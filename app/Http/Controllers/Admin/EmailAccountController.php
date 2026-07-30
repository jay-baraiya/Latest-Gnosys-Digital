<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class EmailAccountController extends Controller
{
    protected $moduleName = 'Email Accounts';

    protected $moduleUrl = 'admin.email_accounts.index';

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
        return view('admin.email_account.index');
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $data = EmailAccount::query()
                ->select(['id', 'name', 'email', 'protocol', 'status'])
                ->when(! empty($request->is_deleted), function ($q) {
                    $q->onlyTrashed();
                })
                ->when(! empty($request->input('search.value')), function ($query) use ($request) {
                    $query->where(function ($q) use ($request) {
                        $q->where('name', 'like', "%{$request->input('search.value')}%")
                            ->orWhere('email', 'like', "%{$request->input('search.value')}%");
                    });
                })->orderBy('id', 'DESC');

            return DataTables::eloquent($data)
                ->with('total_email_accounts', $data->count())
                ->addIndexColumn()
                ->editColumn('status', function ($row) {
                    if ($row->status == 1) {
                        return '<a href="'.route('admin.email_accounts.updateStatus', ['id' => encrypt($row->id), 'status' => 0]).'" class="badge badge-pill badge-status bg-success" id="statusUpdate">Active</a>';
                    } else {
                        return '<a href="'.route('admin.email_accounts.updateStatus', ['id' => encrypt($row->id), 'status' => 1]).'" class="badge badge-pill badge-status bg-danger" id="statusUpdate">Inactive</a>';
                    }
                })
                ->addColumn('actions', function ($row) use ($request) {
                    return view('admin.components.action-links', [
                        'edit' => route('admin.email_accounts.edit', encrypt($row->id)),
                        'show' => route('admin.email_accounts.show', encrypt($row->id)),
                        'delete' => route('admin.email_accounts.destroy', encrypt($row->id)),
                        'restore' => route('admin.email_accounts.restore', encrypt($row->id)),
                        'id' => encrypt($row->id),
                        'is_deleted' => $request->is_deleted,
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

        return view('admin.email_account.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:email_accounts,name',
            'email' => 'required|email|max:255',
            'username' => 'required|string|max:255',
            'password' => 'required|string|max:255',
            'host' => 'required|string|max:255',
            'port' => 'required|string|max:255',
            'protocol' => 'required|string|in:imap,pop3',
            'encryption' => 'nullable|string|in:ssl,tls',
            'status' => 'required|in:1,0',
        ]);

        EmailAccount::create($validated);

        return redirect()->route($this->moduleUrl)->with('success', 'Email Account created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        view()->share('action', 'View');
        $email_account = EmailAccount::findOrFail(decrypt($id));

        return view('admin.email_account.show', compact('email_account'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        view()->share('action', 'Edit');
        $email_account = EmailAccount::findOrFail(decrypt($id));

        return view('admin.email_account.form', compact('email_account'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $emailAccountId = decrypt($id);
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('email_accounts', 'name')->ignore($emailAccountId)],
            'email' => 'required|email|max:255',
            'username' => 'required|string|max:255',
            'password' => 'nullable|string|max:255',
            'host' => 'required|string|max:255',
            'port' => 'required|string|max:255',
            'protocol' => 'required|string|in:imap,pop3',
            'encryption' => 'nullable|string|in:ssl,tls',
            'status' => 'required|in:1,0',
        ]);

        $emailAccount = EmailAccount::findOrFail($emailAccountId);

        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        $emailAccount->update($validated);

        return redirect()->route($this->moduleUrl)->with('success', 'Email Account updated successfully.');
    }

    public function updateStatus(Request $request)
    {
        $emailAccount = EmailAccount::findOrFail(decrypt($request->id));

        $emailAccount->update([
            'status' => $request->status,
        ]);

        if ($emailAccount->status == 1) {
            $message = 'Email Account activated successfully.';
        } else {
            $message = 'Email Account deactivated successfully.';
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
            $emailAccount = EmailAccount::withTrashed()->findOrFail(decrypt($id));
            if ($emailAccount->trashed()) {
                $emailAccount->forceDelete();
                $message = 'Email Account permanently deleted successfully.';
            } else {
                $emailAccount->delete();
                $message = 'Email Account deleted successfully.';
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

    public function checkEmailAccount(Request $request)
    {
        $exists = EmailAccount::query()
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
            $emailAccount = EmailAccount::withTrashed()->findOrFail(decrypt($id));
            $emailAccount->restore();

            return response()->json([
                'success' => true,
                'message' => 'Email Account restored successfully.',
            ]);

        } catch (\Exception $e) {
            Log::error('Email Account Restore Error', [
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
