<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Designation;
use App\Models\DigitalProduct;
use App\Models\DigitalService;
use App\Models\Order;
use App\Models\Role;
use App\Models\ServiceVariant;
use App\Models\Ticket;
use App\Models\User;
use App\Models\UserRole;
use App\Notifications\RealTimeNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class TaskController extends Controller
{
    protected $moduleName = 'Tasks';
    protected $moduleUrl = 'admin.tasks.index';

    protected $authUser;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->authUser = Auth::user();

            view()->share([
                'moduleName' => $this->moduleName,
                'moduleUrl' => $this->moduleUrl,
            ]);

            return $next($request);
        });

        // $this->middleware('permission:create.users')->only('create', 'store');
        // $this->middleware('permission:edit.users')->only('edit', 'update');
        // $this->middleware('permission:delete.users')->only('destroy');
        // $this->middleware('permission:view.users')->only('index', 'show');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = Ticket::query()
            ->with([
                'order:id,order_number,date_time',
                'developer:id,name',
                'developer.roles:id,name',
                'orderItems:id,order_id,product_id,product_name,product_type,variant_id,variant_name,product_price'
            ])
            ->select(['id','ticket_number','datetime','order_id','user_id','developer_id','order_item_id','cancelled_by','status'])
            ->get();

        // echo '<pre>';
        // print_r($data->toArray());
        // echo '</pre>';
        // exit;


        return view('admin.task.index');
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {

        $excludedRoles = [User::IS_ADMIN];

        if (isset($request->is_buyer) && $request->is_buyer == 0) {
            $excludedRoles[] = User::IS_BUYER;
        }

        $query = Ticket::query()
            ->with([
                'order:id,order_number,date_time',
                'developer:id,name',
                'developer.roles:id,name',
                'user:id,name',
                'orderItems:id,order_id,product_id,product_name,product_type,variant_id,variant_name,product_price'
            ])
            ->select(['id','ticket_number','datetime','order_id','user_id','developer_id','order_item_id','cancelled_by','status']);

        return DataTables::eloquent($query)
            ->with('total_tasks', $query->count())
            ->addIndexColumn()
            ->addColumn('order_number', function ($row) {
                $order_number = (!empty($row->order->order_number) ? '#'.$row->order->order_number : '-');
                return '<span class="fw-semibold">' . $order_number . '</span>';
            })
            ->addColumn('ticket_number', function ($row) {
                return '<span class="fw-semibold">#' . $row?->ticket_number . '</span>';
            })
            ->addColumn('order_date', function ($row) {
                return Carbon::parse($row?->order?->date_time)->format('d-m-Y');
            })
            ->addColumn('customer_name', function ($row) {
                return $row->user->name ?? '-';
            })
            ->addColumn('ticket_name', function ($row) {
                $productName = $row?->orderItems?->product_name;
                $productType = $row?->orderItems?->product_type;

                return $productName
                    ? $productName . ($productType ? ' - ( ' . ucfirst($productType) . ' )' : '')
                    : '-';
            })
            ->addColumn('developer_name', function ($row) {
                $developerName = $row?->developer?->name;
                $roleName = $row?->developer?->roles?->first()?->name;

                return $developerName
                    ? $developerName . ($roleName ? ' - (' . $roleName . ')' : '')
                    : '-';
            })
            ->addColumn('total_amount', function ($row) {
                $amount = $row?->orderItems?->product_price ?? 0;
                return '$' . number_format($amount, 2);
            })
            ->addColumn('actions', function ($row) use ($request) {
                return view('admin.components.action-links', [
                    'edit'       => route('admin.tasks.edit', encrypt($row->id)),
                    'show'       => route('admin.tasks.show', encrypt($row->id)),
                    'delete'     => route('admin.tasks.destroy', encrypt($row->id)),
                    'id'         => encrypt($row->id),
                ])->render();
            })
            ->rawColumns(['order_number', 'ticket_number', 'order_date', 'customer_name', 'total_amount', 'ticket_name', 'developer_name', 'actions'])
            ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        view()->share('action', 'Create');

        $services = DigitalService::query()->select(['id','name'])->where('status', 1)->get();

        $products = DigitalProduct::query()->select(['id','name'])->where('status', 1)->get();

        $developers = User::query()
                ->with(['roles','designation'])
                ->where('status', 1)
                ->whereHas('roles', function($sq) {
                    $sq->whereIn('role_id', [User::IS_DEVELOPER])
                    ->whereNotIn('role_id', [User::IS_ADMIN]);
                })
                ->get();

        $users = User::query()
                ->with(['roles'])
                ->where('status', 1)
                ->whereHas('roles', function($sq) {
                    $sq->whereNotIn('role_id', [User::IS_ADMIN])
                    ->whereIn('role_id', [User::IS_BUYER]);
                })
                ->get();

        $ticket_number = 'TCK-' . strtoupper(Str::random(6));

        return view('admin.task.form', compact('products', 'services', 'developers', 'users', 'ticket_number'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'ticket_number' => 'required|string|max:255',

            'product_id' => 'required_without:service_id|nullable|integer|exists:digital_products,id',
            'service_id' => 'required_without:product_id|nullable|integer|exists:digital_services,id',

            'user_id' => 'required|integer|exists:users,id',
            'developer_id' => 'required|integer|exists:users,id',

            'status' => 'required|string|in:pending,assign_requested,assigned,assign_not_accepted,in_progress,completed,cancel_requested,cancelled,refund',

            'cancel_reason' => 'required_if:status,cancelled,cancel_requested|nullable|string|max:1000',
        ],
        [
            'product_id.required_without' => 'Please select a Product or switch to Services.',
            'service_id.required_without' => 'Please select a Service or switch to Products.',
            'cancel_reason.required_if' => 'The cancel reason field is required when the status is cancelled or cancel requested.',
            'status.in' => 'The selected status is invalid.'
        ]);

        try {

            $ticket = Ticket::create([
                'ticket_number' => $request->ticket_number,
                'datetime' => now(),
                'user_id' => $request->user_id,
                'developer_id' => $request->developer_id,
                'variant_id' => $request->service_variant_id ?? null,
                'order_item_id' => !empty($request->product_id) ? $request->product_id : $request->service_id,
                'status' => $request->status,
                'cancelled_by' => ($request->status == 'cancelled' ? Auth::id() : null),
                'cancel_reason' => ($request->status == 'cancelled' ? $request->cancel_reason : null),
            ]);


            return redirect()->route($this->moduleUrl)->with('success', 'Ticket created successfully.');
        } catch (\Exception $e) {
            Log::error('Ticket Store Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'request' => $request->all(),
            ]);

            return redirect()->back()->withInput()->with('error', 'Failed to create ticket. Please try again later.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        view()->share('action', 'View');
        $ticket = Ticket::find(decrypt($id));

        $services = DigitalService::query()->select(['id','name'])->where('status', 1)->get();

        $products = DigitalProduct::query()->select(['id','name'])->where('status', 1)->get();

        $developers = User::query()
                ->with(['roles','designation'])
                ->where('status', 1)
                ->whereHas('roles', function($sq) {
                    $sq->whereIn('role_id', [User::IS_DEVELOPER])
                    ->whereNotIn('role_id', [User::IS_ADMIN]);
                })
                ->get();

        $users = User::query()
                ->with(['roles'])
                ->where('status', 1)
                ->whereHas('roles', function($sq) {
                    $sq->whereNotIn('role_id', [User::IS_ADMIN])
                    ->whereIn('role_id', [User::IS_BUYER]);
                })
                ->get();

        return view('admin.task.show', compact('services', 'products', 'developers', 'users', 'ticket'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        view()->share('action', 'Edit');

        $ticket = Ticket::find(decrypt($id));

        $services = DigitalService::query()->select(['id','name'])->where('status', 1)->get();

        $products = DigitalProduct::query()->select(['id','name'])->where('status', 1)->get();

        $developers = User::query()
                ->with(['roles','designation'])
                ->where('status', 1)
                ->whereHas('roles', function($sq) {
                    $sq->whereIn('role_id', [User::IS_DEVELOPER])
                    ->whereNotIn('role_id', [User::IS_ADMIN]);
                })
                ->get();

        $users = User::query()
                ->with(['roles'])
                ->where('status', 1)
                ->whereHas('roles', function($sq) {
                    $sq->whereNotIn('role_id', [User::IS_ADMIN])
                    ->whereIn('role_id', [User::IS_BUYER]);
                })
                ->get();

        return view('admin.task.form', compact('services', 'products', 'developers', 'users', 'ticket'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'ticket_number' => 'required|string|max:255',
            'product_id'    => 'required_without:service_id|nullable|integer',
            'service_id'    => 'required_without:product_id|nullable|integer',
            'user_id'       => 'required|integer',
            'developer_id'  => 'required|integer',
            'status'        => 'required|string|in:pending,assign_requested,assigned,assign_not_accepted,in_progress,completed,cancel_requested,cancelled,refund',
            'cancel_reason' => 'required_if:status,cancelled,cancel_requested|nullable|string|max:1000',
        ],
        [
            'product_id.required_without' => 'Please select a Product or switch to Services.',
            'service_id.required_without' => 'Please select a Service or switch to Products.',
            'cancel_reason.required_if'   => 'The cancel reason field is required when the status is cancelled or cancel requested.',
            'status.in'                   => 'The selected status is invalid.'
        ]);

        try {
            $ticketId = decrypt($id);

            $ticket = Ticket::findOrFail($ticketId);

            $cancelledBy = $ticket->cancelled_by;
            if ($request->status == 'cancelled' && $ticket->status != 'cancelled') {
                $cancelledBy = Auth::id();
            } elseif ($request->status != 'cancelled' && $request->status != 'cancel_requested') {
                $cancelledBy = null;
            }

            $cancelReason = (in_array($request->status, ['cancelled', 'cancel_requested']))
                            ? $request->cancel_reason
                            : null;

            $ticket->update([
                'user_id'       => $request->user_id,
                'developer_id'  => $request->developer_id,
                'order_item_id' => !empty($request->product_id) ? $request->product_id : $request->service_id,
                'status'        => $request->status,
                'cancelled_by'  => $cancelledBy,
                'cancel_reason' => $cancelReason,
            ]);

            return redirect()->route($this->moduleUrl)->with('success', 'Ticket updated successfully.');

        } catch (\Exception $e) {
            Log::error('Ticket Update Error', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'request' => $request->all(),
            ]);

            return redirect()->back()->withInput()->with('error', 'Failed to update ticket. Please try again later.');
        }
    }

    public function updateStatus(Request $request)
    {
        try {
            $user = User::withTrashed()->findOrFail(decrypt($request->id));
            $user->update([
                'status' => $request->status,
            ]);

            return response()->json([
                'success' => true,
                'message' => $user->status == 1 ? 'User activated successfully.' : 'User deactivated successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error('User Status Update Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'request' => $request->all(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $ticket = Ticket::findOrFail(decrypt($id));
            $ticket->delete();

            return response()->json([
                'success' => true,
                'message' => 'Ticket deleted successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error('Ticket Destroy Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!'
            ]);
        }
    }

    public function getOrderTickets(Request $request)
    {
        try {
            $orderId = decrypt($request->order_id);

            $order = Order::with([
                'tickets:id,ticket_number,datetime,order_id,user_id,developer_id,order_item_id,status,cancelled_by,cancel_reason','user:id,name','orderItems:id,order_id,product_id,product_name,product_type,variant_id,variant_name,product_price,total_amount','tickets.orderItems:id,order_id,product_id,product_name,product_type,variant_id,variant_name,product_price'])->select(['id','user_id','order_number','date_time'
            ])->findOrFail($orderId);

            $html = view('admin.task.ticket_list', compact('order'))->render();

            return response()->json([
                'status' => 'success',
                'html'   => $html
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getDevUser()
    {
        $data = User::query()
                ->with(['roles'])
                ->where('status', 1)
                ->whereHas('roles', function($sq) {
                    $sq->whereNotIn('role_id', [User::IS_ADMIN, User::IS_BUYER]);
                })
                ->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function assignDevUser(Request $request)
    {
        $ticket_id = $request->ticket_id;
        $developer_id = $request->developer_id;

        $ticket = Ticket::find($ticket_id);

        if ($ticket) {
            $ticket->developer_id = $developer_id;

            $ticket->update();

            return response()->json([
                'success' => true,
                'message' => 'Developer assigned successfully',
                'data' => $ticket
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Ticket not found'
        ], 404);
    }

    public function getServiceVariant(Request $request)
    {
        $service_id = $request->service_id;

        $variants = ServiceVariant::query()->where('service_id', $service_id)->get();

        return response()->json([
            'success' => 1,
            'variants' => $variants
        ]);
    }
}
