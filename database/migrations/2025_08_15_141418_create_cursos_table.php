<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cursos', function (Blueprint $table) {
            $table->id('idcurso');
            $table->string('titulo', 255);
            $table->text('descripcion')->nullable();
            $table->decimal('precio', 10, 2);
            $table->foreignId('id_profesor')->constrained('usuarios');
            $table->foreignId('id_categoria')->nullable()->constrained('categorias');
            $table->decimal('puntuacion', 3, 2)->default(0);
            $table->boolean('es_gratis')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cursos');
    }
};