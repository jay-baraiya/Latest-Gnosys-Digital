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
        // 1. Pehla check karo ke old 'department_id' column exist kare chhe ke nahi
        if (Schema::hasColumn('tickets', 'department_id')) {
            Schema::table('tickets', function (Blueprint $table) {
                // Try-catch ma old foreign key drop karo (jethi error na aave jo key already dropped hoy)
                try {
                    $table->dropForeign(['department_id']);
                } catch (\Exception $e) {
                    // Ignore error if foreign key does not exist
                }

                // Old column drop karo jethi niche fari thi properly create kari shakay
                $table->dropColumn('department_id');
            });
        }

        // 2. Have navi column ane relation properly add karo
        Schema::table('tickets', function (Blueprint $table) {
            $table->foreignId('department_id')->nullable()->constrained('departments')->onDelete('set null');
            $table->string('ticket_notice')->nullable();
            $table->string('ticket_source')->nullable();
            $table->string('help_topic')->nullable();
            $table->string('sla_plan')->nullable();
            $table->string('canned_response')->nullable();
            $table->string('signature_option')->nullable();
            $table->longText('internal_note')->nullable();
            $table->string('ticket_status')->nullable();
            $table->date('due_date')->nullable();
            $table->string('internal_note_title')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            // Down method ma pan safe drop rakhvu saru
            try {
                $table->dropForeign(['department_id']);
            } catch (\Exception $e) {
                // Ignore
            }

            $table->dropColumn([
                'department_id',
                'ticket_notice',
                'ticket_source',
                'help_topic',
                'sla_plan',
                'canned_response',
                'signature_option',
                'internal_note',
                'due_date'
            ]);
        });
    }
};
