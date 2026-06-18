<section class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 sm:p-8" x-data="{ paymentMethod: 'paypal' }">

<header class="mb-8 border-b border-slate-100 pb-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h2 class="text-xl font-semibold text-slate-900">{{ __('Wallet & Billing') }}</h2>
        <p class="mt-1 text-sm text-slate-500">{{ __('Manage your funds and top up your account balance.') }}</p>
    </div>
    <div class="bg-blue-50 border border-blue-100 rounded-xl px-4 py-3 text-right">
        <p class="text-xs font-semibold text-blue-600 uppercase tracking-wider mb-0.5">Available Balance</p>
        <p class="text-2xl font-bold text-slate-900">${{ number_format(auth()->user()?->balance?->balance, 2) }}</p>
    </div>
</header>

<div class="mt-3 mb-4" id="wallet-status-container">
    @php
        $status = auth()->user()?->balance?->is_approved;
    @endphp

    @if ($status === 0)
        {{-- Pending --}}
        <div class="d-flex align-items-center p-3 rounded" style="background-color: #fff8e1; border: 1px solid #ffeeba; border-left: 5px solid #ffc107;">
            <i class="ti ti-clock text-warning me-3" style="font-size: 28px;"></i>
            <div>
                <h6 class="mb-1 fw-bold text-warning" style="margin-top: 0;">Pending Approval</h6>
                <p class="mb-0 text-muted" style="font-size: 14px;">Your proof has been submitted and is currently waiting for admin approval.</p>
            </div>
        </div>

    @elseif ($status === 1)
        {{-- Approved --}}
        <div class="d-flex align-items-center p-3 rounded" style="background-color: #e8f5e9; border: 1px solid #c3e6cb; border-left: 5px solid #28a745;">
            <i class="ti ti-check text-success me-3" style="font-size: 28px;"></i>
            <div>
                <h6 class="mb-1 fw-bold text-success" style="margin-top: 0;">Proof Approved</h6>
                <p class="mb-0 text-muted" style="font-size: 14px;">Great news! Your proof has been verified and your wallet is ready.</p>
            </div>
        </div>

    @elseif ($status === 2)
        {{-- Rejected --}}
        <div class="d-flex align-items-center p-3 rounded" style="background-color: #ffebee; border: 1px solid #f5c6cb; border-left: 5px solid #dc3545;">
            <i class="ti ti-x text-danger me-3" style="font-size: 28px;"></i>
            <div>
                <h6 class="mb-1 fw-bold text-danger" style="margin-top: 0;">Proof Rejected</h6>
                <p class="mb-0 text-muted" style="font-size: 14px;">Your proof was rejected by the admin. Please upload a valid document.</p>
            </div>
        </div>

    @else
        {{-- No Record --}}
        <div class="d-flex align-items-center p-3 rounded" style="background-color: #f8f9fa; border: 1px solid #d6d8db; border-left: 5px solid #6c757d;">
            <i class="ti ti-info-circle text-secondary me-3" style="font-size: 28px;"></i>
            <div>
                <h6 class="mb-1 fw-bold text-secondary" style="margin-top: 0;">No Proof Submitted</h6>
                <p class="mb-0 text-muted" style="font-size: 14px;">You have not submitted a proof yet. Please upload your document.</p>
            </div>
        </div>
    @endif
</div>

<h3 class="text-lg font-medium text-slate-900 mb-4">Add Funds</h3>

<div class="grid grid-cols-2 gap-3 mb-6">
    <button type="button" @click="paymentMethod = 'paypal'" :class="{ 'ring-2 ring-blue-600 bg-blue-50 border-blue-200': paymentMethod === 'paypal', 'border-slate-200 hover:bg-slate-50 text-slate-600': paymentMethod !== 'paypal' }" class="flex flex-col items-center justify-center p-4 rounded-xl border transition-all">
        <svg class="w-6 h-6 mb-2" viewBox="0 0 24 24" fill="currentColor" :class="paymentMethod === 'paypal' ? 'text-blue-600' : 'text-slate-400'">
            <path d="M7.076 21.337H2.47a.641.641 0 0 1-.633-.74L4.944.901C5.026.382 5.474 0 5.998 0h7.46c2.57 0 4.578.543 5.69 1.81 1.01 1.15 1.304 2.42 1.012 4.287-.023.143-.047.288-.077.437-.983 5.05-4.349 6.797-8.647 6.797h-2.11c-.473 0-.867.379-.953.868l-.797 4.32-.195 1.146-.037.195a1.144 1.144 0 0 1-1.127.943h-2.15zM22.132 8.783c-.027-.087-.058-.172-.091-.255-.084-.214-.183-.418-.297-.611-.59-1.006-1.558-1.57-2.903-1.696-.285-.027-.585-.043-.9-.043H12.63l-1.645 8.905h2.89c3.642 0 6.495-1.48 7.322-5.74.053-.274.093-.549.123-.827.025-.23.038-.461.038-.692 0-.361-.03-.708-.088-1.041z"/>
        </svg>
        <span class="text-sm font-semibold" :class="paymentMethod === 'paypal' ? 'text-blue-800' : ''">PayPal</span>
    </button>

    <button type="button" @click="paymentMethod = 'bank'" :class="{ 'ring-2 ring-blue-600 bg-blue-50 border-blue-200': paymentMethod === 'bank', 'border-slate-200 hover:bg-slate-50 text-slate-600': paymentMethod !== 'bank' }" class="flex flex-col items-center justify-center p-4 rounded-xl border transition-all">
        <svg class="w-6 h-6 mb-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" :class="paymentMethod === 'bank' ? 'text-blue-600' : 'text-slate-400'">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" />
        </svg>
        <span class="text-sm font-semibold" :class="paymentMethod === 'bank' ? 'text-blue-800' : ''">Bank Transfer</span>
    </button>
</div>

<form action="{{ route('wallet.store') }}" method="POST" x-show="paymentMethod === 'paypal'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
    @csrf
    <div class="mb-5 max-w-sm">
        <label for="paypal_amount" class="block text-sm font-medium text-slate-700 mb-1">Amount to Add (USD)</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                <span class="text-slate-500 font-medium">$</span>
            </div>
            <input type="number" name="amount" id="paypal_amount" min="1" step="0.01" required
                class="block w-full pl-8 pr-4 py-2.5 rounded-lg border-slate-300 text-slate-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm transition-all"
                placeholder="0.00">
        </div>
    </div>

    <div class="p-4 bg-slate-50 rounded-lg border border-slate-200 mb-6 flex items-start gap-3">
        <svg class="w-5 h-5 text-slate-400 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" /></svg>
        <p class="text-sm text-slate-600">You will be redirected securely to PayPal to complete your transaction. Funds will be added instantly upon success.</p>
    </div>

    <div class="flex items-center gap-3">
        <button type="submit" class="inline-flex justify-center rounded-lg bg-blue-600 py-2.5 px-5 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition-colors">
            Proceed to PayPal
        </button>
    </div>
</form>

<form action="{{ route('wallet.store') }}" method="POST" enctype="multipart/form-data" x-show="paymentMethod === 'bank'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
    @csrf

    <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 mb-6">
        <h4 class="text-sm font-semibold text-slate-900 mb-2">Our Bank Details</h4>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-2 gap-x-4 text-sm">
            <div><span class="text-slate-500">Bank Name:</span> <span class="font-medium text-slate-900">Global Tech Bank</span></div>
            <div><span class="text-slate-500">Account Name:</span> <span class="font-medium text-slate-900">Gnosys Digital LLC</span></div>
            <div><span class="text-slate-500">Account No:</span> <span class="font-medium text-slate-900 tracking-wider">1234 5678 9012</span></div>
            <div><span class="text-slate-500">Routing / SWIFT:</span> <span class="font-medium text-slate-900">GTBUS33</span></div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
        <div>
            <label for="bank_amount" class="block text-sm font-medium text-slate-700 mb-1">Amount Sent (USD)</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <span class="text-slate-500 font-medium">$</span>
                </div>
                <input type="number" name="amount" id="bank_amount" min="1" step="0.01" required
                    class="block w-full pl-8 pr-4 py-2.5 rounded-lg border-slate-300 text-slate-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm transition-all"
                    placeholder="0.00">
            </div>
        </div>
        <div>
            <label for="transaction_id" class="block text-sm font-medium text-slate-700 mb-1">Transaction ID / Reference</label>
            <input type="text" name="transaction_id" id="transaction_id" required
                class="block w-full rounded-lg border-slate-300 py-2.5 px-3 text-slate-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm transition-all"
                placeholder="e.g. TXN-987654321">
        </div>
    </div>

    <div class="mb-6">
        <label class="block text-sm font-medium text-slate-700 mb-1">Upload Payment Proof</label>
        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-300 border-dashed rounded-xl hover:border-blue-400 transition-colors bg-white">
            <div class="space-y-1 text-center">
                <svg class="mx-auto h-12 w-12 text-slate-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <div class="flex text-sm text-slate-600 justify-center">
                    <label for="payment_proof" class="relative cursor-pointer rounded-md bg-white font-semibold text-blue-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-blue-600 focus-within:ring-offset-2 hover:text-blue-500">
                        <span>Upload a file</span>
                        <input id="payment_proof" name="payment_proof" type="file" class="sr-only" accept="image/png, image/jpeg, application/pdf" required>
                    </label>
                    <p class="pl-1">or drag and drop</p>
                </div>
                <p class="text-xs text-slate-500">PNG, JPG, PDF up to 5MB</p>
            </div>
        </div>
    </div>

    <div class="flex items-center gap-3">
        <button type="submit" class="inline-flex justify-center rounded-lg bg-blue-600 py-2.5 px-5 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition-colors">
            Submit for Verification
        </button>
    </div>
</form>
</section>
