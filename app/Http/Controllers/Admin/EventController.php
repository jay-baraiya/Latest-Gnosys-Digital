<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\EventSeries;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class EventController extends Controller
{
    protected $moduleName = 'Event';
    protected $moduleUrl = 'admin.event.index';

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

        $this->middleware('permission:create.event')->only('create', 'store');
        $this->middleware('permission:edit.event')->only('edit', 'update');
        $this->middleware('permission:delete.event')->only('destroy');
        $this->middleware('permission:view.event')->only('index', 'show');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return view('admin.event.index');
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {

            $data = Event::with('series');

            return DataTables::eloquent($data)
                ->with('total_records', $data->count())
                ->addIndexColumn()
                ->editColumn('start_date', function ($row) {
                    return \Carbon\Carbon::parse($row->start_date)->format('d M Y, h:i A');
                })
                ->editColumn('end_date', function ($row) {
                    return \Carbon\Carbon::parse($row->end_date)->format('d M Y, h:i A');
                })
                ->editColumn('event_type', function ($row) {
                    return ucfirst($row->event_type);
                })
                ->editColumn('event_mode', function ($row) {
                    return ucfirst($row->event_mode);
                })
                ->addColumn('pricing', function ($row) {
                    return $row->is_free ? '<span class="badge bg-success">Free</span>' : $row->currency . ' ' . $row->price;
                })
                ->editColumn('status', function ($row) {
                    $statusColors = [
                        'draft' => 'secondary',
                        'published' => 'success',
                        'ongoing' => 'primary',
                        'ended' => 'info',
                        'cancelled' => 'danger'
                    ];
                    $color = $statusColors[$row->status] ?? 'secondary';
                    return '<span class="badge badge-pill badge-status bg-' . $color . '">' . ucfirst($row->status) . '</span>';
                })
                ->addColumn('actions', function ($row) use ($request) {
                    return view('admin.components.action-links', [
                        'edit' => route('admin.event.edit', encrypt($row->id)),
                        'show' => route('admin.event.show', encrypt($row->id)),
                        'delete' => route('admin.event.destroy', encrypt($row->id)),
                        'id' => encrypt($row->id),
                    ])->render();
                })
                ->rawColumns(['status', 'pricing', 'actions'])
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        view()->share('action', 'Create');
        $series = EventSeries::where('status', 1)->get();
        return view('admin.event.form', compact('series'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'event_type' => 'required|in:single,series',
            'series_id' => 'required_if:event_type,series|nullable|exists:event_series,id',
            'series_edition' => 'nullable|integer',
            'title' => 'required|string|max:255|unique:events,title',
            'slug' => 'required|string|max:255|unique:events,slug',
            'description' => 'nullable|string',
            'event_mode' => 'required|in:online,offline,hybrid',
            'location' => 'nullable|string|max:255',
            'event_link' => 'nullable|url|max:500',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'timezone' => 'required|string|max:255',
            'is_free' => 'nullable|boolean',
            'price' => 'nullable|numeric|min:0',
            'currency' => 'required_if:is_free,0|string|max:3',
            'capacity' => 'nullable|integer|min:1',
            'waitlist_enabled' => 'nullable|boolean',
            'status' => 'required|in:draft,published,ongoing,ended,cancelled',
        ]);

        try {
            $validatedData['is_free'] = $request->has('is_free') ? 1 : 0;
            $validatedData['waitlist_enabled'] = $request->has('waitlist_enabled') ? 1 : 0;
            
            Event::create($validatedData);

            return redirect()->route($this->moduleUrl)->with('success', 'Event created successfully.');
        } catch (\Exception $e) {
            Log::error('Event Store Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'request' => $request->all(),
            ]);

            return redirect()->back()->withInput()->with('error', 'Failed to create Event. Please try again later.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        view()->share('action', 'View');
        $event = Event::with('series')->findOrFail(decrypt($id));
        return view('admin.event.show', compact('event'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        view()->share('action', 'Edit');
        $event = Event::findOrFail(decrypt($id));
        $series = EventSeries::where('status', 1)->get();
        return view('admin.event.form', compact('event', 'series'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $eventId = decrypt($id);
            $event = Event::findOrFail($eventId);

            $validatedData = $request->validate([
                'event_type' => 'required|in:single,series',
                'series_id' => 'required_if:event_type,series|nullable|exists:event_series,id',
                'series_edition' => 'nullable|integer',
                'title' => ['required', 'string', 'max:255', Rule::unique('events')->ignore($eventId)],
                'slug' => ['required', 'string', 'max:255', Rule::unique('events')->ignore($eventId)],
                'description' => 'nullable|string',
                'event_mode' => 'required|in:online,offline,hybrid',
                'location' => 'nullable|string|max:255',
                'event_link' => 'nullable|url|max:500',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'timezone' => 'required|string|max:255',
                'is_free' => 'nullable|boolean',
                'price' => 'nullable|numeric|min:0',
                'currency' => 'required_if:is_free,0|string|max:3',
                'capacity' => 'nullable|integer|min:1',
                'waitlist_enabled' => 'nullable|boolean',
                'status' => 'required|in:draft,published,ongoing,ended,cancelled',
            ]);

            $validatedData['is_free'] = $request->has('is_free') ? 1 : 0;
            $validatedData['waitlist_enabled'] = $request->has('waitlist_enabled') ? 1 : 0;

            if ($validatedData['event_type'] == 'single') {
                $validatedData['series_id'] = null;
                $validatedData['series_edition'] = null;
            }

            if ($validatedData['is_free']) {
                $validatedData['price'] = null;
            }

            $event->update($validatedData);

            return redirect()->route($this->moduleUrl)->with('success', 'Event updated successfully.');
        } catch (\Exception $e) {
            Log::error('Event Update Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'request' => $request->all(),
            ]);

            return redirect()->back()->withInput()->with('error', 'Failed to update Event. Please try again later.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $event = Event::findOrFail(decrypt($id));
            $event->delete();

            return response()->json([
                'success' => true,
                'message' => 'Event deleted successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error('Event Destroy Error', [
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

    public function checkTitle(Request $request)
    {
        $id = $request->event_id;
        $title = $request->title;

        if ($id) {
            $exists = Event::where('title', $title)->where('id', '!=', $id)->exists();
        } else {
            $exists = Event::where('title', $title)->exists();
        }

        if ($exists) {
            return "false";
        }

        return "true";
    }

    public function checkSlug(Request $request)
    {
        $id = $request->event_id;
        $slug = $request->slug;

        if ($id) {
            $exists = Event::where('slug', $slug)->where('id', '!=', $id)->exists();
        } else {
            $exists = Event::where('slug', $slug)->exists();
        }

        if ($exists) {
            return "false";
        }

        return "true";
    }
}
