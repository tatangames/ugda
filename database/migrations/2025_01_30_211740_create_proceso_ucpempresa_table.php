<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * PUEDE TENER MUCHAS EMPRESAS A UN PROCESO
     */
    public function up(): void
    {
        Schema::create('proceso_ucpempresa', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_procesoucp')->unsigned();
            $table->bigInteger('id_empresa')->unsigned();

            $table->foreign('id_procesoucp')->references('id')->on('proceso_ucp');
            $table->foreign('id_empresa')->references('id')->on('empresa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proceso_ucpempresa');
    }
};
