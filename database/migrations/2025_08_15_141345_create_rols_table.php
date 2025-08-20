<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id('idr');
            $table->string('nombre', 50)->unique();
            $table->timestamps();
        });

        DB::table('roles')->insert([
            ['nombre' => 'Administrador'],
            ['nombre' => 'Invitado']
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};