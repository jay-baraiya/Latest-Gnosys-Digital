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
        Schema::create('events', function (Blueprint $table) {
            $table->id();

            $table->enum('event_type', ['single', 'series']);

            $table->string('series_id')
                ->nullable();
            $table->integer('series_edition')->nullable();

            $table->string('title');
            $table->string('slug')->unique();

            $table->text('description')->nullable();
            $table->string('banner_image')->nullable();

            $table->enum('event_mode', ['online', 'offline', 'hybrid']);

            $table->string('location')->nullable();
            $table->string('event_link', 500)->nullable();

            $table->dateTime('start_date');
            $table->dateTime('end_date');

            $table->string('timezone')->default('UTC');

            $table->boolean('is_free')->default(true);
            $table->decimal('price', 10, 2)->nullable();
            $table->string('currency', 3)->default('USD');

            $table->integer('capacity')->nullable();
            $table->integer('current_registrations')->default(0);

            $table->boolean('waitlist_enabled')->default(false);

            $table->enum('status', [
                'draft',
                'published',
                'ongoing',
                'ended',
                'cancelled',
            ])->default('draft');

            $table->json('registration_form_schema')->nullable();
            $table->json('feedback_form_schema')->nullable();

            $table->string('recording_url', 500)->nullable();
            $table->string('slides_url', 500)->nullable();

            $table->boolean('certificate_enabled')->default(false);
            $table->json('certificate_template')->nullable();

            $table->timestamps();

            $table->unique(['series_id', 'series_edition']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
