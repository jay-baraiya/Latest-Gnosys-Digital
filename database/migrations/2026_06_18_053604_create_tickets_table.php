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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique();
            $table->dateTime('datetime')->nullable();
            $table->foreignId('order_id')->nullable()->constrained('orders')->onDelete('set null');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('developer_id')->nullable()->constrained('users')->onDelete('set null');

            $table->string('order_item_id')->nullable();

            $table->enum('status', [
                'pending',
                'assign_requested',
                'assigned',
                'assign_not_accepted',
                'in_progress',
                'completed',
                'cancel_requested',
                'cancelled',
                'refund'
            ])->default('pending');

            $table->foreignId('cancelled_by')->nullable()->constrained('users')->onDelete('set null');

            $table->text('assign_not_accepted_reason')->nullable();
            $table->text('cancel_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
