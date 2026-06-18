<?php

namespace App\Http\Controllers\Admin;

use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    protected $moduleName = 'Roles';
    protected $moduleUrl = 'admin.roles.index';

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
        return view('admin.role.index');
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $data = Role::query()
            ->select(['id', 'name', 'slug', 'description', 'status'])
            ->when(!empty($request->input('search.value')), function ($query) use ($request) {
                $query->where(function($q) use ($request) {
                    $q->where('name', 'like', "%{$request->input('search.value')}%")
                    ->orWhere('slug', 'like', "%{$request->input('search.value')}%");
                });
            })
            ->whereNotIn('id', [1]);

            return DataTables::eloquent($data)
                ->with('total_roles', $data->count())
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
                        return '<a href="' . route('admin.roles.updateStatus', ['id' => encrypt($row->id), 'status' => 0]) . '" class="badge badge-pill badge-status bg-success" id="statusUpdate">Active</a>';
                    } else {
                        return '<a href="' . route('admin.roles.updateStatus', ['id' => encrypt($row->id), 'status' => 1]) . '" class="badge badge-pill badge-status bg-danger" id="statusUpdate">Inactive</a>';
                    }
                })
                ->addColumn('actions', function ($row) {
                    return view('admin.components.action-links', [
                        'edit' => route('admin.roles.edit', encrypt($row->id)),
                        'show' => route('admin.roles.show', encrypt($row->id)),
                        'delete' => route('admin.roles.destroy', encrypt($row->id)),
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
        $permissions = Permission::active()->get()->groupBy('module');
        return view('admin.role.form', compact('permissions'));
    }

    /**
     * Generate unique slug for role.
     */
    protected function generateUniqueSlug($name, $ignoreId = 0)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $count = 1;

        while (Role::withTrashed()->where('slug', $slug)->where('id', '!=', $ignoreId)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'status' => 'required|in:1,0',
        ]);

        $role = Role::create([
            'name' => $request->name,
            'slug' => $this->generateUniqueSlug($request->name),
            'description' => $request->description,
            'status' => $request->status,
        ]);

        if ($role && !empty($request->permissions)) {
            $rolePermissions = [];
            foreach ($request->permissions as $permission) {
                $rolePermissions[] = [
                    'role_id' => $role->id,
                    'permission_id' => $permission,
                ];
            }
            RolePermission::insert($rolePermissions);
        }

        return redirect()->route($this->moduleUrl)->with('success', 'Role created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        view()->share('action', 'View');
        $role = Role::findOrFail(decrypt($id));
        $permissions = Permission::active()->get()->groupBy('module');
        return view('admin.role.show', compact('role', 'permissions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        view()->share('action', 'Edit');
        $role = Role::findOrFail(decrypt($id));
        $permissions = Permission::active()->get()->groupBy('module');

        $rolePermissions = RolePermission::query()->where('role_id', $role->id)->pluck('permission_id')->toArray();

        return view('admin.role.show', compact('role', 'permissions'));
        return view('admin.role.form', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $roleId = decrypt($id);
        $role = Role::findOrFail($roleId);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('roles', 'name')->ignore($roleId)],
            'status' => 'required|in:1,0',
        ]);

        $role->update([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        RolePermission::where('role_id', $role->id)->delete();

        if ($role && !empty($request->permissions)) {
            foreach ($request->permissions as $permission) {
                RolePermission::updateOrCreate([
                    'role_id' => $role->id,
                    'permission_id' => $permission,
                ], [
                    'role_id' => $role->id,
                    'permission_id' => $permission,
                ]);
            }
        }

        return redirect()->route($this->moduleUrl)->with('success', 'Role updated successfully.');
    }

    public function updateStatus(Request $request)
    {
        $role = Role::findOrFail(decrypt($request->id));
        $role->update([
            'status' => $request->status,
        ]);

        if ($role->status == 1) {
            $message = 'Role activated successfully.';
        } else {
            $message = 'Role deactivated successfully.';
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
            $role = Role::with('users')->findOrFail(decrypt($id));

            if ($role->users()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete this role because it is currently assigned to one or more users. Please reassign those users first.'
                ]);
            }

            $role->delete();

            return response()->json([
                'success' => true,
                'message' => 'Role deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!'
            ]);
        }
    }

    public function checkRole(Request $request)
    {
        $exists = Role::where('name', $request->name)
            ->when(!empty($request->id), function ($query) use ($request) {
                $query->where('id', '!=', decrypt($request->id));
            })
            ->exists();

        return response()->json(!$exists);
    }
}
