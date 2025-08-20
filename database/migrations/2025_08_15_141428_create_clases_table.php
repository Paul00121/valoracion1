<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('clases', function (Blueprint $table) {
            $table->id('idclase');
            $table->foreignId('idcurso')->constrained('cursos');
            $table->string('titulo', 255);
            $table->enum('tipo', ['video', 'audio', 'texto']);
            $table->text('contenido');
            $table->integer('duracion')->nullable();
            $table->integer('orden');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clases');
    }
};