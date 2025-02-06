<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * BASE DE PROCESOS
     */
    public function up(): void
    {
        Schema::create('proceso', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('id_fuente')->unsigned();
            $table->string('numero_proceso', 100);
            $table->string('nombre_proyecto', 300);
            $table->string('codigo_proyecto', 100)->nullable();
            $table->string('numero_expediente', 50)->nullable();
            $table->string('ampo', 50)->nullable();
            $table->string('nombre_proceso', 300)->nullable();

            // Se setea cuando haya registros finales
            $table->boolean('consolidado');

            $table->foreign('id_fuente')->references('id')->on('fuente');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proceso');
    }
};
