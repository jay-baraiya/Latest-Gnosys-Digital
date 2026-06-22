<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Designation;
use App\Models\Order;
use App\Models\Role;
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
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    protected $moduleName = 'Orders';
    protected $moduleUrl = 'admin.orders.index';

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
        return view('admin.order.index');
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {

        $excludedRoles = [User::IS_ADMIN];

        if (isset($request->is_buyer) && $request->is_buyer == 0) {
            $excludedRoles[] = User::IS_BUYER;
        }

        $query = Order::query()
            ->with([
                'tickets:id,ticket_number,datetime,order_id,user_id,developer_id,order_item_id,status,cancelled_by,cancel_reason',
                'user:id,name',
                'tickets.orderItems:id,order_id,product_id,product_name,product_type,variant_id,variant_name,product_price'
            ])
            ->select(['id', 'user_id', 'order_number', 'date_time','total_amount']);

        return DataTables::eloquent($query)
            ->with('total_tasks', $query->count())
            ->addIndexColumn()
            ->addColumn('order_number', function ($row) {
                return '<span class="fw-semibold">#' . $row->order_number . '</span>';
            })
            ->addColumn('order_date', function ($row) {
                return Carbon::parse($row->date_time)->format('d-m-Y');
            })
            ->addColumn('customer_name', function ($row) {
                return $row->user->name ?? '-';
            })
            ->addColumn('total_amount', function ($row) {
                return '$' . number_format($row->total_amount, 2);
            })
            ->addColumn('tickets_list', function ($row) {
                $count = $row->tickets->count();

                if ($count === 0) {
                    return '<span class="text-muted">No tickets</span>';
                }

                return '<button class="btn btn-sm btn-outline-info view-tickets-btn" data-order-id="' . encrypt($row->id) . '">
                            <i class="ti ti-ticket me-1"></i> See Tickets (' . $count . ')
                        </button>';
            })
            ->addColumn('actions', function ($row) use ($request) {
                return view('admin.components.action-links', [
                    'edit'       => route('admin.tasks.edit', encrypt($row->id)),
                    'show'       => route('admin.tasks.show', encrypt($row->id)),
                    'delete'     => route('admin.tasks.destroy', encrypt($row->id)),
                    'id'         => encrypt($row->id),
                ])->render();
            })
            ->rawColumns(['order_number', 'tickets_list', 'actions'])
            ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        view()->share('action', 'Create');
        $roles = Role::query()->where('status', 1)->where('id', '!=', 1)->get();
        $designations = Designation::query()->where('status', 1)->get();

        return view('admin.order.form', compact('roles', 'designations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'country_id' => 'required|exists:countries,id',
            'state_id' => 'required|exists:states,id',
            'city_id' => 'required|exists:cities,id',
            'zip' => 'nullable|string|max:10',
            'status' => 'required|in:1,0',
            'role_id' => 'required|exists:roles,id',
            // 'image' => 'required|image|mimes:jpeg,jpg,png,webp|max:1024',
            'designation_id' => 'required'
        ],
        [
            'image.mimes' => 'Only JPEG, JPG, PNG, and WEBP images are allowed.',
            'image.max' => 'The image size must not exceed 1 MB.',
        ]);

        try {



            return redirect()->route($this->moduleUrl)->with('success', 'User created successfully.');
        } catch (\Exception $e) {
            Log::error('User Store Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'request' => $request->all(),
            ]);

            return redirect()->back()->withInput()->with('error', 'Failed to create user. Please try again later.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        view()->share('action', 'View');
        $user = User::with(['country', 'state', 'city'])->findOrFail(decrypt($id));
        $designations = Designation::query()->where('status', 1)->get();
        return view('admin.order.show', compact('user','designations'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        view()->share('action', 'Edit');
        $user = User::with(['country', 'state', 'city'])->findOrFail(decrypt($id));

        // Notify the currently logged in user so they see the toast/alert immediately
        Auth::user()->notify(new RealTimeNotification('You are editing ' . $user->name . '\'s profile!', []));

        $roles = Role::query()->where('status', 1)->where('id', '!=', 1)->get();
        $designations = Designation::query()->where('status', 1)->get();

        return view('admin.order.form', compact('user', 'roles','designations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        try {
            $userId = decrypt($id);
            $user = User::findOrFail($userId);

            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($userId)],
                'password' => 'nullable|string|min:8|confirmed',
                'phone' => ['nullable', 'string', 'max:20', Rule::unique('users')->ignore($userId)],
                'address' => 'nullable|string',
                'country_id' => 'required|exists:countries,id',
                'state_id' => 'required|exists:states,id',
                'city_id' => 'required|exists:cities,id',
                'zip' => 'nullable|string|max:10',
                'status' => 'required|in:1,0',
                // 'image' => 'required|image|mimes:jpeg,jpg,png,webp|max:1024',
            ],[
                'image.mimes' => 'Only JPEG, JPG, PNG, and WEBP images are allowed.',
                'image.max' => 'The image size must not exceed 1 MB.',
            ]);

            $imagePath = $user->image;

            if ($request->remove_existing_image == '1') {
                if ($user->image && !filter_var($user->image, FILTER_VALIDATE_URL)) {
                    $pathToDelete = str_replace('/storage/', '', $user->image);
                    if(Storage::disk('public')->exists($pathToDelete)) {
                        Storage::disk('public')->delete($pathToDelete);
                    }
                }
                $imagePath = null;
            }

            if ($request->hasFile('image')) {
                if ($user->image && !filter_var($user->image, FILTER_VALIDATE_URL)) {
                    $pathToDelete = str_replace('/storage/', '', $user->image);
                    if(Storage::disk('public')->exists($pathToDelete)) {
                        Storage::disk('public')->delete($pathToDelete);
                    }
                }

                $path = $request->file('image')->store('users', 'public');
                $imagePath = Storage::url($path);

            }

            if (empty($validatedData['password'])) {
                unset($validatedData['password']);
            } else {
                $validatedData['password'] = Hash::make($validatedData['password']);
            }

            $validatedData['image'] = $imagePath;
            $validatedData['is_user'] = 1;

            $user->designation_id = $request->designation_id;

            $user->update($validatedData);


            if ($user && !empty($request->role_id)) {
                UserRole::updateOrCreate([
                    'user_id' => $user->id,
                ], [
                    'role_id' => $request->role_id,
                ]);
            } else {
                UserRole::query()->where('user_id', $user->id)->delete();
            }

            return redirect()->route($this->moduleUrl)->with('success', 'User updated successfully.');
        } catch (\Exception $e) {
            Log::error('User Update Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'request' => $request->all(),
            ]);

            return redirect()->back()->withInput()->with('error', 'Failed to update user. Please try again later.');
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
            $user = User::findOrFail(decrypt($id));
            $user->update(['status' => 0]);
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error('User Destroy Error', [
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

    public function restore(string $id)
    {
        try {
            $user = User::withTrashed()->findOrFail(decrypt($id));

            $user->restore();

            return response()->json([
                'success' => true,
                'message' => 'User restored successfully.'
            ]);

        } catch (\Exception $e) {
            Log::error('User Restore Error', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!'
            ]);
        }
    }

    public function checkEmail(Request $request)
    {
        $query = User::query()->where('email', $request->email);

        if ($request->filled('user_id')) {
            $query->where('id', '!=', $request->user_id);
        }

        if ($query->exists()) {
            return response()->json(false);
        }

        return response()->json(true);
    }

    public function checkPhone(Request $request)
    {
        $query = User::query()->where('phone', $request->phone);

        if ($request->filled('user_id')) {
            $query->where('id', '!=', $request->user_id);
        }

        if ($query->exists()) {
            return response()->json(false);
        }

        return response()->json(true);
    }

    public function getOrderTickets(Request $request)
    {
        try {
            $orderId = decrypt($request->order_id);

            $order = Order::with([
                'tickets:id,ticket_number,datetime,order_id,user_id,developer_id,order_item_id,status,cancelled_by,cancel_reason','user:id,name','orderItems:id,order_id,product_id,product_name,product_type,variant_id,variant_name,product_price,total_amount','tickets.orderItems:id,order_id,product_id,product_name,product_type,variant_id,variant_name,product_price'])->select(['id','user_id','order_number','date_time'
            ])->findOrFail($orderId);

            $html = view('admin.order.ticket_list', compact('order'))->render();

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
                ->whereNotIn('id', [User::IS_ADMIN])
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
}
