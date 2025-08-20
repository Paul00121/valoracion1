<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id('idu');
            $table->string('nombre', 100);
            $table->string('apellidos', 100);
            $table->string('correo', 100)->unique();
            $table->string('passwd');
            $table->foreignId('idr')->constrained('roles');
            $table->foreignId('idp')->nullable()->constrained('planes');
            $table->boolean('verificado')->default(false);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};