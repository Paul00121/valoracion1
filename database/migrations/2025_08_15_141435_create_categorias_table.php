<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('categorias', function (Blueprint $table) {
            $table->id('idc');
            $table->string('nombre', 100);
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });

        DB::table('categorias')->insert([
            ['nombre' => 'Programación', 'descripcion' => 'Cursos de lenguajes de programación, frameworks y desarrollo de software'],
            ['nombre' => 'Diseño Web', 'descripcion' => 'Diseño UI/UX, HTML/CSS, Figma, Adobe XD y experiencia de usuario'],
            // Agrega las demás categorías según tu estructura
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('categorias');
    }
};