<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * "rpe","nombre","cve_carrera","rol"
     */
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('rpe', 50)->unique();
            $table->string('nombre', 255);
            $table->foreignId('cve_carrera')->constrained('carreras', 'cve_carrera')->onDelete('cascade');
            $table->integer('rol');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
