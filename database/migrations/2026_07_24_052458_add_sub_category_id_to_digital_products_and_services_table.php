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
        Schema::table('digital_products', function (Blueprint $table) {
            $table->unsignedBigInteger('sub_category_id')->nullable()->after('category_id');
        });
        
        Schema::table('digital_services', function (Blueprint $table) {
            $table->unsignedBigInteger('sub_category_id')->nullable()->after('category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('digital_products', function (Blueprint $table) {
            $table->dropColumn('sub_category_id');
        });
        
        Schema::table('digital_services', function (Blueprint $table) {
            $table->dropColumn('sub_category_id');
        });
    }
};
