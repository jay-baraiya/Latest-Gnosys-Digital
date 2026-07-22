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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();

            $table->string('code')->unique();
            $table->enum('type', ['percentage', 'fixed'])->default('percentage');
            $table->decimal('value', 10, 2)->nullable();
            $table->enum('applies_to', ['credits', 'events', 'both'])->default('both');
            
            $table->text('service_ids')->nullable(); // store multiple ids in json format
            $table->text('event_ids')->nullable(); // store multiple ids in json format
            
            $table->decimal('min_purchase_amount', 10, 2)->nullable()->default(0.00);
            $table->decimal('max_discount_amount', 10, 2)->nullable();
            $table->integer('usage_limit')->nullable();
            $table->integer('usage_per_user')->nullable()->default(1);
            $table->integer('used_count')->default(0);
            
            $table->dateTime('starts_at')->nullable();
            $table->dateTime('expires_at')->nullable();
            
            $table->tinyInteger('status')->default(1)->comment('1 = active, 0 = inactive');
            
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
