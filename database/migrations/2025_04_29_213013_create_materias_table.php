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
        Schema::create('materias', function (Blueprint $table) {
            $table->id();
            $table->string('nombreMateria');
            $table->integer('horasTeoria');
            $table->integer('horasPractica');
            $table->integer('creditos');
            $table->string('claveMateria')->unique();
            $table->string('claveCacei')->nullable();
            $table->string('tipoMateria')->default('carrera');
            $table->unsignedBigInteger('cve_Carrera');
            $table->unsignedBigInteger('grupo_id')->nullable();
            $table->timestamps();

            $table->foreign('cve_Carrera')->references('cve_carrera')->on('carreras')->onDelete('cascade');
            $table->foreign('grupo_id')->references('id')->on('grupos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materias');
    }
};
