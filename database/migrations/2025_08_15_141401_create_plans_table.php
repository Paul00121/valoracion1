<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('planes', function (Blueprint $table) {
            $table->id('idp');
            $table->string('nombre', 50);
            $table->integer('sesiones_permitidas');
            $table->text('descripcion')->nullable();
            $table->decimal('precio', 10, 2)->default(0);
            $table->decimal('descuento_cursos', 5, 2)->default(0);
            $table->timestamps();
        });

        DB::table('planes')->insert([
            [
                'nombre' => 'Básico',
                'sesiones_permitidas' => 1,
                'descripcion' => '1 sesión activa simultánea',
                'precio' => 0,
                'descuento_cursos' => 0
            ],
            [
                'nombre' => 'Premium',
                'sesiones_permitidas' => 3,
                'descripcion' => 'Hasta 3 sesiones activas simultáneas',
                'precio' => 15,
                'descuento_cursos' => 20
            ]
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('planes');
    }
};