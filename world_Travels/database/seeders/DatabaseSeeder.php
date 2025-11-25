<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Departamentos;
use App\Models\Municipios;
use App\Models\Categorias_Actividades;
use App\Models\Actividades;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        echo "Verificando usuarios existentes...\n";

        // Verificar usuarios en tabla users
        $users = User::all();
        echo "Usuarios en tabla users: " . $users->count() . "\n";
        foreach ($users as $user) {
            echo "- ID: {$user->id}, Email: {$user->email}, Role: " . ($user->role ?? 'NULL') . "\n";
        }

        // Verificar administradores
        $admins = \App\Models\Administrador::all();
        echo "\nAdministradores: " . $admins->count() . "\n";
        foreach ($admins as $admin) {
            echo "- ID: {$admin->id}, Email: {$admin->correo_electronico}, UserID: " . ($admin->user_id ?? 'NULL') . "\n";
        }

        // Verificar empresas
        $empresas = \App\Models\Empresa::all();
        echo "\nEmpresas: " . $empresas->count() . "\n";
        foreach ($empresas as $empresa) {
            echo "- ID: {$empresa->id}, Email: {$empresa->correo}, UserID: " . ($empresa->user_id ?? 'NULL') . "\n";
        }

        // Verificar turistas
        $turistas = \App\Models\Usuarios::all();
        echo "\nTuristas: " . $turistas->count() . "\n";
        foreach ($turistas as $turista) {
            echo "- ID: {$turista->id}, Email: {$turista->Email}, Rol: {$turista->Rol}, UserID: " . ($turista->user_id ?? 'NULL') . "\n";
        }

        echo "\nCreando usuarios de prueba para login...\n";

        // Crear usuario administrador en tabla users
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name' => 'Administrador',
                'password' => bcrypt('password123'),
                'role' => 'administrador',
                'verificado' => true
            ]
        );
        echo "Usuario administrador creado: {$adminUser->email}\n";

        // Crear usuario empresa en tabla users
        $empresaUser = User::firstOrCreate(
            ['email' => 'empresa@test.com'],
            [
                'name' => 'Empresa Test',
                'password' => bcrypt('password123'),
                'role' => 'empresa',
                'verificado' => true
            ]
        );
        echo "Usuario empresa creado: {$empresaUser->email}\n";

        // Crear usuario turista en tabla users
        $turistaUser = User::firstOrCreate(
            ['email' => 'turista@test.com'],
            [
                'name' => 'Turista Test',
                'password' => bcrypt('password123'),
                'role' => 'turista',
                'verificado' => true
            ]
        );
        echo "Usuario turista creado: {$turistaUser->email}\n";

        echo "\nUsuarios disponibles para login:\n";
        echo "- admin@test.com / password123 (Administrador)\n";
        echo "- empresa@test.com / password123 (Empresa)\n";
        echo "- turista@test.com / password123 (Turista)\n";

        // Ejecutar el seeder de permisos
        $this->call(PermisosSeeder::class);

        // Crear departamento de Boyacá
        $boyaca = \App\Models\Departamentos::create([
            'Nombre_Departamento' => 'Boyacá'
        ]);

        // Crear municipios
        $tunja = \App\Models\Municipios::create([
            'Nombre_Municipio' => 'Tunja',
            'idDepartamento' => $boyaca->id
        ]);

        $villaDeLeyva = \App\Models\Municipios::create([
            'Nombre_Municipio' => 'Villa de Leyva',
            'idDepartamento' => $boyaca->id
        ]);

        $sogamoso = \App\Models\Municipios::create([
            'Nombre_Municipio' => 'Sogamoso',
            'idDepartamento' => $boyaca->id
        ]);

        // Crear categorías
        $cultura = \App\Models\Categorias_Actividades::create([
            'Nombre_Categoria' => 'Cultural',
            'Descripcion' => 'Actividades culturales y patrimoniales'
        ]);

        $naturaleza = \App\Models\Categorias_Actividades::create([
            'Nombre_Categoria' => 'Naturaleza',
            'Descripcion' => 'Actividades en contacto con la naturaleza'
        ]);

        $aventura = \App\Models\Categorias_Actividades::create([
            'Nombre_Categoria' => 'Aventura',
            'Descripcion' => 'Actividades de aventura y deportes extremos'
        ]);

        // Crear actividades
        \App\Models\Actividades::create([
            'Nombre_Actividad' => 'Visita al Museo Casa del Fundador',
            'Descripcion' => 'Explora la historia de Colombia en este museo ubicado en el corazón de Tunja.',
            'Fecha_Actividad' => now()->addDays(7)->toDateString(),
            'Hora_Actividad' => '10:00:00',
            'Precio' => 15000,
            'Cupo_Maximo' => 20,
            'Ubicacion' => 'Tunja, Boyacá',
            'Imagen' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80',
            'idCategoria' => $cultura->id,
            'idMunicipio' => $tunja->id,
            'idUsuario' => $empresaUser->id
        ]);

        \App\Models\Actividades::create([
            'Nombre_Actividad' => 'Paseo por Villa de Leyva',
            'Descripcion' => 'Recorre las calles empedradas y plazas coloniales de este pueblo mágico.',
            'Fecha_Actividad' => now()->addDays(10)->toDateString(),
            'Hora_Actividad' => '09:00:00',
            'Precio' => 25000,
            'Cupo_Maximo' => 15,
            'Ubicacion' => 'Villa de Leyva, Boyacá',
            'Imagen' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80',
            'idCategoria' => $cultura->id,
            'idMunicipio' => $villaDeLeyva->id,
            'idUsuario' => $empresaUser->id
        ]);

        \App\Models\Actividades::create([
            'Nombre_Actividad' => 'Baños Termales en Sogamoso',
            'Descripcion' => 'Relájate en las aguas medicinales de los termales de Sogamoso.',
            'Fecha_Actividad' => now()->addDays(5)->toDateString(),
            'Hora_Actividad' => '14:00:00',
            'Precio' => 30000,
            'Cupo_Maximo' => 25,
            'Ubicacion' => 'Sogamoso, Boyacá',
            'Imagen' => 'https://images.unsplash.com/photo-1571003123894-1f0594d2b5d9?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80',
            'idCategoria' => $naturaleza->id,
            'idMunicipio' => $sogamoso->id,
            'idUsuario' => $empresaUser->id
        ]);

        \App\Models\Actividades::create([
            'Nombre_Actividad' => 'Senderismo en el Parque Nacional Pisba',
            'Descripcion' => 'Aventura en la naturaleza con vistas espectaculares de los Andes.',
            'Fecha_Actividad' => now()->addDays(14)->toDateString(),
            'Hora_Actividad' => '08:00:00',
            'Precio' => 45000,
            'Cupo_Maximo' => 12,
            'Ubicacion' => 'Sogamoso, Boyacá',
            'Imagen' => 'https://images.unsplash.com/photo-1551632811-561732d1e306?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80',
            'idCategoria' => $aventura->id,
            'idMunicipio' => $sogamoso->id,
            'idUsuario' => $empresaUser->id
        ]);
    }
}
