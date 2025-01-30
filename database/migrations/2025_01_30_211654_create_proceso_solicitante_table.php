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
        Schema::create('proceso_solicitante', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_proceso')->unsigned();
            $table->date('fecha_entrega');
            $table->string('documento',100);
            $table->string('nombre_documento',300)->nullable();
            $table->string('folio', 50)->nullable();

            // ES PARA AGREGAR UNA PAGINA EN BLANCO AL PDF GENERADO Y COLOCAR EN MEDIO ESTE TEXTO
            $table->string('texto_documento',100)->nullable();

            $table->foreign('id_proceso')->references('id')->on('proceso');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proceso_solicitante');
    }
};
