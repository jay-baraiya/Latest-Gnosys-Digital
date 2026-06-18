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
        Schema::create('digital_products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->string('sku');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->longText('description')->nullable();
            $table->text('short_description')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->decimal('price_for_sale', 10, 2)->nullable();
            $table->tinyInteger('on_sale')->default(0);
            $table->string('badge')->nullable();
            $table->text('image')->nullable();
            $table->text('project')->nullable();
            $table->text('tags')->nullable();
            $table->tinyInteger('sort_order')->default(0);
            $table->tinyInteger('status')->default(1);

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('digital_products');
    }
};
