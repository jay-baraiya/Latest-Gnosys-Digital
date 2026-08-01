<?php

namespace App\Http\Controllers;

use App\Models\EventWaitlist;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;

class EventWaitlistController extends Controller
{
    protected $moduleName = 'Event Waitlist';
    protected $moduleUrl = 'admin.event_waitlists.index';

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

        $this->middleware('permission:create.event_waitlists')->only('create', 'store');
        $this->middleware('permission:edit.event_waitlists')->only('edit', 'update');
        $this->middleware('permission:delete.event_waitlists')->only('destroy');
        $this->middleware('permission:view.event_waitlists')->only('index', 'show');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return view('admin.event_waitlists.index');
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {

            $data = EventWaitlist::with(['event']);

            return DataTables::eloquent($data)
                ->with('total_records', $data->count())
                ->addIndexColumn()
                ->editColumn('event_id', function ($row) {
                    return $row->event ? $row->event->title : 'N/A';
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at ? \Carbon\Carbon::parse($row->created_at)->format('d M Y, h:i A') : 'N/A';
                })
                ->editColumn('notified_at', function ($row) {
                    return $row->notified_at ? \Carbon\Carbon::parse($row->notified_at)->format('d M Y, h:i A') : '<span class="badge bg-secondary">Not Notified</span>';
                })
                ->addColumn('actions', function ($row) use ($request) {
                    return view('admin.components.action-links', [
                        'edit' => route('admin.event_waitlists.edit', encrypt($row->id)),
                        'show' => route('admin.event_waitlists.show', encrypt($row->id)),
                        'delete' => route('admin.event_waitlists.destroy', encrypt($row->id)),
                        'id' => encrypt($row->id),
                    ])->render();
                })
                ->rawColumns(['notified_at', 'actions'])
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
        return view('admin.event_waitlists.form', compact('events'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'event_id' => 'required|exists:events,id',
            'email' => 'required|email|max:255',
            'notified_at' => 'nullable|date',
        ]);

        // Check unique constraint
        $exists = EventWaitlist::where('event_id', $validatedData['event_id'])
            ->where('email', $validatedData['email'])
            ->exists();

        if ($exists) {
            return redirect()->back()->withInput()->with('error', 'This email is already on the waitlist for the selected event.');
        }

        try {
            EventWaitlist::create($validatedData);

            return redirect()->route($this->moduleUrl)->with('success', 'Added to Waitlist successfully.');
        } catch (\Exception $e) {
            Log::error('Event Waitlist Store Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'request' => $request->all(),
            ]);

            return redirect()->back()->withInput()->with('error', 'Failed to add to Waitlist. Please try again later.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        view()->share('action', 'View');
        $eventWaitlist = EventWaitlist::with(['event'])->findOrFail(decrypt($id));
        return view('admin.event_waitlists.show', compact('eventWaitlist'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        view()->share('action', 'Edit');
        $eventWaitlist = EventWaitlist::findOrFail(decrypt($id));
        $events = Event::all();
        return view('admin.event_waitlists.form', compact('eventWaitlist', 'events'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $waitlistId = decrypt($id);
            $eventWaitlist = EventWaitlist::findOrFail($waitlistId);

            $validatedData = $request->validate([
                'event_id' => 'required|exists:events,id',
                'email' => 'required|email|max:255',
                'notified_at' => 'nullable|date',
            ]);

            // Check unique constraint excluding self
            $exists = EventWaitlist::where('event_id', $validatedData['event_id'])
                ->where('email', $validatedData['email'])
                ->where('id', '!=', $waitlistId)
                ->exists();

            if ($exists) {
                return redirect()->back()->withInput()->with('error', 'This email is already on the waitlist for the selected event.');
            }

            $eventWaitlist->update($validatedData);

            return redirect()->route($this->moduleUrl)->with('success', 'Waitlist entry updated successfully.');
        } catch (\Exception $e) {
            Log::error('Event Waitlist Update Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'request' => $request->all(),
            ]);

            return redirect()->back()->withInput()->with('error', 'Failed to update Waitlist entry. Please try again later.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $eventWaitlist = EventWaitlist::findOrFail(decrypt($id));
            $eventWaitlist->delete();

            return response()->json([
                'success' => true,
                'message' => 'Waitlist entry deleted successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error('Event Waitlist Destroy Error', [
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
