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
        Schema::create('neumaticos', function (Blueprint $table) {
            $table->id();
            $table->string('CODIGOOT');
            $table->string('CODIGOM');
            $table->string('TALLER');
            $table->foreignId('user_id')->references('id')->on('users');
            $table->foreignId('neumatico_anterior')->nullable()->references('id')->on('neumaticos');
            $table->string('OBSERVACIONES')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('neumaticos');
    }
};
