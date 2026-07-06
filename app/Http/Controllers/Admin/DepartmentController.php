<?php

namespace App\Http\Controllers\Admin;

use App\Models\Department;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class DepartmentController extends Controller
{
    protected $moduleName = 'Departments';
    protected $moduleUrl = 'admin.departments.index';

    public function __construct()
    {
        view()->share([
            'moduleName' => $this->moduleName,
            'moduleUrl' => $this->moduleUrl,
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return view('admin.department.index');
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $data = Department::query()
            ->select(['id', 'name', 'description', 'status'])
            ->when(!empty($request->is_deleted), function ($q){
                $q->onlyTrashed();
            })
            ->when(!empty($request->input('search.value')), function ($query) use ($request) {
                $query->where(function($q) use ($request) {
                    $q->where('name', 'like', "%{$request->input('search.value')}%");
                });
            })->orderBy('id', 'DESC');

            return DataTables::eloquent($data)
                ->with('total_departments', $data->count())
                ->addIndexColumn()
                // ->editColumn('checkbox', function () {
                //     return '<div class="form-check form-check-md">
                //                     <input class="form-check-input" type="checkbox" id="select-all">
                //                 </div>';
                // })
                ->editColumn('description', function ($row) {
                    return $row->description ?? '-';
                })
                ->editColumn('status', function ($row) {
                    if ($row->status == 1) {
                        return '<a href="' . route('admin.departments.updateStatus', ['id' => encrypt($row->id), 'status' => 0]) . '" class="badge badge-pill badge-status bg-success" id="statusUpdate">Active</a>';
                    } else {
                        return '<a href="' . route('admin.departments.updateStatus', ['id' => encrypt($row->id), 'status' => 1]) . '" class="badge badge-pill badge-status bg-danger" id="statusUpdate">Inactive</a>';
                    }
                })
                ->addColumn('actions', function ($row) {
                    return view('admin.components.action-links', [
                        'edit' => route('admin.departments.edit', encrypt($row->id)),
                        'show' => route('admin.departments.show', encrypt($row->id)),
                        'delete' => route('admin.departments.destroy', encrypt($row->id)),
                        'id' => encrypt($row->id)
                    ])->render();
                })
                ->rawColumns(['status', 'actions', 'checkbox'])
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        view()->share('action', 'Create');

        return view('admin.department.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name',
            'status' => 'required|in:1,0',
        ]);

        $department = Department::create([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return redirect()->route($this->moduleUrl)->with('success', 'Department created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        view()->share('action', 'View');
        $department = Department::findOrFail(decrypt($id));

        return view('admin.department.show', compact('department'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        view()->share('action', 'Edit');
        $department = Department::findOrFail(decrypt($id));

        return view('admin.department.form', compact('department'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $departmentId = decrypt($id);
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('departments', 'name')->ignore($departmentId)],
            'status' => 'required|in:1,0',
        ]);

        $department = Department::findOrFail($departmentId);

        $department->update([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return redirect()->route($this->moduleUrl)->with('success', 'Department updated successfully.');
    }

    public function updateStatus(Request $request)
    {
        $department = Department::findOrFail(decrypt($request->id));

        $department->update([
            'status' => $request->status,
        ]);

        if ($department->status == 1) {
            $message = 'Department activated successfully.';
        } else {
            $message = 'Department deactivated successfully.';
        }

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $department = Department::findOrFail(decrypt($id));

            $department->delete();

            return response()->json([
                'success' => true,
                'message' => 'Department deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!'
            ]);
        }
    }

    public function checkDepartment(Request $request)
    {
        $exists = Department::query()
            ->where('name', $request->name)
            ->when(!empty($request->id), function ($query) use ($request) {
                $query->where('id', '!=', decrypt($request->id));
            })
            ->exists();

        return response()->json(!$exists);
    }
}
