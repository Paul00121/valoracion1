<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sesiones_activas', function (Blueprint $table) {
            $table->id('ids');
            $table->foreignId('idu')->constrained('usuarios');
            $table->string('session_id', 255);
            $table->dateTime('fecha_inicio')->useCurrent();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sesiones_activas');
    }
};