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
        Schema::table('settings', function (Blueprint $table) {
            $table->string('paypal_mode')->default('sandbox')->nullable();
            $table->string('paypal_client_id')->nullable();
            $table->string('paypal_client_secret')->nullable();
            $table->string('paypal_currency')->default('USD')->nullable();
            $table->string('paypal_webhook_url')->nullable();
            $table->string('paypal_return_url')->nullable();
            $table->string('paypal_cancel_url')->nullable();
            $table->boolean('paypal_enable_for_credits')->default(0)->nullable();
            $table->boolean('paypal_enable_for_events')->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'paypal_mode',
                'paypal_client_id',
                'paypal_client_secret',
                'paypal_currency',
                'paypal_webhook_url',
                'paypal_return_url',
                'paypal_cancel_url',
                'paypal_enable_for_credits',
                'paypal_enable_for_events',
            ]);
        });
    }
};
