<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\PayPalController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    // Route::get('/paypal/checkout', [PayPalController::class, 'checkout'])->name('paypal.checkout');
    // Route::get('/paypal/success', [PayPalController::class, 'success'])->name('paypal.success');
    // Route::get('/paypal/cancel', [PayPalController::class, 'cancel'])->name('paypal.cancel');

    Route::get('/paypal/success', [OrderController::class, 'paypalSuccess'])->name('paypal.success');
    Route::get('/paypal/cancel', [OrderController::class, 'paypalCancel'])->name('paypal.cancel');
});
