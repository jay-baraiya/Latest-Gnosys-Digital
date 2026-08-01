<?php

namespace App\Http\Controllers;

use App\Models\EventRegistrations;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;

class EventRegistrationsController extends Controller
{
    protected $moduleName = 'Event Registrations';
    protected $moduleUrl = 'admin.event_registrations.index';

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

        $this->middleware('permission:create.event_registrations')->only('create', 'store');
        $this->middleware('permission:edit.event_registrations')->only('edit', 'update');
        $this->middleware('permission:delete.event_registrations')->only('destroy');
        $this->middleware('permission:view.event_registrations')->only('index', 'show');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return view('admin.event_registrations.index');
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {

            $data = EventRegistrations::with(['event', 'user']);

            return DataTables::eloquent($data)
                ->with('total_records', $data->count())
                ->addIndexColumn()
                ->editColumn('event_id', function ($row) {
                    return $row->event ? $row->event->title : 'N/A';
                })
                ->editColumn('user_id', function ($row) {
                    return $row->user ? $row->user->name : 'N/A';
                })
                ->editColumn('payment_status', function ($row) {
                    $statusColors = [
                        'pending' => 'warning',
                        'paid' => 'success',
                        'failed' => 'danger',
                        'refunded' => 'info'
                    ];
                    $color = $statusColors[$row->payment_status] ?? 'secondary';
                    return '<span class="badge bg-' . $color . '">' . ucfirst($row->payment_status) . '</span>';
                })
                ->editColumn('attendee_status', function ($row) {
                    $statusColors = [
                        'registered' => 'primary',
                        'checked_in' => 'success',
                        'no_show' => 'warning',
                        'cancelled' => 'danger'
                    ];
                    $color = $statusColors[$row->attendee_status] ?? 'secondary';
                    return '<span class="badge bg-' . $color . '">' . ucfirst(str_replace('_', ' ', $row->attendee_status)) . '</span>';
                })
                ->addColumn('actions', function ($row) use ($request) {
                    return view('admin.components.action-links', [
                        'edit' => route('admin.event_registrations.edit', encrypt($row->id)),
                        'show' => route('admin.event_registrations.show', encrypt($row->id)),
                        'delete' => route('admin.event_registrations.destroy', encrypt($row->id)),
                        'id' => encrypt($row->id),
                    ])->render();
                })
                ->rawColumns(['payment_status', 'attendee_status', 'actions'])
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        view()->share('action', 'Create');
        $events = Event::where('status', 'published')->get();
        $users = User::all();
        return view('admin.event_registrations.form', compact('events', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'event_id' => 'nullable|exists:events,id',
            'user_id' => 'nullable|exists:users,id',
            'email' => 'required|email|max:255',
            'payment_status' => 'required|in:pending,paid,failed,refunded',
            'attendee_status' => 'required|in:registered,checked_in,no_show,cancelled',
        ]);

        try {
            EventRegistrations::create($validatedData);

            return redirect()->route($this->moduleUrl)->with('success', 'Event Registration created successfully.');
        } catch (\Exception $e) {
            Log::error('Event Registration Store Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'request' => $request->all(),
            ]);

            return redirect()->back()->withInput()->with('error', 'Failed to create Event Registration. Please try again later.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        view()->share('action', 'View');
        $eventRegistration = EventRegistrations::with(['event', 'user', 'order'])->findOrFail(decrypt($id));
        return view('admin.event_registrations.show', compact('eventRegistration'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        view()->share('action', 'Edit');
        $eventRegistration = EventRegistrations::findOrFail(decrypt($id));
        $events = Event::all();
        $users = User::all();
        return view('admin.event_registrations.form', compact('eventRegistration', 'events', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $registrationId = decrypt($id);
            $eventRegistration = EventRegistrations::findOrFail($registrationId);

            $validatedData = $request->validate([
                'event_id' => 'nullable|exists:events,id',
                'user_id' => 'nullable|exists:users,id',
                'email' => 'required|email|max:255',
                'payment_status' => 'required|in:pending,paid,failed,refunded',
                'attendee_status' => 'required|in:registered,checked_in,no_show,cancelled',
            ]);

            $eventRegistration->update($validatedData);

            return redirect()->route($this->moduleUrl)->with('success', 'Event Registration updated successfully.');
        } catch (\Exception $e) {
            Log::error('Event Registration Update Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'request' => $request->all(),
            ]);

            return redirect()->back()->withInput()->with('error', 'Failed to update Event Registration. Please try again later.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $eventRegistration = EventRegistrations::findOrFail(decrypt($id));
            $eventRegistration->delete();

            return response()->json([
                'success' => true,
                'message' => 'Event Registration deleted successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error('Event Registration Destroy Error', [
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
}
