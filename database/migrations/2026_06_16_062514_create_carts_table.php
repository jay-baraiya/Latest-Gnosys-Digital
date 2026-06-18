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
        Schema::create('carts', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('guest_email')->nullable();
            $table->unsignedBigInteger('variant_id')->nullable();
            $table->string('variant_name')->nullable();

            $table->string('product_id');

            $table->string('product_type')->default('product');
            $table->string('product_title');
            $table->string('product_img')->nullable();
            $table->decimal('product_price', 12, 2);

            $table->integer('product_qty')->default(1);

            $table->decimal('total_amount', 12, 2)->nullable();

            $table->timestamps();

            $table->foreign('variant_id')->references('id')->on('service_variants')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
