<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Usuarios;

class PopulateUserNamesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            // Si el usuario tiene relación con tabla usuarios (turistas)
            if ($user->userable_type === 'App\Models\Usuarios' && $user->userable_id) {
                $usuario = Usuarios::find($user->userable_id);
                if ($usuario) {
                    $user->update([
                        'nombre' => $usuario->Nombre,
                        'apellido' => $usuario->Apellido,
                        'telefono' => $usuario->Telefono
                    ]);
                    $this->command->info("Actualizado turista ID {$user->id}: {$usuario->Nombre} {$usuario->Apellido}");
                }
            }
            // Si el nombre contiene espacios, dividirlo
            elseif (str_contains($user->name, ' ')) {
                $parts = explode(' ', $user->name, 2);
                $user->update([
                    'nombre' => $parts[0],
                    'apellido' => $parts[1] ?? ''
                ]);
                $this->command->info("Actualizado desde name ID {$user->id}: {$parts[0]} {$parts[1]}");
            }
            // Si no tiene espacios, usar el name completo como nombre
            else {
                $user->update([
                    'nombre' => $user->name,
                    'apellido' => ''
                ]);
                $this->command->info("Actualizado simple ID {$user->id}: {$user->name}");
            }
        }

        $this->command->info('Población de nombres completada');
    }
}
