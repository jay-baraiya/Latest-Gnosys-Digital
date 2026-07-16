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
        Schema::table('custom_fields', function (Blueprint $table) {
            if (!Schema::hasColumn('custom_fields', 'department_id')) {
                $table->unsignedBigInteger('department_id')->nullable()->after('recode_id');
                $table->foreign('department_id')->on('departments')->references('id')->after('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('custom_fields', function (Blueprint $table) {
            if (Schema::hasColumn('custom_fields', 'department_id') && Schema::hasForeignKey('custom_fields', ['department_id'])) {
                $table->dropForeign(['department_id']);
                $table->dropColumn(['department_id']);
            }
        });
    }
};
