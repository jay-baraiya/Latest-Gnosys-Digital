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
        Schema::table('wallets', function (Blueprint $table) {
            $table->string('proof')->nullable()->after('balance');
            $table->string('transaction_id')->nullable()->after('proof');
            $table->tinyInteger('is_approved')->nullable()->after('transaction_id')->comment('0=pending,1=approve, 2=reject, 3=reapprove');
            $table->text('reject_reason')->nullable()->after('is_approved');
            $table->text('reapprove_reason')->nullable()->after('reject_reason');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wallets', function (Blueprint $table) {
            $table->dropColumn(['proof','transaction_id','is_approved','reject_reason','reapprove_reason']);
        });
    }
};
