<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // ¡Esta línea es la que falta!

class RolesAndPlanesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('roles')->insert([
            ['nombre' => 'Administrador'],
            ['nombre' => 'Invitado']
        ]);

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
}