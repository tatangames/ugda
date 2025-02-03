<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * DIFERENTES FUENTES COMO
     * COMPARACION PRECIO SERVICIOS
     */
    public function up(): void
    {
        Schema::create('fuente', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_anio')->unsigned();

            $table->string('nombre', 100);
            // Para ocultar al crear proceso, asi solo saldra del año actual
            $table->boolean('visible');

            $table->foreign('id_anio')->references('id')->on('anio');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fuente');
    }
};
