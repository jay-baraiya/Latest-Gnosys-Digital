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

            $table->string('email')->nullable();
            $table->string('name')->nullable();
            $table->string('subject')->nullable();

            $table->string('cc_recipients')->nullable();

            $table->foreignId('department_id')->nullable()->constrained('roles')->onDelete('set null');

            $table->foreignId('assign_id')->nullable()->constrained('users')->onDelete('set null');

            $table->string('priority')->nullable();

            $table->enum('status', [
                'pending',
                'in_progress',
                'assigned',
                'completed',
                'closed'
            ])->default('pending');

            $table->foreignId('closed_by')->nullable()->constrained('users')->onDelete('set null');

            $table->longText('description')->nullable();
            $table->longText('attachments')->nullable();
            $table->longText('file')->nullable();
            $table->longText('note')->nullable();

            $table->text('close_reason')->nullable();
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
