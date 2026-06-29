<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Designation;
use App\Models\DigitalProduct;
use App\Models\DigitalService;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Role;
use App\Models\ServiceVariant;
use App\Models\Task;
use App\Models\Ticket;
use App\Models\TicketAttachment;
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

class TicketController extends Controller
{
    protected $moduleName = 'Ticket';
    protected $moduleUrl = 'admin.tickets.index';

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

        $this->middleware('permission:create.tickets')->only('create', 'store');
        $this->middleware('permission:edit.tickets')->only('edit', 'update');
        $this->middleware('permission:delete.tickets')->only('destroy');
        $this->middleware('permission:view.tickets')->only('index', 'show');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $status = $request->input('status', 'pending');
        $priority = $request->input('priority', 'Low');

        $developers = User::query()
                ->with(['roles','designation'])
                ->where('status', 1)
                ->whereHas('roles', function($sq) {
                    $sq->whereNotIn('role_id', [User::IS_ADMIN,User::IS_BUYER]);
                })
                ->get();

        return view('admin.ticket.index', compact('status','priority','developers'));
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {

            $status = $request->input('status');
            $priority = $request->input('priority');

            $query = Ticket::query()
                ->with([
                    'department:id,name',
                    'assign:id,name'
                ])
                ->select([
                    'id',
                    'ticket_number',
                    'datetime',
                    'name',
                    'email',
                    'subject',
                    'department_id',
                    'priority',
                    'status'
                ])
                ->when(!empty($request->input('ticket_number')), function ($query) use ($request) {
                    $search = $request->input('ticket_number');
                    $query->where('ticket_number', 'like', "%{$search}%");
                })
                ->where('status', $status)
                ->where('priority', $priority)
                ->orderBy('id', 'DESC');

            return DataTables::eloquent($query)
                ->with('total_tasks', $query->count())
                ->addIndexColumn()

                ->filter(function ($query) use ($request) {
                    if ($request->has('search') && !empty($request->input('search.value'))) {
                        $search = $request->input('search.value');

                        $query->where(function ($q) use ($search) {
                            $q->where('ticket_number', 'like', "%{$search}%")
                            ->orWhere('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('subject', 'like', "%{$search}%");
                        });
                    }
                }, true)
                ->addColumn('ticket_number', function ($row) {
                    return '<span class="fw-semibold">#' . $row?->ticket_number . '</span>';
                })
                ->addColumn('date', function ($row) {
                    return Carbon::parse($row?->datetime)->format('d-m-Y H:i');
                })
                ->addColumn('client_info', function ($row) {
                    $name = $row->name ?? 'Unknown';
                    $email = $row->email ?? '-';
                    return '<div><span class="fw-medium">' . $name . '</span><br><small class="text-muted">' . $email . '</small></div>';
                })
                ->addColumn('subject', function ($row) {
                    return $row->subject ?? '-';
                })
                ->addColumn('department', function ($row) {
                    return $row->department->name ?? '-';
                })
                ->addColumn('priority', function ($row) {
                    $badges = [
                        'High'   => '<span class="badge bg-danger">High</span>',
                        'Medium' => '<span class="badge bg-warning text-dark">Medium</span>',
                        'Low'    => '<span class="badge bg-info">Low</span>',
                    ];
                    return $badges[$row->priority] ?? '<span class="badge bg-secondary">' . $row->priority . '</span>';
                })
                ->addColumn('status', function ($row) {
                    $badges = [
                        'pending'             => '<span class="badge bg-warning text-dark">Pending</span>',
                        'assign_requested'    => '<span class="badge bg-info">Assign Requested</span>',
                        'assigned'            => '<span class="badge bg-primary">Assigned</span>',
                        'assign_not_accepted' => '<span class="badge bg-secondary">Assign Not Accepted</span>',
                        'in_progress'         => '<span class="badge bg-dark">In Progress</span>',
                        'completed'           => '<span class="badge bg-success">Completed</span>',
                        'cancel_requested'    => '<span class="badge bg-warning text-dark">Cancel Requested</span>',
                        'cancelled'           => '<span class="badge bg-danger">Cancelled</span>',
                        'refund'              => '<span class="badge bg-danger">Refund</span>',
                    ];
                    return $badges[$row->status] ?? '<span class="badge bg-light text-dark">Unknown</span>';
                })
                ->addColumn('actions', function ($row) {
                    return view('admin.components.task-action-link', [
                        'edit'           => route('admin.tickets.edit', encrypt($row->id)),
                        'show'           => route('admin.tickets.show', encrypt($row->id)),
                        'delete'         => route('admin.tickets.destroy', encrypt($row->id)),
                        'id'             => encrypt($row->id),
                        'current_status' => $row->status,
                    ])->render();
                })
                ->rawColumns(['ticket_number', 'client_info', 'priority', 'status', 'actions'])
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        view()->share('action', 'Create');

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

        $cc_recipients = User::query()
                ->whereHas('roles', function($sq) {
                    $sq->whereNotIn('role_id', [User::IS_ADMIN]);
                })
                ->where('status', 1)
                ->get();

        $departments = Role::query()->where('slug', '!=', 'super-admin')->where('status', 1)->get();

        $products  = DigitalProduct::query()->where('status', 1)->get();

        $services  = DigitalService::with('variants')->where('status', 1)->get();

        return view('admin.ticket.form', compact('services', 'products', 'developers', 'users', 'departments', 'cc_recipients'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id'       => 'required|integer|exists:users,id',
            'name'          => 'nullable|string|max:255',
            'email'         => 'required|email|max:255',
            'send_email'    => 'nullable|boolean',

            'cc_recipients' => 'nullable|array',
            'cc_recipients.*'=> 'email',

            'subject'       => 'required|string|max:255',
            'department_id' => 'required|integer',
            'assign_id' => 'required|integer',
            'priority'      => 'required|string|in:High,Medium,Low',
            'description'   => 'required|string',
            'note'          => 'nullable|string',

            'attachments'   => 'nullable|array|max:10',
            'attachments.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx|max:10240',

            'product_type'  => 'nullable|array',
            'product_id'    => 'nullable|array',
            'variant_id'    => 'nullable|array',
            'quantity'      => 'nullable|array',
            'price'         => 'nullable|array',
        ]);

        try {

            $ticket = Ticket::create([
                'ticket_number' => 'TCK-' . strtoupper(Str::random(6)),
                'datetime'      => now(),
                'user_id'       => $request->user_id,
                'name'          => $request->name,
                'email'         => $request->email,
                'cc_recipients' => $request->cc_recipients ? json_encode($request->cc_recipients) : null,
                'subject'       => $request->subject,
                'department_id' => $request->department_id,
                'assign_id' => $request->assign_id,
                'priority'      => $request->priority,
                'description'   => $request->description,
                'note'          => $request->note,
                'status'        => 'pending',
            ]);

            if ($request->hasFile('attachments')) {
                $attachmentPaths = [];

                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('tickets', 'public');
                    $fileUrl = Storage::url($path);

                    TicketAttachment::create([
                        'ticket_id' => $ticket->id,
                        'file_path' => $fileUrl,
                    ]);

                    $attachmentPaths[] = $fileUrl;
                }

                $ticket->update(['attachments' => json_encode($attachmentPaths)]);
            }

            if ($ticket->id) {
            $productTypes = $request->input('product_type', []);
            $productIds   = $request->input('product_id', []);
            $variantIds   = $request->input('variant_id', []);
            $quantities   = $request->input('quantity', []);
            $prices       = $request->input('price', []);

            if (!empty($productTypes) && is_array($productTypes)) {

                foreach ($productTypes as $i => $type) {

                    if (!empty($productIds[$i])) {
                        Task::create([
                            'ticket_id'    => $ticket->id,
                            'product_type' => $type,
                            'product_id'   => $productIds[$i],
                            'variant_id'   => !empty($variantIds[$i]) ? $variantIds[$i] : null,
                            'quantity'     => $quantities[$i] ?? 1,
                            'price'        => $prices[$i] ?? 0.00,
                        ]);
                    }
                }
            }
        }

            return redirect()->route($this->moduleUrl)->with('success', 'Ticket created successfully.');
        } catch (\Exception $e) {
            Log::error('Ticket Store Error', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'request' => $request->except('attachments'),
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

        $orderItems_product = OrderItem::query()->where('order_id', $ticket->order_id)->where('product_type', 'product')->pluck('product_id')->toArray();
        $orderItems_service = OrderItem::query()->where('order_id', $ticket->order_id)->where('product_type', 'service')->pluck('product_id')->toArray();

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

        $cc_recipients = User::query()
                ->where('status', 1)
                ->get();

        $departments = Role::query()->where('slug', '!=', 'super-admin')->where('status', 1)->get();

        $products = DigitalProduct::query()->where('status', 1)
        ->when((!empty($ticket->order_id) && !empty($orderItems_product)), function($q) use ($orderItems_product) {
            $q->whereIn('id', $orderItems_product);
        })
        ->get();

        $services = DigitalService::with('variants')->where('status', 1)
        ->when((!empty($ticket->order_id) && !empty($orderItems_service)), function($q) use ($orderItems_service) {
            $q->whereIn('id', $orderItems_service);
        })
        ->get();

        $tasks = Task::query()->select([
            'id',
            'ticket_id',
            'product_type',
            'product_id',
            'product_name',
            'variant_id',
            'variant_name',
            'quantity',
            'price',
            'status'
        ])->where('ticket_id', decrypt($id))->get();

        return view('admin.ticket.show', compact('tasks', 'products', 'services', 'developers', 'users', 'ticket', 'cc_recipients', 'departments'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        view()->share('action', 'Edit');

        $ticket = Ticket::find(decrypt($id));

        $orderItems_product = OrderItem::query()->where('order_id', $ticket->order_id)->where('product_type', 'product')->pluck('product_id')->toArray();
        $orderItems_service = OrderItem::query()->where('order_id', $ticket->order_id)->where('product_type', 'service')->pluck('product_id')->toArray();

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

        $cc_recipients = User::query()
                ->where('status', 1)
                ->get();

        $departments = Role::query()->where('slug', '!=', 'super-admin')->where('status', 1)->get();

        $products = DigitalProduct::query()->where('status', 1)
        ->when((!empty($ticket->order_id) && !empty($orderItems_product)), function($q) use ($orderItems_product) {
            $q->whereIn('id', $orderItems_product);
        })
        ->get();

        $services = DigitalService::with('variants')->where('status', 1)
        ->when((!empty($ticket->order_id) && !empty($orderItems_service)), function($q) use ($orderItems_service) {
            $q->whereIn('id', $orderItems_service);
        })
        ->get();



        $tasks = Task::query()->select([
            'id',
            'ticket_id',
            'product_type',
            'product_id',
            'product_name',
            'variant_id',
            'variant_name',
            'quantity',
            'price',
            'status'
        ])->where('ticket_id', decrypt($id))->get();

        return view('admin.ticket.form', compact('tasks', 'products', 'services', 'developers', 'users', 'ticket', 'cc_recipients', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'user_id'              => 'required|integer|exists:users,id',
            'name'                 => 'nullable|string|max:255',
            'email'                => 'required|email|max:255',
            'send_email'           => 'nullable|boolean',
            'cc_recipients'        => 'nullable|array',
            'cc_recipients.*'      => 'email',
            'subject'              => 'required|string|max:255',
            'department_id'        => 'required|integer',
            'assign_id'            => 'required|integer',
            'priority'             => 'required|string|in:High,Medium,Low',
            'description'          => 'required|string',
            'note'                 => 'nullable|string',
            'attachments'          => 'nullable|array|max:10',
            'attachments.*'        => 'file|mimes:jpg,jpeg,png,pdf,doc,docx|max:10240',
            'existing_attachments' => 'nullable|array',
            'existing_attachments.*'=> 'string',

            'task_id'              => 'nullable|array',
            'product_type'         => 'nullable|array',
            'product_id'           => 'nullable|array',
            'variant_id'           => 'nullable|array',
            'quantity'             => 'nullable|array',
            'price'                => 'nullable|array',
        ]);

        try {
            $ticketId = decrypt($id);
            $ticket = Ticket::findOrFail($ticketId);

            $oldFiles = $ticket->attachments ? json_decode($ticket->attachments, true) : [];
            if (!is_array($oldFiles)) {
                $oldFiles = [];
            }

            $keptFiles = $request->input('existing_attachments', []);

            $removedFiles = array_diff($oldFiles, $keptFiles);

            if (!empty($removedFiles)) {
                foreach ($removedFiles as $fileUrl) {
                    $pathToDelete = str_replace('/storage/', '', $fileUrl);
                    if (Storage::disk('public')->exists($pathToDelete)) {
                        Storage::disk('public')->delete($pathToDelete);
                    }

                    TicketAttachment::query()->where('ticket_id', $ticket->id)
                                    ->where('file_path', $fileUrl)
                                    ->delete();
                }
            }

            $allActiveFiles = $keptFiles;

            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('tickets', 'public');
                    $fileUrl = Storage::url($path);

                    TicketAttachment::create([
                        'ticket_id' => $ticket->id,
                        'file_path' => $fileUrl,
                    ]);

                    $allActiveFiles[] = $fileUrl;
                }
            }

            $ticket->update([
                'user_id'       => $request->user_id,
                'name'          => $request->name,
                'email'         => $request->email,
                'cc_recipients' => $request->cc_recipients ? json_encode($request->cc_recipients) : null,
                'subject'       => $request->subject,
                'department_id' => $request->department_id,
                'assign_id'     => $request->assign_id,
                'priority'      => $request->priority,
                'description'   => $request->description,
                'note'          => $request->note,

                'attachments'   => !empty($allActiveFiles) ? json_encode(array_values($allActiveFiles)) : null
            ]);

            $taskIds      = $request->input('task_id', []);
            $productTypes = $request->input('product_type', []);
            $productIds   = $request->input('product_id', []);
            $variantIds   = $request->input('variant_id', []);
            $quantities   = $request->input('quantity', []);
            $prices       = $request->input('price', []);

            $processedTaskIds = [];

            if (!empty($productTypes) && is_array($productTypes)) {
                foreach ($productTypes as $i => $type) {
                    if (!empty($productIds[$i])) {

                        $taskId = !empty($taskIds[$i]) ? $taskIds[$i] : null;

                        $taskData = [
                            'ticket_id'    => $ticket->id,
                            'product_type' => $type,
                            'product_id'   => $productIds[$i],
                            'variant_id'   => !empty($variantIds[$i]) ? $variantIds[$i] : null,
                            'quantity'     => $quantities[$i] ?? 1,
                            'price'        => $prices[$i] ?? 0.00,
                        ];

                        if ($taskId) {
                            Task::query()
                                ->where('id', $taskId)
                                ->where('ticket_id', $ticket->id)
                                ->update($taskData);

                            $processedTaskIds[] = $taskId;
                        } else {
                            $newTask = Task::create($taskData);
                            $processedTaskIds[] = $newTask->id;
                        }
                    }
                }
            }

            if (!empty($processedTaskIds)) {
                Task::query()
                    ->where('ticket_id', $ticket->id)
                    ->whereNotIn('id', $processedTaskIds)
                    ->delete();
            } else {
                Task::query()->where('ticket_id', $ticket->id)->delete();
            }

            return redirect()->route($this->moduleUrl ?? 'admin.tickets.index')->with('success', 'Ticket updated successfully.');

        } catch (\Exception $e) {
            Log::error('Ticket Update Error', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'request' => $request->except(['attachments', 'existing_attachments']),
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
        $ticket_id = decrypt($request->ticket_id);
        $developer_id = $request->developer_id;

        $ticket = Ticket::find($ticket_id);

        if ($ticket) {
            $ticket->developer_id = $developer_id;
            $ticket->status = 'assigned';

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

    public function updateTicketStatus(Request $request)
    {
        $request->validate([
            'ticket_id' => 'required',
            'status' => 'required|in:pending,assign_requested,assigned,assign_not_accepted,in_progress,completed,cancel_requested,cancelled,refund'
        ]);

        try {
            $ticket_id = decrypt($request->ticket_id);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Ticket ID'
            ], 400);
        }

        $ticket = Ticket::find($ticket_id);

        if ($ticket) {

            if ($request->status == 'assigned' && $request->assign_id) {
                $ticket->assign_id = $request->assign_id;
            }

            $ticket->status = $request->status;
            $ticket->update();

            return response()->json([
                'success' => true,
                'message' => 'Ticket status updated successfully!',
                'data' => $ticket
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Ticket not found'
        ], 404);
    }

    public function getItemQty(Request $request)
    {
        $request->validate([
            'order_id'     => 'required|integer',
            'product_id'   => 'required|integer',
            'product_type' => 'required|string',
        ]);

        $orderItem = OrderItem::where('order_id', $request->order_id)
                            ->where('product_id', $request->product_id)
                            ->where('product_type', $request->product_type)
                            ->first();

        if ($orderItem) {
            return response()->json([
                'success' => true,
                'data'    => $orderItem,
                'qty'     => $orderItem->product_qty ?? 1,
                'price'   => $orderItem->product_price ?? 0
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Item not found in this order.'
        ]);
    }
}
