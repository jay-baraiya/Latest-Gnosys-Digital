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
        Schema::create('event_waitlists', function (Blueprint $table) {
            $table->id(); // SERIAL PRIMARY KEY
            
            // Foreign Key constraint
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            
            $table->string('email'); // VARCHAR(255) NOT NULL
            $table->json('form_data')->nullable(); // JSON (nullable because NOT NULL is not specified)
            
            // Timestamps
            $table->timestamp('created_at')->useCurrent(); // DEFAULT CURRENT_TIMESTAMP
            $table->timestamp('notified_at')->nullable(); 
            
            // Unique Constraint
            $table->unique(['event_id', 'email'], 'event_waitlist_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_waitlists');
    }
};
