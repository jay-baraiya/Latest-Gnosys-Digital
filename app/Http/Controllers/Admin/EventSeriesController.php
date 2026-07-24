<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\EventSeries;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class EventSeriesController extends Controller
{
    protected $moduleName = 'Event Series';
    protected $moduleUrl = 'admin.event_series.index';

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

        $this->middleware('permission:create.event_series')->only('create', 'store');
        $this->middleware('permission:edit.event_series')->only('edit', 'update');
        $this->middleware('permission:delete.event_series')->only('destroy');
        $this->middleware('permission:view.event_series')->only('index', 'show');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return view('admin.event_series.index');
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {

            $data = EventSeries::query();

            return DataTables::eloquent($data)
                ->with('total_records', $data->count())
                ->addIndexColumn()
                ->editColumn('status', function ($row) {
                    if ($row->status == 1) {
                        return '<a href="' . route('admin.event_series.updateStatus', ['id' => encrypt($row->id), 'status' => 0]) . '" class="badge badge-pill badge-status bg-success" id="statusUpdate">Active</a>';
                    } else {
                        return '<a href="' . route('admin.event_series.updateStatus', ['id' => encrypt($row->id), 'status' => 1]) . '" class="badge badge-pill badge-status bg-danger" id="statusUpdate">Inactive</a>';
                    }
                })
                ->addColumn('actions', function ($row) use ($request) {
                    return view('admin.components.action-links', [
                        'edit' => route('admin.event_series.edit', encrypt($row->id)),
                        'show' => route('admin.event_series.show', encrypt($row->id)),
                        'delete' => route('admin.event_series.destroy', encrypt($row->id)),
                        'id' => encrypt($row->id),
                    ])->render();
                })
                ->rawColumns(['status', 'actions'])
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        view()->share('action', 'Create');
        return view('admin.event_series.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:event_series,name',
            'slug' => 'required|string|max:255|unique:event_series,slug',
            'description' => 'nullable|string',
            'date_time' => 'nullable|date',
            'status' => 'required|in:1,0',
            'is_archived' => 'nullable|boolean',
        ]);

        try {
            $validatedData['is_archived'] = $request->has('is_archived') ? 1 : 0;
            EventSeries::create($validatedData);

            return redirect()->route($this->moduleUrl)->with('success', 'Event Series created successfully.');
        } catch (\Exception $e) {
            Log::error('Event Series Store Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'request' => $request->all(),
            ]);

            return redirect()->back()->withInput()->with('error', 'Failed to create Event Series. Please try again later.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        view()->share('action', 'View');
        $eventSeries = EventSeries::findOrFail(decrypt($id));
        return view('admin.event_series.show', compact('eventSeries'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        view()->share('action', 'Edit');
        $eventSeries = EventSeries::findOrFail(decrypt($id));
        return view('admin.event_series.form', compact('eventSeries'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $eventSeriesId = decrypt($id);
            $eventSeries = EventSeries::findOrFail($eventSeriesId);

            $validatedData = $request->validate([
                'name' => ['required', 'string', 'max:255', Rule::unique('event_series')->ignore($eventSeriesId)],
                'slug' => ['required', 'string', 'max:255', Rule::unique('event_series')->ignore($eventSeriesId)],
                'description' => 'nullable|string',
                'date_time' => 'nullable|date',
                'status' => 'required|in:1,0',
                'is_archived' => 'nullable|boolean',
            ]);

            $validatedData['is_archived'] = $request->has('is_archived') ? 1 : 0;

            $eventSeries->update($validatedData);

            return redirect()->route($this->moduleUrl)->with('success', 'Event Series updated successfully.');
        } catch (\Exception $e) {
            Log::error('Event Series Update Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'request' => $request->all(),
            ]);

            return redirect()->back()->withInput()->with('error', 'Failed to update Event Series. Please try again later.');
        }
    }

    public function updateStatus(Request $request)
    {
        try {
            $eventSeries = EventSeries::findOrFail(decrypt($request->id));
            $eventSeries->update([
                'status' => $request->status,
            ]);

            return response()->json([
                'success' => true,
                'message' => $eventSeries->status == 1 ? 'Event Series activated successfully.' : 'Event Series deactivated successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error('Event Series Status Update Error', [
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
            $eventSeries = EventSeries::findOrFail(decrypt($id));
            $eventSeries->delete();

            return response()->json([
                'success' => true,
                'message' => 'Event Series deleted successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error('Event Series Destroy Error', [
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

    public function checkName(Request $request)
    {
        $id = $request->event_series_id;
        $name = $request->name;

        if ($id) {
            $exists = EventSeries::where('name', $name)->where('id', '!=', $id)->exists();
        } else {
            $exists = EventSeries::where('name', $name)->exists();
        }

        if ($exists) {
            return "false";
        }

        return "true";
    }

    public function checkSlug(Request $request)
    {
        $id = $request->event_series_id;
        $slug = $request->slug;

        if ($id) {
            $exists = EventSeries::where('slug', $slug)->where('id', '!=', $id)->exists();
        } else {
            $exists = EventSeries::where('slug', $slug)->exists();
        }

        if ($exists) {
            return "false";
        }

        return "true";
    }
}
