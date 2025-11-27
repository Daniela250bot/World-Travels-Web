<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Administrador;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear administrador en la tabla administradores
        $admin = Administrador::create([
            'nombre' => 'Danis',
            'apellido' => 'Valencia',
            'correo_electronico' => 'danis16val@gmail.com',
            'telefono' => '123456789',
            'documento' => '123456789',
            'contraseña' => Hash::make('123456789'),
            'codigo_verificacion' => Administrador::generarCodigoVerificacion(),
        ]);

        // Crear usuario en la tabla users
        User::create([
            'name' => 'Danis Valencia',
            'email' => 'danis16val@gmail.com',
            'password' => Hash::make('123456789'),
            'role' => 'administrador',
            'userable_type' => Administrador::class,
            'userable_id' => $admin->id,
            'codigo_verificacion' => $admin->codigo_verificacion,
            'verificado' => true, // Para que pueda loguearse
        ]);
    }
}
