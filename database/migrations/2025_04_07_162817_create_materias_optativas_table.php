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
//         //
// id
// nombreMateria
// horasTeoria
// horasPractica
// creditos
// claveMateria
// claveCacei
        Schema::create('materias_optativas', function (Blueprint $table) {
            $table->id();
            $table->string('nombreMateria', 255);
            $table->integer('horasTeoria');
            $table->integer('horasPractica');
            $table->integer('creditos');
            $table->string('claveMateria', 50)->unique();
            $table->string('claveCacei', 50);

            $table->foreignId('cve_carrera')->constrained('carreras', 'cve_carrera')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('_materias_optativas');
    }
};
