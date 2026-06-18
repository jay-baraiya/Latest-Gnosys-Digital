<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\Designation;
use App\Models\Role;
use App\Models\UserRole;
use App\Notifications\RealTimeNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    protected $moduleName = 'Users';
    protected $moduleUrl = 'admin.users.index';

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

        $this->middleware('permission:create.users')->only('create', 'store');
        $this->middleware('permission:edit.users')->only('edit', 'update');
        $this->middleware('permission:delete.users')->only('destroy');
        $this->middleware('permission:view.users')->only('index', 'show');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return view('admin.user.index');
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {

        $excludedRoles = [User::IS_ADMIN];

        if (isset($request->is_buyer) && $request->is_buyer == 0) {
            $excludedRoles[] = User::IS_BUYER;
        }

            $data = User::with('roles')
                ->whereNotIn('id', [$this->authUser->id])
                ->when(!empty($request->is_deleted), function ($q){
                    $q->onlyTrashed();
                })
                ->when(!empty($request->is_buyer), function($q) {
                    $q->whereHas('roles', function($sq) {
                        $sq->where('role_id', User::IS_BUYER);
                    });
                })
                ->when(!empty($request->input('search.value')), function ($query) use ($request) {
                    $query->where(function($q) use ($request) {
                        $q->where('name', 'like', "%{$request->input('search.value')}%")
                        ->orWhere('email', 'like', "%{$request->input('search.value')}%")
                        ->orWhere('phone', 'like', "%{$request->input('search.value')}%");
                    });
                })
                ->whereDoesntHave('roles', function ($query) use ($excludedRoles) {
                    $query->whereIn('role_id', $excludedRoles);
                });

            return DataTables::eloquent($data)
                ->with('total_users', $data->count())
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    $name = $row->name ?? 'Unknown';

                    $imagePath = $row->image ? asset($row->image) : asset('assets/img/profiles/default.jpg');

                    return '<h6 class="d-flex align-items-center fs-14 fw-medium mb-0">
                                <a href="' . $imagePath . '" target="blank" class="avatar avatar-rounded me-2">
                                    <img src="' . $imagePath . '" alt="' . $name . '">
                                </a>
                                <a href="' . $imagePath . '" target="blank" class="d-flex flex-column">' . $name . '</a>
                            </h6>';
                })
                ->editColumn('status', function ($row) {
                    if ($row->status == 1) {
                        return '<a href="' . route('admin.users.updateStatus', ['id' => encrypt($row->id), 'status' => 0]) . '" class="badge badge-pill badge-status bg-success" id="statusUpdate">Active</a>';
                    } else {
                        return '<a href="' . route('admin.users.updateStatus', ['id' => encrypt($row->id), 'status' => 1]) . '" class="badge badge-pill badge-status bg-danger" id="statusUpdate">Inactive</a>';
                    }
                })
                ->addColumn('role', function ($row) {
                    return $row->role?->name ?? '-';
                })
                ->addColumn('actions', function ($row) use ($request) {
                    return view('admin.components.action-links', [
                        'edit' => route('admin.users.edit', encrypt($row->id)),
                        'show' => route('admin.users.show', encrypt($row->id)),
                        'delete' => route('admin.users.destroy', encrypt($row->id)),
                        'restore' => route('admin.users.restore', encrypt($row->id)),
                        'id' => encrypt($row->id),
                        'is_deleted' => $request->is_deleted,
                    ])->render();
                })
                ->rawColumns(['status', 'role', 'actions','name'])
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        view()->share('action', 'Create');
        $roles = Role::query()->where('status', 1)->where('id', '!=', 1)->get();
        $designations = Designation::query()->where('status', 1)->get();

        return view('admin.user.form', compact('roles', 'designations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'country_id' => 'required|exists:countries,id',
            'state_id' => 'required|exists:states,id',
            'city_id' => 'required|exists:cities,id',
            'zip' => 'nullable|string|max:10',
            'status' => 'required|in:1,0',
            'role_id' => 'required|exists:roles,id',
            // 'image' => 'required|image|mimes:jpeg,jpg,png,webp|max:1024',
            'designation_id' => 'required'
        ],
        [
            'image.mimes' => 'Only JPEG, JPG, PNG, and WEBP images are allowed.',
            'image.max' => 'The image size must not exceed 1 MB.',
        ]);

        try {

            $imagePath = null;

            if ($request->hasFile('image')) {

                $path = $request->file('image')->store('users', 'public');

                $imagePath = Storage::url($path);

            }

            $validatedData['password'] = Hash::make($validatedData['password']);
            $validatedData['image'] = $imagePath;
            $validatedData['is_user'] = 1;
            $validatedData['designation_id'] = $request->designation_id;

            $roleId = $validatedData['role_id'];
            unset($validatedData['role_id']);

            $user = User::create($validatedData);

            if ($user && !empty($roleId)) {
                UserRole::create([
                    'user_id' => $user->id,
                    'role_id' => $roleId,
                ]);
            }

            return redirect()->route($this->moduleUrl)->with('success', 'User created successfully.');
        } catch (\Exception $e) {
            Log::error('User Store Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'request' => $request->all(),
            ]);

            return redirect()->back()->withInput()->with('error', 'Failed to create user. Please try again later.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        view()->share('action', 'View');
        $user = User::with(['country', 'state', 'city'])->findOrFail(decrypt($id));
        $designations = Designation::query()->where('status', 1)->get();
        return view('admin.user.show', compact('user','designations'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        view()->share('action', 'Edit');
        $user = User::with(['country', 'state', 'city'])->findOrFail(decrypt($id));

        // Notify the currently logged in user so they see the toast/alert immediately
        Auth::user()->notify(new RealTimeNotification('You are editing ' . $user->name . '\'s profile!', []));

        $roles = Role::query()->where('status', 1)->where('id', '!=', 1)->get();
        $designations = Designation::query()->where('status', 1)->get();

        return view('admin.user.form', compact('user', 'roles','designations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        try {
            $userId = decrypt($id);
            $user = User::findOrFail($userId);

            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($userId)],
                'password' => 'nullable|string|min:8|confirmed',
                'phone' => ['nullable', 'string', 'max:20', Rule::unique('users')->ignore($userId)],
                'address' => 'nullable|string',
                'country_id' => 'required|exists:countries,id',
                'state_id' => 'required|exists:states,id',
                'city_id' => 'required|exists:cities,id',
                'zip' => 'nullable|string|max:10',
                'status' => 'required|in:1,0',
                // 'image' => 'required|image|mimes:jpeg,jpg,png,webp|max:1024',
            ],[
                'image.mimes' => 'Only JPEG, JPG, PNG, and WEBP images are allowed.',
                'image.max' => 'The image size must not exceed 1 MB.',
            ]);

            $imagePath = $user->image;

            if ($request->remove_existing_image == '1') {
                if ($user->image && !filter_var($user->image, FILTER_VALIDATE_URL)) {
                    $pathToDelete = str_replace('/storage/', '', $user->image);
                    if(Storage::disk('public')->exists($pathToDelete)) {
                        Storage::disk('public')->delete($pathToDelete);
                    }
                }
                $imagePath = null;
            }

            if ($request->hasFile('image')) {
                if ($user->image && !filter_var($user->image, FILTER_VALIDATE_URL)) {
                    $pathToDelete = str_replace('/storage/', '', $user->image);
                    if(Storage::disk('public')->exists($pathToDelete)) {
                        Storage::disk('public')->delete($pathToDelete);
                    }
                }

                $path = $request->file('image')->store('users', 'public');
                $imagePath = Storage::url($path);

            }

            if (empty($validatedData['password'])) {
                unset($validatedData['password']);
            } else {
                $validatedData['password'] = Hash::make($validatedData['password']);
            }

            $validatedData['image'] = $imagePath;
            $validatedData['is_user'] = 1;

            $user->designation_id = $request->designation_id;

            $user->update($validatedData);


            if ($user && !empty($request->role_id)) {
                UserRole::updateOrCreate([
                    'user_id' => $user->id,
                ], [
                    'role_id' => $request->role_id,
                ]);
            } else {
                UserRole::query()->where('user_id', $user->id)->delete();
            }

            return redirect()->route($this->moduleUrl)->with('success', 'User updated successfully.');
        } catch (\Exception $e) {
            Log::error('User Update Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'request' => $request->all(),
            ]);

            return redirect()->back()->withInput()->with('error', 'Failed to update user. Please try again later.');
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
