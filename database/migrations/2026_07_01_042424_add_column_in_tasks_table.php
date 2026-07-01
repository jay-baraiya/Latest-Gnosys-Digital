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
        Schema::table('tasks', function (Blueprint $table) {
            $table->foreignId('department_id')->nullable()->after('ticket_id')->constrained('roles')->onDelete('set null');
            $table->foreignId('assign_id')->nullable()->after('department_id')->constrained('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropForeign(['assign_id']);

            $table->dropColumn(['department_id', 'assign_id']);
        });
    }
};
