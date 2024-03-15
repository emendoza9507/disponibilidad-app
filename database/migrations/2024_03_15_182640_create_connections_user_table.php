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
        Schema::create('connection_role_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('connection_id')->references('id')->on('connections');
            $table->foreignId('user_id')->references('id')->on('users');
            $table->foreignId('rol_id')->references('id')->on('roles');

            $table->unique(['connection_id', 'user_id', 'rol_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('connections_user');
    }
};
