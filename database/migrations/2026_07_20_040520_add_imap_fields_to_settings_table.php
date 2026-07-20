<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('imap_host')->nullable();
            $table->string('imap_protocol')->nullable();
            $table->string('imap_username')->nullable();
            $table->string('imap_password')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'imap_host',
                'imap_protocol',
                'imap_username',
                'imap_password',
            ]);
        });
    }
};
