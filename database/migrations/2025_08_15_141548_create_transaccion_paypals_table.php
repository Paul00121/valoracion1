<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transacciones_paypal', function (Blueprint $table) {
            $table->id('idtransaccion');
            $table->foreignId('idusuario')->constrained('usuarios');
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

    public function down(): void
    {
        Schema::dropIfExists('transacciones_paypal');
    }
};