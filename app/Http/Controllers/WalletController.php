<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Models\WalletHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class WalletController extends Controller
{
    protected $authUser = null;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->authUser = auth()->user();
            return $next($request);
        });
    }

    public function store(Request $request)
    {

        $currentDate = Carbon::now();
        try {

            $validated = $request->validate([
                'amount' => [
                    'required',
                    'numeric',
                    'min:1',
                    'max:50000'
                ]
            ], [
                'amount.required' => 'Please enter wallet amount.',
                'amount.numeric'  => 'Amount must be a valid number.',
                'amount.min'      => 'Minimum add balance amount is $1.',
                'amount.max'      => 'Maximum add balance amount is $50,000.'
            ]);

            DB::beginTransaction();

            $imagePath = null;

            $approved = null;

            if ($request->hasFile('payment_proof')) {

                $request->validate([
                    'payment_proof' => 'required|file|mimes:jpeg,jpg,png,webp,pdf,doc,docx|max:5120',
                ], [
                    'payment_proof.file'  => 'The uploaded file is invalid.',
                    'payment_proof.mimes' => 'Only JPEG, JPG, PNG, WEBP, PDF, DOC, and DOCX files are allowed.',
                    'payment_proof.max'   => 'The file size must not exceed 5 MB.',
                ]);

                $path = $request->file('payment_proof')->store('wallet-proof', 'public');

                $imagePath = Storage::url($path);


                $approved = 0;
            }

            $wallet = Wallet::firstOrNew([
                'user_id' => $this->authUser->id
            ]);

            if ($wallet->is_approved == 1) {
                $approved = 1;
            }

            if ($wallet->is_approved == 2) {
                return redirect()->back()
                    ->withFragment('wallet')
                    ->with('error', 'Your wallet balance request has been rejected by the administrator. Therefore, you are not allowed to add balance to your wallet at this time.');
            }

            $oldBalance = $wallet->balance ?? 0;

            $wallet->date = $currentDate;
            $wallet->user_id = $this->authUser->id;
            $wallet->balance = $oldBalance + $request->amount;
            $wallet->proof = $imagePath;
            $wallet->transaction_id = $request->transaction_id;
            $wallet->is_approved = $approved;
            $wallet->save();

            $newBalance = $wallet->balance;

            $walletHistory = new WalletHistory();
            $walletHistory->date = $currentDate;
            $walletHistory->wallet_id = $wallet->id;
            $walletHistory->user_id = $wallet->user_id;
            $walletHistory->type = 'credit';
            $walletHistory->balance_before = $oldBalance;
            $walletHistory->transfer_amount = $request->amount;
            $walletHistory->balance_after = $newBalance;
            $walletHistory->status = 'success';
            $walletHistory->save();

            DB::commit();

            return redirect()->back()->withFragment('wallet')->with('success', 'Wallet credit added successfully.');

        } catch (\Illuminate\Validation\ValidationException $e) {

            Log::error($e);

            return redirect()->back()->with('error', 'somthing went wrong!.');

        } catch (\Exception $e) {

            DB::rollBack();

            Log::error($e);

            return redirect()->back()->with('error', 'somthing went wrong!.');
        }
    }

}
