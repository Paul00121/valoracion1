<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reseñas', function (Blueprint $table) {
            $table->id('idreseña');
            $table->foreignId('idusuario')->constrained('usuarios');
            $table->foreignId('idcurso')->constrained('cursos');
            $table->tinyInteger('puntuacion')->unsigned();
            $table->text('comentario')->nullable();
            $table->dateTime('fecha')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reseñas');
    }
};