<?php

namespace App\Http\Controllers\Admin;

use App\Models\CustomField;
use App\Models\CustomFieldType;
use App\Models\Department;
use App\Models\EmailAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

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
                ->when(! empty($request->is_deleted), function ($q) {
                    $q->onlyTrashed();
                })
                ->when(! empty($request->input('search.value')), function ($query) use ($request) {
                    $query->where(function ($q) use ($request) {
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
                        return '<a href="'.route('admin.departments.updateStatus', ['id' => encrypt($row->id), 'status' => 0]).'" class="badge badge-pill badge-status bg-success" id="statusUpdate">Active</a>';
                    } else {
                        return '<a href="'.route('admin.departments.updateStatus', ['id' => encrypt($row->id), 'status' => 1]).'" class="badge badge-pill badge-status bg-danger" id="statusUpdate">Inactive</a>';
                    }
                })
                ->addColumn('actions', function ($row) use ($request) {
                    return view('admin.components.action-links', [
                        'edit' => route('admin.departments.edit', encrypt($row->id)),
                        'show' => route('admin.departments.show', encrypt($row->id)),
                        'delete' => route('admin.departments.destroy', encrypt($row->id)),
                        'restore' => route('admin.departments.restore', encrypt($row->id)),
                        'id' => encrypt($row->id),
                        'is_deleted' => $request->is_deleted,
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

        $customfieldtyeps = CustomFieldType::query()->where('status', 1)->get();
        $emailAccounts = EmailAccount::where('status', 1)
            ->whereNotIn('id', function ($query) {
                $query->select('email_id')
                    ->from('departments')
                    ->whereNotNull('email_id')
                    ->whereNull('deleted_at');
            })->get();

        return view('admin.department.form', compact('customfieldtyeps', 'emailAccounts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name',
            'email_id' => 'nullable|exists:email_accounts,id',
            'status' => 'required|in:1,0',
        ]);

        $department = Department::create([
            'name' => $request->name,
            'email_id' => $request->email_id,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        if ($request->has('custom_field') && $department->id) {
            $customFieldData = $request->input('custom_field', []);
            $customFieldData['recode_id'] = $department->id;
            $request->merge([
                'custom_field' => $customFieldData,
            ]);

            $customFieldIds = CommonController::storeCustomFields($request);
        }

        return redirect()->route($this->moduleUrl)->with('success', 'Department created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        view()->share('action', 'View');
        $department = Department::findOrFail(decrypt($id));
        $customfieldtyeps = CustomFieldType::query()->where('status', 1)->get();
        $emailAccounts = EmailAccount::where('status', 1)->get();
        $customfields = CustomField::with(['fieldType'])->where('module_type', 'department')->where('recode_id', decrypt($id))->orderBy('sort_order', 'ASC')->get();

        return view('admin.department.show', compact('department', 'customfieldtyeps', 'customfields', 'emailAccounts'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        view()->share('action', 'Edit');
        $department = Department::findOrFail(decrypt($id));
        $customfieldtyeps = CustomFieldType::query()->where('status', 1)->get();
        $emailAccounts = EmailAccount::where('status', 1)
            ->where(function ($q) use ($department) {
                $q->whereNotIn('id', function ($query) {
                    $query->select('email_id')
                        ->from('departments')
                        ->whereNotNull('email_id')
                        ->whereNull('deleted_at');
                });
                if ($department->email_id) {
                    $q->orWhere('id', $department->email_id);
                }
            })->get();
        $customfields = CustomField::with(['fieldType'])->where('module_type', 'department')->where('recode_id', decrypt($id))->orderBy('sort_order', 'ASC')->get();

        return view('admin.department.form', compact('department', 'customfieldtyeps', 'customfields', 'emailAccounts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $departmentId = decrypt($id);
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('departments', 'name')->ignore($departmentId)],
            'email_id' => 'nullable|exists:email_accounts,id',
            'status' => 'required|in:1,0',
        ]);

        $department = Department::findOrFail($departmentId);

        $department->update([
            'name' => $request->name,
            'email_id' => $request->email_id,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        if ($request->has('custom_field')) {
            $customFieldIds = CommonController::storeCustomFields($request);
        }

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
            'message' => $message,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $department = Department::withTrashed()->findOrFail(decrypt($id));

            if ($department->trashed()) {
                $department->forceDelete();
                $message = 'Department permanently deleted successfully.';
            } else {
                $department->delete();
                $message = 'Department deleted successfully.';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
            ]);
        }
    }

    public function checkDepartment(Request $request)
    {
        $exists = Department::query()
            ->where('name', $request->name)
            ->when(! empty($request->id), function ($query) use ($request) {
                $query->where('id', '!=', decrypt($request->id));
            })
            ->exists();

        return response()->json(! $exists);
    }

    public function restore(string $id)
    {
        try {
            $department = Department::withTrashed()->findOrFail(decrypt($id));

            $department->restore();

            return response()->json([
                'success' => true,
                'message' => 'Department restored successfully.',
            ]);

        } catch (\Exception $e) {
            Log::error('Department Restore Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
            ]);
        }
    }
}
