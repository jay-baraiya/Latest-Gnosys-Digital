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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('guest_email')->nullable();
            $table->string('order_number')->unique();

            $table->string('billing_address')->nullable();
            $table->string('billing_first_name')->nullable();
            $table->string('billing_country')->nullable();
            $table->string('billing_state')->nullable();
            $table->string('billing_city')->nullable();
            $table->string('billing_phone')->nullable();

            $table->string('date_time')->nullable();

            $table->decimal('subtotal', 10, 2);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2);

            $table->string('status')->default('pending')->comment('e.g., pending, processing, shipped, delivered, cancelled');
            $table->string('payment_method')->nullable()->comment('e.g., paypal, stripe, cod');
            $table->string('payment_status')->default('pending')->comment('e.g., pending, paid, failed, refunded, success');
            $table->string('transaction_id')->nullable();

            $table->longText('getway_response')->nullable();

            $table->text('order_notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
