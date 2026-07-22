<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\CustomField;
use App\Models\Department;
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
use App\Models\Note;
use App\Models\User;
use App\Models\UserRole;
use App\Notifications\RealTimeNotification;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class TicketController extends Controller
{
    protected $moduleName = 'Tickets';
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
                    'user:id,name,email',
                    'department:id,name',
                    'assign:id,name'
                ])
                ->select([
                    'id',
                    'user_id',
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
                // ->where('status', $status)
                // ->where('priority', $priority)
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
                    $name = $row?->user?->name ?? 'Unknown';
                    $email = $row?->user?->email ?? '-';
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
                        // 'show'           => route('admin.tickets.show', encrypt($row->id)),
                        'delete'         => route('admin.tickets.destroy', encrypt($row->id)),
                        'invoice'        => route('admin.tickets.generate_invoice', encrypt($row->id)),
                        'id'             => encrypt($row->id),
                        'current_status' => $row->status,
                    ])->render();
                })
                ->rawColumns(['ticket_number', 'client_info', 'priority', 'status', 'actions'])
                ->make(true);
        }
    }

    public function generateInvoice(Request $request, string $id)
    {
        try {
            $ticket = Ticket::with(['user', 'department'])->findOrFail(decrypt($id));
            $tasks = Task::query()->where('ticket_id', $ticket->id)->get();
            $ticket->setRelation('tasks', $tasks);

            $fileName = 'exports/invoice_' . $ticket->ticket_number . '_' . time() . '.pdf';

            // Dispatch the job to the queue
            \App\Jobs\GenerateTicketInvoice::dispatch($ticket, $fileName, Auth::id());

            return response()->json([
                'success' => true,
                'message' => 'Invoice generation is processing...',
                'filename' => $fileName,
                'status' => 'processing'
            ]);
        } catch (\Exception $e) {
            Log::error('Invoice Generation Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to generate invoice.'
            ]);
        }
    }

    public function generateCustomInvoice(Request $request, string $id)
    {
        $request->validate([
            'invoice_subject' => 'required|string',
            'invoice_amount'  => 'required|numeric|min:1',
        ]);

        try {
            $ticket = Ticket::with(['user', 'department'])->findOrFail(decrypt($id));

            $subject = $request->invoice_subject;
            $amount = $request->invoice_amount;

            $fileName = 'custom_invoice_' . $ticket->ticket_number . '_' . time() . '.pdf';

            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.ticket.custom_invoice', compact('ticket', 'subject', 'amount'));

            return $pdf->download($fileName);
        } catch (\Exception $e) {
            Log::error('Custom Invoice Generation Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return back()->with('error', 'Failed to generate custom invoice.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
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

        $departments = Department::query()->where('status', 1)->get();
        $roles = Role::query()->where('status', 1)->where('id', '!=', 1)->get();

        $products  = DigitalProduct::query()->where('status', 1)->get();

        $services  = DigitalService::with('variants')->where('status', 1)->get();

        $tab = $request->input('tab', 'ticket-form');

        $priority = '';

        return view('admin.ticket.form', compact('tab', 'priority', 'services', 'products', 'developers', 'users', 'departments', 'cc_recipients', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'user_id'          => 'required|integer|exists:users,id',
            'cc_recipients'    => 'nullable|array',
            'cc_recipients.*'  => 'email', // CC માં રહેલી વેલ્યુ ઈમેલ હોવી જોઈએ

            'ticket_notice'    => 'nullable|string',
            'ticket_source'    => 'required|string|in:phone,email,other',
            'help_topic'       => 'required|string',
            'department_id'    => 'nullable|integer', // જો ફરજીયાત હોય તો required કરો
            'sla_plan'         => 'nullable|string',
            'due_date'         => 'nullable|date',
            'assign_id'        => 'nullable|integer',
            'canned_response'  => 'nullable|string',

            'description'      => 'nullable|string',
            'ticket_status'    => 'nullable|string|in:open,resolved,closed',
            'signature_option' => 'nullable|string',
            'internal_note'    => 'nullable|string',

            // જો ભવિષ્યમાં ફાઈલ અપલોડ ચાલુ કરો તો આ કોમેન્ટ હટાવી દેજો:
            // 'attachments'   => 'nullable|array|max:10',
            // 'attachments.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx|max:10240',
        ]);

        try {
            $user = User::find($request->user_id);
            $name = $user?->name ?? null;
            $email = $user?->email ?? null;

            $ticket = Ticket::create([
                'ticket_number'    => 'TCK-' . strtoupper(Str::random(6)),
                'datetime'         => now(),
                'user_id'          => $request->user_id,
                'name'             => $name,
                'email'            => $email,
                'body'            => $request->body,
                'cc_recipients'    => $request->cc_recipients ? json_encode($request->cc_recipients) : null,

                'ticket_notice'    => $request->ticket_notice,
                'ticket_source'    => $request->ticket_source,
                'help_topic'       => $request->help_topic,
                'department_id'    => $request->department_id,
                'sla_plan'         => $request->sla_plan,
                'due_date'         => $request->due_date,
                'assign_id'        => $request->assign_id,
                'canned_response'  => $request->canned_response,

                'signature_option' => $request->signature_option,

                'status'           => 'pending',
                'ticket_status'    => $request->ticket_status ?? 'open',
            ]);

            if ($request->description) {
                Note::create([
                    'ref_id'   => $ticket->id,
                    'ref_type' => 'description',
                    'datetime' => now(),
                    'text'     => $request->description,
                    'user_id'  => Auth::id(),
                ]);
            }

            if ($request->internal_note) {
                Note::create([
                    'ref_id'   => $ticket->id,
                    'ref_type' => 'internal_note',
                    'datetime' => now(),
                    'text'     => $request->internal_note,
                    'user_id'  => Auth::id(),
                ]);
            }

            // if ($request->hasFile('attachments')) {
            //     $attachmentPaths = [];

            //     foreach ($request->file('attachments') as $file) {
            //         $path = $file->store('tickets', 'public');
            //         $fileUrl = Storage::url($path);

            //         TicketAttachment::create([
            //             'ticket_id' => $ticket->id,
            //             'file_path' => $fileUrl,
            //         ]);

            //         $attachmentPaths[] = $fileUrl;
            //     }

            //     $ticket->update(['attachments' => json_encode($attachmentPaths)]);
            // }

            // if ($ticket->id) {
            //     $productTypes = $request->input('product_type', []);
            //     $productIds   = $request->input('product_id', []);
            //     $variantIds   = $request->input('variant_id', []);
            //     $quantities   = $request->input('quantity', []);
            //     $prices       = $request->input('price', []);
            //     $duedate      = $request->input('due_date', []);

            //     if (!empty($productTypes) && is_array($productTypes)) {

            //         foreach ($productTypes as $i => $type) {

            //             if (!empty($productIds[$i])) {
            //                 Task::create([
            //                     'ticket_id'    => $ticket->id,
            //                     'product_type' => $type,
            //                     'product_id'   => $productIds[$i],
            //                     'due_date'     => $duedate[$i],
            //                     'variant_id'   => !empty($variantIds[$i]) ? $variantIds[$i] : null,
            //                     'quantity'     => $quantities[$i] ?? 1,
            //                     'price'        => $prices[$i] ?? 0.00,
            //                 ]);
            //             }
            //         }
            //     }
            // }

            // return redirect()->route('admin.tickets.edit', [ 'ticket' => encrypt($ticket->id) ])->with('success', 'Ticket created successfully.');
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
    public function show(Request $request, string $id)
    {
        view()->share('action', 'View');

        $ticket = Ticket::with(['descriptionNote', 'internalNoteRelation'])->find(decrypt($id));

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
                ->whereHas('roles', function($sq) {
                    $sq->whereNotIn('role_id', [User::IS_ADMIN]);
                })
                ->get();

        $departments = Department::query()->where('status', 1)->get();
        $roles = Role::query()->where('status', 1)->where('id', '!=', 1)->get();

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

        $tasks = Task::query()->with('assign')->where('ticket_id', decrypt($id))->get();

        $tab = $request->input('tab', 'ticket-form');

        $priority = '';

        return view('admin.ticket.show', compact('tab', 'priority', 'tasks', 'products', 'services', 'developers', 'users', 'ticket', 'cc_recipients', 'departments', 'roles'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, string $id)
    {
        view()->share('action', 'Open');

        $tab = $request->input('tab', 'ticket-post-raplay');

        $ticket = Ticket::with([
            'assign:id,name',
            'department:id,name',
            'descriptionNote',
            'internalNoteRelation',
            'notes' => function($q) {
                $q->with('user')->orderBy('datetime', 'asc');
            }
        ])->find(decrypt($id));
        // echo '<pre>';
        // print_r($ticket->toArray());
        // echo '</pre>';
        // exit;
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
                ->whereHas('roles', function($sq) {
                    $sq->whereNotIn('role_id', [User::IS_ADMIN]);
                })
                ->get();

        $departments = Department::query()->where('status', 1)->get();
        $roles = Role::query()->where('status', 1)->where('id', '!=', 1)->get();

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

        $tasks = Task::query()->with('assign')->where('ticket_id', decrypt($id))->get();

        $chats = Chat::query()->where('ticket_id', decrypt($id))->get();

        $isAdmin = $this->authUser?->role?->id === User::SUPERADMIN_ROLE_ID;

        $customfields = CustomField::with(['fieldType'])
                        ->where('module_type', 'department')
                        ->when(!$isAdmin, function($q) use ($ticket) {
                            $q->where('recode_id', $ticket->department_id)
                            ->where('params->only_admin', 0);
                        })
                        ->when($isAdmin, function($q) use ($ticket) {
                            $q->where('recode_id', $ticket->department_id);
                        })
                        ->orderBy('sort_order', 'ASC')
                        ->get();

        return view('admin.ticket.edit', compact('tab', 'tasks', 'products', 'services', 'developers', 'users', 'ticket', 'cc_recipients', 'departments', 'roles', 'customfields'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $validatedData = $request->validate([
            'user_id'              => 'required|integer|exists:users,id',
            'cc_recipients'        => 'nullable|array',
            'cc_recipients.*'      => 'email',
            'ticket_notice'        => 'nullable|string',
            'ticket_source'        => 'required|string|in:phone,email,other',
            'help_topic'           => 'required|string',
            'department_id'        => 'nullable|integer',
            'sla_plan'             => 'nullable|string',
            'due_date'             => 'nullable|date',
            'assign_id'            => 'nullable|integer',
            'priority'             => 'required|string|in:High,Medium,Low',
            'canned_response'      => 'nullable|string',
            'description'          => 'nullable|string',
            'ticket_status'        => 'nullable|string|in:open,resolved,closed',
            'signature_option'     => 'nullable|string',
            'internal_note'        => 'nullable|string',

            'name'                 => 'nullable|string|max:255',
            'email'                => 'nullable|email|max:255',
            'subject'              => 'nullable|string|max:255',
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

            $user = User::find($request->user_id);
            $name = $request->name ?? ($user?->name ?? null);
            $email = $request->email ?? ($user?->email ?? null);

            if (in_array($request->ticket_status, ['closed', 'resolved'])) {
                $incompleteTasks = \App\Models\Task::where('ticket_id', $ticket->id)->where('status', '!=', 'completed')->count();
                if ($incompleteTasks > 0) {
                    return redirect()->back()->withInput()->with('error', 'Cannot change ticket status to closed/resolved. All tasks must be completed first.');
                }
            }

            $oldTicketStatus = $ticket->ticket_status;

            $ticket->update([
                'user_id'          => $request->user_id,
                'name'             => $name,
                'email'            => $email,
                'body'            => $request->body,
                'cc_recipients'    => $request->cc_recipients ? json_encode($request->cc_recipients) : null,
                'subject'          => $request->subject ?? $ticket->subject,
                'ticket_notice'    => $request->ticket_notice,
                'ticket_source'    => $request->ticket_source,
                'help_topic'       => $request->help_topic,
                'department_id'    => $request->department_id,
                'sla_plan'         => $request->sla_plan,
                'due_date'         => $request->due_date,
                'assign_id'        => $request->assign_id,
                'priority'         => $request->priority,
                'canned_response'  => $request->canned_response,
                'ticket_status'    => $request->ticket_status ?? 'open',
                'signature_option' => $request->signature_option,

                'attachments'      => !empty($allActiveFiles) ? json_encode(array_values($allActiveFiles)) : null
            ]);

            // Save/Update description in notes table
            Note::updateOrCreate(
                ['ref_id' => $ticket->id, 'ref_type' => 'description'],
                [
                    'datetime' => now(),
                    'text'     => $request->description,
                    'user_id'  => Auth::id(),
                ]
            );

            if ($oldTicketStatus != $request->ticket_status && $request->ticket_status) {
                Note::create([
                    'ref_id' => $ticket->id,
                    'ref_type' => 'internal_note',
                    'title' => 'Status Updated',
                    'datetime' => now(),
                    'text' => 'Ticket status changed from ' . ucfirst(str_replace('_', ' ', $oldTicketStatus)) . ' to ' . ucfirst(str_replace('_', ' ', $request->ticket_status)),
                    'user_id' => Auth::id(),
                ]);
            }

            // Save/Update internal_note in notes table
            if ($request->internal_note) {
                Note::updateOrCreate(
                    ['ref_id' => $ticket->id, 'ref_type' => 'internal_note'],
                    [
                        'datetime' => now(),
                        'text'     => $request->internal_note,
                        'user_id'  => Auth::id(),
                    ]
                );
            } else {
                Note::where('ref_id', $ticket->id)->where('ref_type', 'internal_note')->delete();
            }

            $taskIds      = $request->input('task_id', []);
            $productTypes = $request->input('product_type', []);
            $productIds   = $request->input('product_id', []);
            $variantIds   = $request->input('variant_id', []);
            $quantities   = $request->input('quantity', []);
            $prices       = $request->input('price', []);
            $duedate       = $request->input('due_date', []);

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
                            'due_date'     => $duedate[$i] ?? null,
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

            return redirect()->back()->with('success', 'Ticket updated successfully.');
            // return redirect()->route($this->moduleUrl ?? 'admin.tickets.index')->with('success', 'Ticket updated successfully.');

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
            $ticket = Ticket::withTrashed()->findOrFail(decrypt($request->id));

            if ($request->status == 'assigned' && $request->assign_id) {
                $ticket->assign_id = $request->assign_id;
            }

            if (in_array($request->status, ['completed', 'cancel_requested', 'cancelled', 'refund'])) {
                $incompleteTasks = \App\Models\Task::where('ticket_id', $ticket->id)->where('status', '!=', 'completed')->count();
                if ($incompleteTasks > 0) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cannot change status to completed/cancelled. All tasks must be completed first.'
                    ]);
                }
            }

            $oldStatus = $ticket->status;
            $ticket->status = $request->status;
            $ticket->update();

            if ($oldStatus != $ticket->status) {
                Note::create([
                    'ref_id' => $ticket->id,
                    'ref_type' => 'internal_note',
                    'title' => 'Status Updated',
                    'datetime' => now(),
                    'text' => 'Ticket status changed from ' . ucfirst(str_replace('_', ' ', $oldStatus)) . ' to ' . ucfirst(str_replace('_', ' ', $ticket->status)),
                    'user_id' => Auth::id(),
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Ticket status updated successfully!',
                'data' => $ticket
            ]);
        } catch (\Exception $e) {
            Log::error('Ticket Status Update Error', [
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
                'status' => 'error',
                'message' => 'Unauthorized action.'
            ], 403);
        }
    }

    /**
     * Store a new user created via AJAX popup.
     */
    public function storeUser(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role_id' => 'required|exists:roles,id',
            'department_id' => 'nullable|exists:departments,id',
        ]);

        try {
            $validatedData['password'] = Hash::make('12345678');
            $validatedData['status'] = 1;
            $validatedData['is_user'] = 1;

            $roleId = $validatedData['role_id'];
            unset($validatedData['role_id']);
            unset($validatedData['department_id']);

            $user = User::create($validatedData);

            if ($user && !empty($roleId)) {
                UserRole::create([
                    'user_id' => $user->id,
                    'role_id' => $roleId,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'User created successfully.',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('AJAX User Store Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create user: ' . $e->getMessage()
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

            if (in_array($request->status, ['completed', 'cancel_requested', 'cancelled', 'refund'])) {
                $incompleteTasks = \App\Models\Task::where('ticket_id', $ticket->id)->where('status', '!=', 'completed')->count();
                if ($incompleteTasks > 0) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cannot change status to completed/cancelled. All tasks must be completed first.'
                    ]);
                }
            }

            $oldStatus = $ticket->status;
            $ticket->status = $request->status;
            $ticket->update();

            if ($oldStatus != $ticket->status) {
                Note::create([
                    'ref_id' => $ticket->id,
                    'ref_type' => 'internal_note',
                    'title' => 'Status Updated',
                    'datetime' => now(),
                    'text' => 'Ticket status changed from ' . ucfirst(str_replace('_', ' ', $oldStatus)) . ' to ' . ucfirst(str_replace('_', ' ', $ticket->status)),
                    'user_id' => Auth::id(),
                ]);
            }

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

    public function storeTask(Request $request)
    {

        $tab = $request->input('tab', 'ticket-form');
        $ticket_id = $request->input('ticket_id', '');
        try {
            DB::beginTransaction();

            if (!$ticket_id) {
                $ticket = Ticket::create([
                    'ticket_number' => 'TCK-' . strtoupper(Str::random(6)),
                    'datetime'      => now(),
                    'user_id'       => null,
                    'name'          => null,
                    'email'         => null,
                    'cc_recipients' => null,
                    'subject'       => 'Create Tasks.',
                    'department_id' => null,
                    'assign_id'     => null,
                    'priority'      => 'Low',
                    'description'   => null,
                    'note'          => null,
                    'status'        => 'pending',
                ]);
                $ticket_id = $ticket->id;
            }

            if ($ticket_id) {
                $taskIds      = $request->input('task_id', []);
                $productTypes = $request->input('product_type', []);
                $productIds   = $request->input('product_id', []);
                $variantIds   = $request->input('variant_id', []);
                $quantities   = $request->input('quantity', []);
                $prices       = $request->input('price', []);
                $duedate      = $request->input('due_date', []);
                $departmentId = $request->input('department_id', []);
                $assignId     = $request->input('assign_id', []);

                $processedTaskIds = [];

                if (!empty($productTypes) && is_array($productTypes)) {
                    foreach ($productTypes as $i => $type) {
                        if (!empty($productIds[$i])) {

                            $taskId = !empty($taskIds[$i]) ? $taskIds[$i] : null;

                            $taskData = [
                                'ticket_id'     => $ticket_id,
                                'product_type'  => $type,
                                'product_id'    => $productIds[$i],
                                'variant_id'    => !empty($variantIds[$i]) ? $variantIds[$i] : null,
                                'due_date'      => $duedate[$i] ?? null,
                                'quantity'      => $quantities[$i] ?? 1,
                                'price'         => $prices[$i] ?? 0.00,
                                'department_id' => $departmentId[$i] ?? null,
                                'assign_id'     => $assignId[$i] ?? null,
                            ];

                            if ($taskId) {
                                Task::query()
                                    ->where('id', $taskId)
                                    ->where('ticket_id', $ticket_id)
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
                        ->where('ticket_id', $ticket_id)
                        ->whereNotIn('id', $processedTaskIds)
                        ->delete();
                } else {
                    Task::query()->where('ticket_id', $ticket_id)->delete();
                }
            }

            DB::commit();

            return redirect()->route('admin.tickets.edit', [
                'ticket' => encrypt($ticket_id),
                'tab' => $tab,
            ])->with('success', 'Ticket and tasks created successfully.');

        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Error creating Task Ticket: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                            ->withInput()
                            ->with('error', 'An error occurred while saving the tasks. Please try again.');
        }
    }

    public function storeChat(Request $request)
    {

        $request->validate([
            'ticket_id' => 'required|exists:tickets,id',
            'text' => 'required_without:attachment|nullable|string',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,zip|max:5120'
        ]);

        $chat = new Chat();
        $chat->ticket_id = $request->ticket_id;
        $chat->user_id = Auth::id();
        $chat->task_id = $request->task_id;
        $chat->text = $request->text;
        $chat->sent_at = now();
        $chat->is_edited = false;

        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('chat_attachments', 'public');
            $chat->attachment = $path;
        }

        $chat->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Message sent successfully!',
            'data' => $chat
        ]);
    }

    public function getChats(Request $request)
    {
        $request->validate([
            'ticket_id' => 'required|exists:tickets,id'
        ]);

        $chats = Chat::with('user')
                    ->where('ticket_id', $request->ticket_id)
                    ->orderBy('created_at', 'asc')
                    ->get();

        $html = view('admin.ticket.parts.list-chat', compact('chats'))->render();

        return response()->json([
            'status' => 'success',
            'html' => $html
        ]);
    }

    public function deleteChat(Request $request)
    {
        $request->validate([
            'chat_id' => 'required|exists:chats,id'
        ]);

        $chat = Chat::findOrFail($request->chat_id);

        if ($chat->user_id == auth()->id()) {
            $chat->delete();
            return response()->json(['status' => 'success', 'message' => 'Message deleted successfully!']);
        }

        return response()->json(['status' => 'error', 'message' => 'Unauthorized action.'], 403);
    }

    public function updateChatMessage(Request $request)
    {
        $request->validate([
            'chat_id' => 'required|exists:chats,id',
            'text' => 'required|string',
        ]);

        $chat = Chat::findOrFail($request->chat_id);

        // Auth check jethi koi biju user aa message edit na kari shake
        if ($chat->user_id == auth()->id()) {

            // Juna message ni history preserve karo
            $history = $chat->edit_history ?? [];
            $history[] = [
                'old_text' => $chat->text,
                'edited_at' => now()->toDateTimeString(),
            ];

            // Quill editor text ne <p> tag ma wrap kare chhe, aathi ahi pan <p> lagavvu joiye jethi UI match thay
            $newFormattedText = '<p>' . $request->text . '</p>';

            $chat->update([
                'text' => $newFormattedText,
                'is_edited' => true,
                'edit_history' => $history
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Message updated successfully!'
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Unauthorized action.'
        ], 403);
    }

    /**
     * Store a reply (public note) for the ticket.
     */
    public function storeReply(Request $request, string $id)
    {
        $ticketId = decrypt($id);
        $ticket = Ticket::findOrFail($ticketId);

        $request->validate([
            'description' => 'nullable|string',
            'ticket_status' => 'nullable|string|in:open,resolved,closed',
        ]);

        try {
            if (!empty($request->description)) {
                Note::create([
                    'ref_id' => $ticket->id,
                    'ref_type' => 'reply',
                    'datetime' => now(),
                    'text' => $request->description,
                    'user_id' => Auth::id(),
                ]);
            }

            if ($request->ticket_status) {
                if (in_array($request->ticket_status, ['closed', 'resolved'])) {
                    $incompleteTasks = \App\Models\Task::where('ticket_id', $ticket->id)->where('status', '!=', 'completed')->count();
                    if ($incompleteTasks > 0) {
                        return redirect()->back()->withInput()->with('error', 'Reply posted, but cannot close ticket. All tasks must be completed first.');
                    }
                }
                $oldStatus = $ticket->ticket_status;
                $ticket->update(['ticket_status' => $request->ticket_status]);

                if ($oldStatus != $request->ticket_status) {
                    Note::create([
                        'ref_id' => $ticket->id,
                        'ref_type' => 'reply',
                        'title' => 'Status Updated',
                        'datetime' => now(),
                        'text' => 'Ticket status changed from ' . ucfirst(str_replace('_', ' ', $oldStatus)) . ' to ' . ucfirst(str_replace('_', ' ', $request->ticket_status)),
                        'user_id' => Auth::id(),
                    ]);
                }
            }

            return redirect()->back()->with('success', 'Reply posted successfully.');
        } catch (\Exception $e) {
            Log::error('Store Reply Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return redirect()->back()->with('error', 'Failed to post reply. Please try again.');
        }
    }

    /**
     * Store an internal note for the ticket.
     */
    public function storeInternalNote(Request $request, string $id)
    {
        $ticketId = decrypt($id);
        $ticket = Ticket::findOrFail($ticketId);

        $request->validate([
            'internal_note' => 'nullable|string',
            'ticket_status' => 'nullable|string|in:open,resolved,closed',
        ]);

        try {
            if (!empty($request->internal_note)) {
                Note::create([
                    'ref_id' => $ticket->id,
                    'ref_type' => 'internal_note',
                    'title' => $request->internal_note_title,
                    'datetime' => now(),
                    'text' => $request->internal_note,
                    'user_id' => Auth::id(),
                ]);
            }

            if ($request->ticket_status) {
                if (in_array($request->ticket_status, ['closed', 'resolved'])) {
                    $incompleteTasks = \App\Models\Task::where('ticket_id', $ticket->id)->where('status', '!=', 'completed')->count();
                    if ($incompleteTasks > 0) {
                        return redirect()->back()->withInput()->with('error', 'Note posted, but cannot close ticket. All tasks must be completed first.');
                    }
                }
                $oldStatus = $ticket->ticket_status;
                $ticket->update(['ticket_status' => $request->ticket_status]);

                if ($oldStatus != $request->ticket_status) {
                    Note::create([
                        'ref_id' => $ticket->id,
                        'ref_type' => 'internal_note',
                        'title' => 'Status Updated',
                        'datetime' => now(),
                        'text' => 'Ticket status changed from ' . ucfirst(str_replace('_', ' ', $oldStatus)) . ' to ' . ucfirst(str_replace('_', ' ', $request->ticket_status)),
                        'user_id' => Auth::id(),
                    ]);
                }
            }

            return redirect()->back()->with('success', 'Internal note posted successfully.');
        } catch (\Exception $e) {
            \Log::error('Store Internal Note Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return redirect()->back()->with('error', 'Failed to post internal note. Please try again.');
        }
    }

    public function createTask(Request $request, string $id)
    {
        $ticketId = decrypt($id);
        $ticket = Ticket::findOrFail($ticketId);

        $departments = \App\Models\Department::query()->where('status', 1)->get();
        $products  = \App\Models\DigitalProduct::query()->where('status', 1)->get();
        $services  = \App\Models\DigitalService::with('variants')->where('status', 1)->get();
        $developers = \App\Models\User::query()
                ->with(['roles','designation'])
                ->where('status', 1)
                ->whereHas('roles', function($sq) {
                    $sq->whereIn('role_id', [\App\Models\User::IS_DEVELOPER])
                    ->whereNotIn('role_id', [\App\Models\User::IS_ADMIN]);
                })
                ->get();

        $task_number = 'TSK-' . strtoupper(\Illuminate\Support\Str::random(6));
        $action = 'Create';

        return view('admin.ticket.tasks.form', compact('ticket', 'departments', 'products', 'services', 'developers', 'task_number', 'action'));
    }

    public function storeTicketTask(Request $request, string $id)
    {
        $ticketId = decrypt($id);
        $ticket = Ticket::findOrFail($ticketId);

        $validatedData = $request->validate([
            'task_number'   => 'required',
            'title'         => 'required|string|max:255',
            'product_type'  => 'required',
            'description'   => 'required',
            'status'        => 'required',
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
            $products = \App\Models\DigitalProduct::query()
                        ->where('id', $product_id)
                        ->where('status', 1)
                        ->first();
            $product_name = $products?->name ?? '';
            $product_price = $products?->price ?? 0;
        } else if ($product_type == 'service') {
            $services = \App\Models\DigitalService::query()
                        ->where('id', $service_id)
                        ->with(['variants' => function ($sq) use ($is_variant, $service_variant) {
                            if ($is_variant && $service_variant) {
                                $sq->where('id', $service_variant);
                            }
                        }])
                        ->where('status', 1)
                        ->first();
            $product_name = $services?->name ?? '';
            $product_price = $services?->price ?? 0;

            if ($services && !empty($services->variants[0])) {
                $variant_id = $services->variants[0]->id;
                $variant_name = $services->variants[0]->name;
                $product_price = $services->variants[0]->price;
            }
        }

        try {
            \App\Models\Task::create([
                'title'          => $request->title,
                'task_number'    => $request->task_number,
                'ticket_id'      => $ticket->id,
                'department_id'  => $request->department_id,
                'user_id'        => $ticket->user_id,
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

            return redirect()->route('admin.tickets.edit', ['ticket' => encrypt($ticket->id), 'tab' => 'task-form'])->with('success', 'Task created successfully.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Task Store Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'request' => $request->all(),
            ]);

            return redirect()->back()->withInput()->with('error', 'Failed to create Task. Please try again later.');
        }
    }

    public function editTask(Request $request, string $id, string $taskId)
    {
        $ticketId = decrypt($id);
        $ticket = Ticket::findOrFail($ticketId);

        $decryptedTaskId = decrypt($taskId);
        $task = \App\Models\Task::findOrFail($decryptedTaskId);

        $departments = \App\Models\Department::query()->where('status', 1)->get();
        $products  = \App\Models\DigitalProduct::query()->where('status', 1)->get();
        $services  = \App\Models\DigitalService::with('variants')->where('status', 1)->get();
        $developers = \App\Models\User::query()
                ->with(['roles','designation'])
                ->where('status', 1)
                ->whereHas('roles', function($sq) {
                    $sq->whereIn('role_id', [\App\Models\User::IS_DEVELOPER])
                    ->whereNotIn('role_id', [\App\Models\User::IS_ADMIN]);
                })
                ->get();

        $action = 'Edit';

        return view('admin.ticket.tasks.form', compact('ticket', 'task', 'departments', 'products', 'services', 'developers', 'action'));
    }

    public function updateTask(Request $request, string $id, string $taskId)
    {
        $ticketId = decrypt($id);
        $ticket = Ticket::findOrFail($ticketId);

        $decryptedTaskId = decrypt($taskId);
        $task = \App\Models\Task::findOrFail($decryptedTaskId);

        $validatedData = $request->validate([
            'task_number'   => 'required',
            'title'         => 'required|string|max:255',
            'product_type'  => 'required',
            'description'   => 'required',
            'status'        => 'required',
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
            $products = \App\Models\DigitalProduct::query()
                        ->where('id', $product_id)
                        ->where('status', 1)
                        ->first();
            $product_name = $products?->name ?? '';
            $product_price = $products?->price ?? 0;
        } else if ($product_type == 'service') {
            $services = \App\Models\DigitalService::query()
                        ->where('id', $service_id)
                        ->with(['variants' => function ($sq) use ($is_variant, $service_variant) {
                            if ($is_variant && $service_variant) {
                                $sq->where('id', $service_variant);
                            }
                        }])
                        ->where('status', 1)
                        ->first();
            $product_name = $services?->name ?? '';
            $product_price = $services?->price ?? 0;

            if ($services && !empty($services->variants[0])) {
                $variant_id = $services->variants[0]->id;
                $variant_name = $services->variants[0]->name;
                $product_price = $services->variants[0]->price;
            }
        }

        try {
            $oldStatus = $task->status;
            $task->update([
                'title'          => $request->title,
                'task_number'    => $request->task_number,
                'ticket_id'      => $ticket->id,
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

            if ($oldStatus != $request->status && $request->status) {
                \App\Models\Note::create([
                    'task_id' => $task->id,
                    'ref_type' => 'internal_note',
                    'title' => 'Status Updated',
                    'datetime' => now(),
                    'text' => 'Task status changed from ' . ucfirst(str_replace('_', ' ', $oldStatus)) . ' to ' . ucfirst(str_replace('_', ' ', $request->status)),
                    'user_id' => Auth::id(),
                ]);
            }

            return redirect()->route('admin.tickets.edit', ['ticket' => encrypt($ticket->id), 'tab' => 'task-form'])->with('success', 'Task updated successfully.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Task Update Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'request' => $request->all(),
            ]);

            return redirect()->back()->withInput()->with('error', 'Failed to update Task. Please try again later.');
        }
    }

    public function deleteTask(Request $request, string $id, string $taskId)
    {
        try {
            $ticketId = decrypt($id);
            Ticket::findOrFail($ticketId); // verify ticket

            $decryptedTaskId = decrypt($taskId);
            $task = \App\Models\Task::findOrFail($decryptedTaskId);

            $task->delete();

            return redirect()->route('admin.tickets.edit', ['ticket' => encrypt($ticketId), 'tab' => 'task-form'])->with('success', 'Task deleted successfully.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Task Delete Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return redirect()->back()->with('error', 'Failed to delete Task. Please try again later.');
        }
    }

    public function fetchEmails(Request $request)
    {
        // echo "<pre/>";
        // print_r(config('imap.accounts.default'));
        // exit;
        try {
            $departments = \App\Models\Department::whereNotNull('email_id')->with('emailAccount')->get();
            $totalFetched = 0;

            foreach ($departments as $department) {
                $emailAccount = $department->emailAccount;
                if ($emailAccount && $emailAccount->status == 1) {
                    $count = $this->fetchFromCustomAccount($emailAccount, $department->id);
                    $totalFetched += $count;
                }
            }

            if ($totalFetched === 0) {
                $totalFetched = $this->fetchFromDefaultConfig();
            }

            if ($totalFetched === 0) {
                return response()->json([
                    'success' => true,
                    'message' => 'No emails found to fetch.'
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => "Successfully fetched {$totalFetched} email(s) and generated tickets."
            ]);

        } catch (\Exception $e) {
            Log::error('FetchEmails AJAX Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching emails: ' . $e->getMessage()
            ], 500);
        } finally {
            if (function_exists('imap_errors')) {
                imap_errors();
            }
        }
    }

    protected function fetchFromCustomAccount($emailAccount, $departmentId)
    {
        try {
            \App\Helpers\Helper::setDynamicImapConfig($emailAccount, 'default');
            
            $client = \Webklex\IMAP\Facades\Client::account('default');
            $client->connect();
            
            return $this->processClientMessages($client, $emailAccount->protocol, $departmentId);
        } catch (\Exception $e) {
            Log::error("FetchEmails Custom Account Error ({$emailAccount->email}): " . $e->getMessage());
            return 0;
        }
    }

    protected function fetchFromDefaultConfig()
    {
        $host = config('imap.accounts.default.host');
        if (empty($host)) {
            return 0;
        }

        try {
            $client = \Webklex\IMAP\Facades\Client::account('default');
            $client->connect();
            $protocol = config('imap.accounts.default.protocol');
            
            return $this->processClientMessages($client, $protocol, null);
        } catch (\Exception $e) {
            Log::error('FetchEmails Default Account Error: ' . $e->getMessage());
            return 0;
        }
    }

    protected function processClientMessages($client, $protocol, $departmentId = null)
    {
        $folder = $client->getFolder('INBOX');

        if ($protocol === 'pop3') {
            $messages = $folder->query()->all()->get();
        } else {
            $messages = $folder->query()->unseen()->get();
        }

        $count = $messages->count();
        
        if ($count === 0) {
            return 0;
        }

        $fetchedCount = 0;
        foreach ($messages as $message) {
            $subject = $message->getSubject();
            
            $fromAddresses = $message->getFrom();
            $from = $fromAddresses->count() > 0 ? $fromAddresses[0]->mail : 'Unknown Sender';
            
            $body = $message->getTextBody();
            if (empty($body)) {
                $body = $message->getHTMLBody();
            }
            
            $messageId = $message->getMessageId();
            $date = $message->getDate();

            // Log::info('--- New Unread Email Fetched via AJAX ---', [
            //     'message_id' => $messageId,
            //     'from'       => $from,
            //     'subject'    => $subject,
            //     'date'       => $date,
            //     'body'       => \Illuminate\Support\Str::limit($body, 1000) 
            // ]);

            $user = \App\Models\User::where('email', $from)->first();
            
            if (!$user) {
                $newName = ($fromAddresses->count() > 0 && !empty($fromAddresses[0]->personal)) ? $fromAddresses[0]->personal : 'Unknown Sender';
                
                $user = \App\Models\User::create([
                    'name'     => $newName,
                    'email'    => $from,
                    'password' => \Illuminate\Support\Facades\Hash::make(\Illuminate\Support\Str::random(10)),
                    'status'   => '1',
                ]);

                if ($user) {
                    \App\Models\UserRole::create([
                        'user_id' => $user->id,
                        'role_id' => \App\Models\User::IS_BUYER,
                    ]);
                }
            }

            $userId = $user ? $user->id : null;
            $name = $user ? $user->name : 'Unknown Sender';

            $ticketData = [
                'ticket_number'    => 'TCK-' . strtoupper(\Illuminate\Support\Str::random(6)),
                'datetime'         => now(),
                'user_id'          => $userId,
                'name'             => $name,
                'email'            => $from,
                'subject'          => $subject,
                'body'             => $body,
                'ticket_source'    => 'email',
                'status'           => 'pending',
                'ticket_status'    => 'open',
                'priority'         => 'Low'
            ];

            if ($departmentId) {
                $ticketData['department_id'] = $departmentId;
            }

            \App\Models\Ticket::create($ticketData);

            $message->delete();
            $fetchedCount++;
        }

        return $fetchedCount;
    }
}
