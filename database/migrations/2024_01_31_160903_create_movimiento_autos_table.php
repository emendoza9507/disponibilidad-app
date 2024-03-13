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
        Schema::create('movimiento_autos', function (Blueprint $table) {
            $table->id();
            $table->string('matricula');
            $table->unsignedBigInteger('origen');
            $table->unsignedBigInteger('destino');

            $table->unsignedBigInteger('enviado_por');
            $table->unsignedBigInteger('recivido_por')->nullable();

            $table->dateTime('fecha_salida');
            $table->dateTime('fecha_llegada_estimada')->nullable();
            $table->dateTime('fecha_llegada')->nullabele();

            $table->string('descripcion');

            $table->timestamps();

            $table->foreign('matricula')->references('matricula')->on('autos');
            $table->foreign('origen')->references('id')->on('ubicacions');
            $table->foreign('destino')->references('id')->on('ubicacions');
            $table->foreign('enviado_por')->references('id')->on('users');
            $table->foreign('recivido_por')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimiento_autos');
    }
};
