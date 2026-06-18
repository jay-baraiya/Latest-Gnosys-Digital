<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('wallet_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('wallet_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->datetime('date');
            $table->enum('type', ['credit','debit','refund','withdraw','purchase']);
            $table->decimal('balance_before', 12, 2)->default(0);
            $table->decimal('transfer_amount', 12, 2)->default(0);
            $table->decimal('balance_after', 12, 2)->default(0);
            $table->string('payment_method')->nullable()->comment('Payment method like PayPal, Stripe, Razorpay');
            $table->string('transaction_id')->nullable()->comment('Gateway transaction ID');
            $table->string('currency')->default('USD');
            $table->longText('gateway_response')->nullable();
            $table->enum('status', ['pending','processing','success','failed','cancelled'])->default('pending');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('wallet_id')->references('id')->on('wallets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_histories');
    }
};
