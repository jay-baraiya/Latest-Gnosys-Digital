<?php

namespace App\Http\Controllers\Admin;

use App\Models\CustomField;
use App\Models\Department;
use App\Models\DigitalProduct;
use App\Models\DigitalService;
use App\Models\Task;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
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
        return view('admin.task.index');
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {

        $excludedRoles = [User::IS_ADMIN];

        if (isset($request->is_buyer) && $request->is_buyer == 0) {
            $excludedRoles[] = User::IS_BUYER;
        }

            $data = Task::with([
                        'ticket:id,ticket_number',
                        'department:id,name',
                        'assign:id,name'
                    ])
                    ->select([
                        'id',
                        'title',
                        'task_number',
                        'ticket_id',
                        'department_id',
                        'assign_id',
                        'product_type',
                        'product_id',
                        'product_name',
                        'variant_id',
                        'variant_name',
                        'price',
                        'status'
                    ])
                    ->when(!empty($request->input('search.value')), function ($query) use ($request) {
                        $query->where(function($q) use ($request) {
                            $q->where('title', 'like', "%{$request->input('search.value')}%")
                            ->orWhere('variant_name', 'like', "%{$request->input('search.value')}%")
                            ->orWhere('product_name', 'like', "%{$request->input('search.value')}%");
                        });
                    });

            return DataTables::eloquent($data)
                ->with('total_users', $data->count())
                ->addIndexColumn()
                ->editColumn('task_number', function ($row) {
                    return '#' . $row->task_number;
                })
                ->editColumn('status', function ($row) {

                    $badges = [
                        'pending'     => 'warning',
                        'assigned'    => 'info',
                        'in_progress' => 'primary',
                        'on_hold'     => 'secondary',
                        'completed'   => 'success',
                        'cancelled'   => 'danger',
                        'refunded'    => 'dark',
                    ];

                    $status = $row->status;
                    $class = $badges[$status] ?? 'secondary';

                    return '<span class="badge bg-' . $class . '">' . ucwords(str_replace('_', ' ', $status)) . '</span>';
                })
                ->addColumn('title', function ($row) {
                    return $row->title ?? '-';
                })
                ->addColumn('ticket_number', function ($row) {
                    if (!empty($row->ticket?->ticket_number)) {
                        return '<a target="_blank" href="' . route('admin.tickets.edit', ['ticket' => encrypt($row->ticket_id)]) . '">#' . $row->ticket->ticket_number . '</a>';
                    } else {
                        return '-';
                    }
                })
                ->addColumn('assign', function ($row) {
                    return $row?->assign?->name ?? '-';
                })
                ->addColumn('department', function ($row) {
                    return $row?->department?->name ?? '-';
                })
                ->addColumn('actions', function ($row) use ($request) {
                    return view('admin.components.action-links', [
                        'edit' => route('admin.tasks.edit', encrypt($row->id)),
                        // 'show' => route('admin.tasks.show', encrypt($row->id)),
                        'delete' => route('admin.tasks.destroy', encrypt($row->id)),
                        'restore' => route('admin.tasks.restore', encrypt($row->id)),
                        'id' => encrypt($row->id),
                        'is_deleted' => $request->is_deleted,
                    ])->render();
                })
                ->rawColumns(['task_number', 'status', 'ticket_number','assign','department','actions'])
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        view()->share('action', 'Create');

        $departments = Department::query()->where('status', 1)->get();

        $products  = DigitalProduct::query()->where('status', 1)->get();

        $services  = DigitalService::with('variants')->where('status', 1)->get();

        $developers = User::query()
                ->with(['roles','designation'])
                ->where('status', 1)
                ->whereHas('roles', function($sq) {
                    $sq->whereIn('role_id', [User::IS_DEVELOPER])
                    ->whereNotIn('role_id', [User::IS_ADMIN]);
                })
                ->get();

        $tickets = Ticket::select(['id', 'ticket_number'])->get();

        $task_number = 'TSK-' . strtoupper(Str::random(6));

        return view('admin.task.form', compact('tickets', 'task_number', 'departments', 'products', 'services', 'developers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'task_number'   => 'required',
            'title'         => 'required|string|max:255',
            'product_type'  => 'required',
            'description'   => 'required',
            'status'        => 'required',
            // 'product_id'    => 'required',
            'ticket_id'     => 'nullable',
            'due_date'      => 'required',
            'department_id' => 'required',
            'assign_id'     => 'required'
        ]);

        $product_id = $request->input('product_id');
        $service_id = $request->input('service_id');
        $product_type = $request->input('product_type');
        $is_variant = $request->input('is_variant');
        $service_variant = $request->input('service_variant_id');
        $duedate = $request->input('due_date');

        $product_name = null;
        $product_price = 0;

        $variant_id = null;
        $variant_name = null;

        if ($product_type == 'product') {
            $products = DigitalProduct::query()
                        ->where('id', $product_id)
                        ->where('status', 1)
                        ->first();

            $product_name = $products->name ?? '';
            $product_price = $products->price ?? 0;

        } else if ($product_type == 'service') {
            $services = DigitalService::query()
                        ->where('id', $service_id)
                        ->with(['variants' => function ($sq) use ($is_variant, $service_variant) {
                            if ($is_variant && $service_variant) {
                                $sq->where('id', $service_variant);
                            }
                        }])
                        ->where('status', 1)
                        ->first();

            $product_name = $services->name ?? '';
            $product_price = $services->price ?? 0;

            if (!empty($services->variants[0])) {
                $variant_id = $services->variants[0]->id;
                $variant_name = $services->variants[0]->name;
                $product_price = $services->variants[0]->price;
            }
        }

        try {

            Task::create([
                'title'          => $request->title,
                'task_number'    => $request->task_number,
                'ticket_id'      => $request->ticket_id,
                'department_id'  => $request->department_id,
                'user_id'        => $request->user_id,
                'product_type'   => $request->product_type,
                'product_id'     => (($request->product_type == 'product') ? $request->product_id : $request->service_id),
                'due_date'       => $duedate,
                'variant_id'     => $variant_id,
                'variant_name'   => $variant_name,
                'quantity'       => 1,
                'price'          => $product_price ?? 0.00,
                'status'         => !empty($request->status) ? $request->status : 'pending',
                'description'    => $request->description,
                'cancel_reason'  => $request->cancel_reason,
                'product_name'   => $product_name,
                'assign_id'      => $request->assign_id
            ]);

            return redirect()->route($this->moduleUrl)->with('success', 'Task created successfully.');
        } catch (\Exception $e) {
            Log::error('Ticket Store Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'request' => $request->all(),
            ]);

            return redirect()->back()->withInput()->with('error', 'Failed to create Task. Please try again later.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        view()->share('action', 'View');
        $user = User::with(['country', 'state', 'city'])->findOrFail(decrypt($id));
        $roles = Role::query()->where('status', 1)->where('id', '!=', 1)->get();
        $designations = Designation::query()->where('status', 1)->get();
        return view('admin.user.show', compact('user','roles','designations'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, string $id)
    {
        view()->share('action', 'Open');
        $task = Task::with([
                    'ticket:id,ticket_number',
                    'department:id,name',
                    'assign:id,name',
                    'notes',
                ])
                ->findOrFail(decrypt($id));

        $departments = Department::query()->where('status', 1)->get();

        $products  = DigitalProduct::query()->where('status', 1)->get();

        $services  = DigitalService::with('variants')->where('status', 1)->get();

        $developers = User::query()
                ->with(['roles','designation'])
                ->where('status', 1)
                ->whereHas('roles', function($sq) {
                    $sq->whereIn('role_id', [User::IS_DEVELOPER])
                    ->whereNotIn('role_id', [User::IS_ADMIN]);
                })
                ->get();

        $tickets = Ticket::select(['id', 'ticket_number'])->get();

        $customfields = CustomField::with(['fieldType'])
                        ->where('module_type', 'department')
                        ->where('recode_id', $task->department_id)
                        ->get();

        $tab = $request->input('tab', 'task-detail');

        return view('admin.task.edit', compact('tab','customfields','task','departments','products','services','developers','tickets'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $taskId = decrypt($id);
            $task = Task::findOrFail($taskId);

            $validatedData = $request->validate([
                'task_number'   => 'required',
                'title'         => 'required|string|max:255',
                'product_type'  => 'required',
                'description'   => 'required',
                'status'        => 'required',
                'ticket_id'     => 'nullable',
                'due_date'      => 'required',
                'department_id' => 'required',
                'assign_id'     => 'required'
            ]);

            $product_id = $request->input('product_id');
            $service_id = $request->input('service_id');
            $product_type = $request->input('product_type');
            $is_variant = $request->input('is_variant');
            $service_variant = $request->input('service_variant_id');
            $duedate = $request->input('due_date');

            $product_name = null;
            $product_price = 0;

            $variant_id = null;
            $variant_name = null;

            if ($product_type == 'product') {
                $products = DigitalProduct::query()
                            ->where('id', $product_id)
                            ->where('status', 1)
                            ->first();

                $product_name = $products->name ?? '';
                $product_price = $products->price ?? 0;

            } else if ($product_type == 'service') {
                $services = DigitalService::query()
                            ->where('id', $service_id)
                            ->with(['variants' => function ($sq) use ($is_variant, $service_variant) {
                                if ($is_variant && $service_variant) {
                                    $sq->where('id', $service_variant);
                                }
                            }])
                            ->where('status', 1)
                            ->first();

                $product_name = $services->name ?? '';
                $product_price = $services->price ?? 0;

                if (!empty($services->variants[0])) {
                    $variant_id = $services->variants[0]->id;
                    $variant_name = $services->variants[0]->name;
                    $product_price = $services->variants[0]->price;
                }
            }

            $oldStatus = $task->status;

            $task->update([
                'title'          => $request->title,
                'task_number'    => $request->task_number,
                'ticket_id'      => $request->ticket_id,
                'department_id'  => $request->department_id,
                'product_type'   => $request->product_type,
                'product_id'     => (($request->product_type == 'product') ? $request->product_id : $request->service_id),
                'due_date'       => $duedate,
                'variant_id'     => $variant_id,
                'variant_name'   => $variant_name,
                'price'          => $product_price ?? 0.00,
                'status'         => !empty($request->status) ? $request->status : 'pending',
                'description'    => $request->description,
                'cancel_reason'  => $request->cancel_reason,
                'product_name'   => $product_name,
                'assign_id'      => $request->assign_id
            ]);

            $newStatus = !empty($request->status) ? $request->status : 'pending';
            if ($oldStatus != $newStatus) {
                \App\Models\Note::create([
                    'task_id' => $task->id,
                    'ref_type' => 'internal_note',
                    'title' => 'Status Updated',
                    'datetime' => now(),
                    'text' => 'Task status changed from ' . ucfirst(str_replace('_', ' ', $oldStatus)) . ' to ' . ucfirst(str_replace('_', ' ', $newStatus)),
                    'user_id' => Auth::id(),
                ]);
            }

            return redirect()->route($this->moduleUrl)->with('success', 'Task updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Task Update Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'request' => $request->all(),
            ]);

            return redirect()->back()->withInput()->with('error', 'Failed to update Task. Please try again later.');
        }
    }

    public function storeReply(Request $request, string $id)
    {
        $taskId = decrypt($id);
        $task = Task::findOrFail($taskId);

        $request->validate([
            'description' => 'nullable|string',
            'ticket_status' => 'nullable|string|in:pending,assigned,in_progress,completed,cancelled,refund,on_hold',
        ]);

        try {
            if (!empty($request->description)) {
                \App\Models\Note::create([
                    'task_id' => $task->id,
                    'ref_type' => 'reply',
                    'datetime' => now(),
                    'text' => $request->description,
                    'user_id' => Auth::id(),
                ]);
            }

            if ($request->ticket_status) {
                $oldStatus = $task->status;
                $task->update(['status' => $request->ticket_status]);

                if ($oldStatus != $request->ticket_status) {
                    \App\Models\Note::create([
                        'task_id' => $task->id,
                        'ref_type' => 'reply',
                        'title' => 'Status Updated',
                        'datetime' => now(),
                        'text' => 'Task status changed from ' . ucfirst(str_replace('_', ' ', $oldStatus)) . ' to ' . ucfirst(str_replace('_', ' ', $request->ticket_status)),
                        'user_id' => Auth::id(),
                    ]);
                }
            }

            return redirect()->back()->with('success', 'Reply posted successfully.');
        } catch (\Exception $e) {
            \Log::error('Store Task Reply Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return redirect()->back()->with('error', 'Failed to post reply. Please try again.');
        }
    }

    public function storeInternalNote(Request $request, string $id)
    {
        $taskId = decrypt($id);
        $task = Task::findOrFail($taskId);

        $request->validate([
            'internal_note' => 'nullable|string',
            'ticket_status' => 'nullable|string|in:pending,assigned,in_progress,completed,cancelled,refund,on_hold',
        ]);

        try {
            if (!empty($request->internal_note)) {
                \App\Models\Note::create([
                    'task_id' => $task->id,
                    'ref_type' => 'internal_note',
                    'title' => $request->internal_note_title,
                    'datetime' => now(),
                    'text' => $request->internal_note,
                    'user_id' => Auth::id(),
                ]);
            }

            if ($request->ticket_status) {
                $oldStatus = $task->status;
                $task->update(['status' => $request->ticket_status]);

                if ($oldStatus != $request->ticket_status) {
                    \App\Models\Note::create([
                        'task_id' => $task->id,
                        'ref_type' => 'internal_note',
                        'title' => 'Status Updated',
                        'datetime' => now(),
                        'text' => 'Task status changed from ' . ucfirst(str_replace('_', ' ', $oldStatus)) . ' to ' . ucfirst(str_replace('_', ' ', $request->ticket_status)),
                        'user_id' => Auth::id(),
                    ]);
                }
            }

            return redirect()->back()->with('success', 'Internal note posted successfully.');
        } catch (\Exception $e) {
            \Log::error('Store Task Internal Note Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return redirect()->back()->with('error', 'Failed to post internal note. Please try again.');
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
}
