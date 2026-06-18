<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\Role;
use App\Models\UserRole;
use App\Notifications\RealTimeNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    protected $moduleName = 'Users';
    protected $moduleUrl = 'users.index';

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
        return view('user.index');
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {

            $data = User::with('roles')->whereNotIn('id', [$this->authUser->id])
                ->whereDoesntHave('roles', function ($query) {
                    $query->where('role_id', 1);
                });

            return DataTables::eloquent($data)
                ->with('total_users', $data->count())
                ->addIndexColumn()
                ->editColumn('status', function ($row) {
                    if ($row->status == 1) {
                        return '<a href="' . route('users.updateStatus', ['id' => encrypt($row->id), 'status' => 0]) . '" class="badge badge-pill badge-status bg-success" id="statusUpdate">Active</a>';
                    } else {
                        return '<a href="' . route('users.updateStatus', ['id' => encrypt($row->id), 'status' => 1]) . '" class="badge badge-pill badge-status bg-danger" id="statusUpdate">Inactive</a>';
                    }
                })
                ->addColumn('role', function ($row) {
                    return $row->role?->name ?? '-';
                })
                ->addColumn('actions', function ($row) {
                    return view('components.action-links', [
                        'edit' => route('users.edit', encrypt($row->id)),
                        'show' => route('users.show', encrypt($row->id)),
                        'delete' => route('users.destroy', encrypt($row->id)),
                        'id' => encrypt($row->id)
                    ])->render();
                })
                ->rawColumns(['status', 'role', 'actions'])
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        view()->share('action', 'Create');
        $roles = Role::where('status', 1)->where('id', '!=', 1)->get();
        return view('user.form', compact('roles'));
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
        ]);

        try {
            $validatedData['password'] = Hash::make($validatedData['password']);

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
        return view('user.show', compact('user'));
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

        $roles = Role::where('status', 1)->where('id', '!=', 1)->get();
        return view('user.form', compact('user', 'roles'));
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
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string',
                'country_id' => 'required|exists:countries,id',
                'state_id' => 'required|exists:states,id',
                'city_id' => 'required|exists:cities,id',
                'zip' => 'nullable|string|max:10',
                'status' => 'required|in:1,0',
            ]);

            if (empty($validatedData['password'])) {
                unset($validatedData['password']);
            } else {
                $validatedData['password'] = Hash::make($validatedData['password']);
            }

            $user->update($validatedData);

            if ($user && !empty($request->role_id)) {
                UserRole::updateOrCreate([
                    'user_id' => $user->id,
                ], [
                    'role_id' => $request->role_id,
                ]);
            } else {
                UserRole::where('user_id', $user->id)->delete();
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
            $user = User::findOrFail(decrypt($request->id));
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
}
