<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('compras', function (Blueprint $table) {
            $table->id('idcompra');
            $table->foreignId('idusuario')->constrained('usuarios');
            $table->foreignId('idcurso')->constrained('cursos');
            $table->dateTime('fecha')->useCurrent();
            $table->decimal('monto_pagado', 10, 2);
            $table->decimal('descuento_aplicado', 10, 2)->default(0);
            $table->decimal('comision_plataforma', 10, 2);
            $table->decimal('pago_profesor', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('compras');
    }
};