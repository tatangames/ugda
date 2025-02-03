<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * AÑO DE PROCESO
     */
    public function up(): void
    {
        Schema::create('anio', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 10);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anio');
    }
};
