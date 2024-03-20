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
        Schema::create('baterias', function (Blueprint $table) {
            $table->id();
            $table->string('CODIGOOT');
            $table->string('CODIGOM');
            $table->string('TALLER');
            $table->foreignId('connection_id')->references('id')->on('connections');
            $table->foreignId('user_id')->references('id')->on('users');
            $table->foreignId('bateria_anterior')->nullable()->references('id')->on('baterias');

            $table->string('cons_manual')->unique()->nullable();

            $table->string('OBSERVACIONES')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('baterias');
    }
};
