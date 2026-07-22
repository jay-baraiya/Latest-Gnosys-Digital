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
        Schema::create('email_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->string('host')->nullable();
            $table->string('port')->nullable();
            $table->string('protocol')->default('imap');
            $table->string('encryption')->nullable();
            $table->tinyInteger('status')->default(1)->comment('1:Active, 0:Inactive');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_accounts');
    }
};
