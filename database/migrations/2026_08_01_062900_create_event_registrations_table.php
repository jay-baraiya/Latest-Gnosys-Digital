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
        Schema::create('event_registrations', function (Blueprint $table) {
            $table->id();
            // Foreign Keys
            $table->foreignId('event_id')->nullable()->constrained('events')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained('orders')->onDelete('set null');
            
            $table->string('email'); // VARCHAR(255) NOT NULL
            $table->longText('form_data')->nullable(); // JSON
            
            // Enums for CHECK constraints
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])
                  ->default('pending');
                  
            $table->enum('attendee_status', ['registered', 'checked_in', 'no_show', 'cancelled'])
                  ->default('registered');
            
            // Timestamps and booleans
            $table->timestamp('checked_in_at')->nullable();
            $table->boolean('feedback_submitted')->default(false);
            $table->timestamp('feedback_submitted_at')->nullable();
            
            $table->boolean('certificate_generated')->default(false);
            $table->string('certificate_url', 500)->nullable(); // VARCHAR(500)
            
            // created_at & updated_at
            $table->timestamps(); 
            
            // Unique Constraint
            $table->unique(['event_id', 'user_id', 'email'], 'event_user_email_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_registrations');
    }
};
