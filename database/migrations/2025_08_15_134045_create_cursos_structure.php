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
        Schema::create('categorias', function (Blueprint $table) {
            $table->id('idc');
            $table->string('nombre', 100);
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });

        Schema::create('cursos', function (Blueprint $table) {
            $table->id('idcurso');
            $table->string('titulo', 255);
            $table->text('descripcion')->nullable();
            $table->decimal('precio', 10, 2);
            $table->foreignId('id_profesor')->constrained('users');
            $table->foreignId('id_categoria')->nullable()->constrained('categorias');
            $table->decimal('puntuacion', 3, 2)->default(0);
            $table->boolean('es_gratis')->default(false);
            $table->timestamps();
        });

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

        Schema::create('suscripciones', function (Blueprint $table) {
            $table->id('idsuscripcion');
            $table->foreignId('idusuario')->constrained('users');
            $table->foreignId('idplan')->constrained('planes');
            $table->dateTime('fecha_inicio')->useCurrent();
            $table->dateTime('fecha_fin')->nullable();
            $table->enum('estado', ['activa', 'cancelada', 'expirada'])->default('activa');
            $table->timestamps();
        });

        Schema::create('compras', function (Blueprint $table) {
            $table->id('idcompra');
            $table->foreignId('idusuario')->constrained('users');
            $table->foreignId('idcurso')->constrained('cursos');
            $table->dateTime('fecha')->useCurrent();
            $table->decimal('monto_pagado', 10, 2);
            $table->decimal('descuento_aplicado', 10, 2)->default(0);
            $table->decimal('comision_plataforma', 10, 2);
            $table->decimal('pago_profesor', 10, 2);
            $table->timestamps();
        });

        Schema::create('reseñas', function (Blueprint $table) {
            $table->id('idreseña');
            $table->foreignId('idusuario')->constrained('users');
            $table->foreignId('idcurso')->constrained('cursos');
            $table->tinyInteger('puntuacion')->unsigned();
            $table->text('comentario')->nullable();
            $table->dateTime('fecha')->useCurrent();
            $table->timestamps();
        });

        Schema::create('transacciones_paypal', function (Blueprint $table) {
            $table->id('idtransaccion');
            $table->foreignId('idusuario')->constrained('users');
            $table->enum('tipo', ['suscripcion', 'compra_curso']);
            $table->foreignId('idsuscripcion')->nullable()->constrained('suscripciones');
            $table->foreignId('idcompra')->nullable()->constrained('compras');
            $table->string('id_paypal', 255);
            $table->string('estado', 50);
            $table->decimal('monto', 10, 2);
            $table->dateTime('fecha')->useCurrent();
            $table->text('datos_completos')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categorias');
        Schema::dropIfExists('cursos');
        Schema::dropIfExists('suscripciones');
        Schema::dropIfExists('compras');
        Schema::dropIfExists('reseñas');
        Schema::dropIfExists('transacciones_paypal');
    }
};
