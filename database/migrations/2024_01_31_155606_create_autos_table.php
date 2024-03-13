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
        Schema::create('autos', function (Blueprint $table) {
            $table->string('matricula', 7)->primary();
            $table->string('codigodp');
            $table->string('modelo');
            $table->string('qr_code_path')->nullabel();

            $table->timestamps();

            $table->foreign('codigodp')->references('codigodp')->on('departamentos');
            $table->foreign('modelo')->references('nombre')->on('modelo_autos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('autos');
    }
};
