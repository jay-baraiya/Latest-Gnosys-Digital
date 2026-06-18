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
        Schema::create('custom_fields', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('custom_field_type_id')->nullable();
            $table->string('recode_id')->nullable();
            $table->string('name');
            $table->string('slug');
            $table->string('type')->nullable();
            $table->enum('module_type', ['product', 'service']);
            $table->longText('params')->nullable();
            $table->longText('options')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();

            $table->foreign('custom_field_type_id')->on('custom_field_types')->references('id')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_fields');
    }
};
