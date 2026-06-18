<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class WalletController extends Controller
{
    protected $moduleName = 'Wallets';
    protected $moduleUrl = 'admin.wallets.index';

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

        $this->middleware('permission:create.wallets')->only('create', 'store');
        // $this->middleware('permission:edit.users')->only('edit', 'update');
        $this->middleware('permission:delete.wallets')->only('destroy');
        $this->middleware('permission:view.wallets')->only('index', 'show');
        // $this->middleware('permission:approve.wallets')->only('action');
        // $this->middleware('permission:reject.wallets')->only('reject');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $buyers = User::with('roles')
                ->whereNotIn('id', [$this->authUser->id])
                ->whereHas('roles', function($sq) {
                    $sq->where('role_id', User::IS_BUYER);
                })
                ->pluck('name', 'id');

        return view('admin.wallets.index', compact('buyers'));
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $searchValue = $request->input('search.value');
            $data = Wallet::select(['id','user_id','date','balance','currency','status','is_approved'])
            ->with(['user:id,name'])
            ->when(!empty($searchValue), function ($query) use ($searchValue) {
                $query->where(function($q) use ($searchValue) {

                    $q->Where('date', 'like', "%{$searchValue}%")

                    ->orWhereHas('user', function($userQuery) use ($searchValue) {
                        $userQuery->where('name', 'like', "%{$searchValue}%");
                    });

                });
            });

            return DataTables::eloquent($data)
                ->with('total_wallets', $data->count())
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    $name = $row->user?->name ?? 'Unknown';

                    return $name;
                })
                ->addColumn('transaction_id', function ($row) {
                    return $row->transaction_id ?? '-';
                })
                ->addColumn('proof', function ($row) {

                    if (!empty($row->proof)) {
                        $fileUrl = asset($row->proof);

                        return '<a href="' . $fileUrl . '" target="_blank" class="btn btn-sm btn-primary">
                                    <i class="fas fa-file-alt me-1"></i> View Proof
                                </a>';
                    }

                    return '<span class="badge bg-secondary">No Proof</span>';
                })
                ->addColumn('date', function ($row) {
                    return $row->date->format('d-m-Y') ?? '-';
                })
                ->addColumn('balance', function ($row) {
                    return '$'. number_format($row->balance, 2);
                })
                ->addColumn('is_approved', function ($row) {
                    return match ($row->is_approved) {
                        0 => '<span class="badge bg-warning">Pending</span>',
                        1 => '<span class="badge bg-success">Approved</span>',
                        2 => '<span class="badge bg-danger">Rejected</span>',
                        3 => '<span class="badge bg-success">Reapproved</span>',
                        default => '<span class="">-</span>',
                    };
                })
                ->addColumn('actions', function ($row) use ($request) {
                    return view('admin.components.action-links', [
                        'delete' => route('admin.wallets.destroy', encrypt($row->id)),
                        'id' => encrypt($row->id),
                        'history' => route('admin.wallets.getTransactionHistoty', ['id' => encrypt($row->id), 'user_id' => encrypt($row->user_id)]),
                        'approve' => route('admin.wallets.action', ['id' => encrypt($row->id), 'user_id' => encrypt($row->user_id), 'action' => 'approve']),
                        'reject' => route('admin.wallets.action', ['id' => encrypt($row->id), 'user_id' => encrypt($row->user_id), 'action' => 'reject']),
                        'reapprove' => route('admin.wallets.action', ['id' => encrypt($row->id), 'user_id' => encrypt($row->user_id), 'action' => 'reapprove']),
                        'is_approved' => $row->is_approved,
                    ])->render();
                })
                ->rawColumns(['name','date','balance','actions','proof','transaction_id','is_approved'])
                ->make(true);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'buyer_id' => 'required',
            'amount'   => 'required|numeric|min:1|max:50000'
        ], [
            'buyer_id.required' => 'Please select a buyer.',
            'amount.required'   => 'Please enter wallet amount.',
            'amount.numeric'    => 'Amount must be a valid number.',
            'amount.min'        => 'Minimum add balance amount is $1.',
            'amount.max'        => 'Maximum add balance amount is $50,000.'
        ]);

        $currentDate = Carbon::now();

        try {
            DB::beginTransaction();

            $wallet = Wallet::firstOrNew([
                'user_id' => $request->buyer_id
            ]);

            $oldBalance = $wallet->balance ?? 0;

            $wallet->date = $currentDate;
            $wallet->user_id = $request->buyer_id;
            $wallet->balance = $oldBalance + $request->amount;
            $wallet->save();

            $newBalance = $wallet->balance;

            $walletHistory = new WalletHistory();
            $walletHistory->date = $currentDate;
            $walletHistory->wallet_id = $wallet->id;
            $walletHistory->user_id = $request->buyer_id;
            $walletHistory->type = 'credit';
            $walletHistory->balance_before = $oldBalance;
            $walletHistory->transfer_amount = $request->amount;
            $walletHistory->balance_after = $newBalance;
            $walletHistory->status = 'success';
            $walletHistory->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Wallet credit added successfully.',
                'new_balance' => $newBalance
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Wallet Add Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong! Please try again.'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $wallet = Wallet::findOrFail(decrypt($id));

            if (!empty($wallet->proof)) {
                $walletFilePath = str_replace('/storage/', '', $wallet->proof);
                if (Storage::disk('public')->exists($walletFilePath)) {
                    Storage::disk('public')->delete($walletFilePath);
                }
            }

            WalletHistory::query()->where('user_id', $wallet->user_id)->where('id', $wallet->id)->delete();

            $wallet->delete();

            return response()->json([
                'success' => true,
                'message' => 'Wallet deleted successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error('Wallet Destroy Error', [
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

    public function getTransactionHistoty(Request $request)
    {

        $id = !empty($request->id) ? decrypt($request->id) : null;
        $user_id = !empty($request->user_id) ? decrypt($request->user_id) : null;

        if ($id && $user_id) {

            $historys = Wallet::with(['histories' => function($q) use ($user_id) {
                $q->where('user_id', $user_id);
            }])->find($id);

            $html = view(
                'admin.wallets.wallet-history',
                compact('historys')
            )->render();

            return response()->json([
                'status' => true,
                'html'   => $html
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Invalid User'
        ]);
    }

    public function action(Request $request, string $id, string $user_id, string $action)
    {
        try {
            $recordId = decrypt($id);
            $userId = decrypt($user_id);

            $wallet = Wallet::query()
                            ->where('id', $recordId)
                            ->where('user_id', $userId)
                            ->firstOrFail();

            $message = '';

            if ($action === 'approve') {
                $wallet->is_approved = 1;
                $message = 'Wallet proof approved successfully.';
            } elseif ($action === 'reject') {
                $wallet->is_approved = 2;
                $wallet->reject_reason = $request->input('reason');
                $message = 'Wallet proof rejected successfully.';
            } elseif ($action === 'reapprove') {
                $wallet->is_approved = 3;
                $wallet->reapprove_reason = $request->input('reason');
                $message = 'Wallet proof reapproved successfully.';
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid action provided.'
                ], 400);
            }

            $wallet->save();

            return response()->json([
                'success' => true,
                'message' => $message
            ]);

        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or tampered URL parameters.'
            ], 400);

        } catch (\Exception $e) {
            Log::error('Wallet Proof Action Error', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while processing the action!'
            ], 500);
        }
    }
}
