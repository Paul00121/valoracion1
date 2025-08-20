<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('recuperacion', function (Blueprint $table) {
            $table->id();
            $table->string('correo', 100);
            $table->string('codigo', 6);
            $table->string('token', 64);
            $table->dateTime('expiracion');
            $table->boolean('usado')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recuperacion');
    }
};