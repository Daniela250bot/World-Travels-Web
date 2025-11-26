<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DiasFestivosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $diasFestivos = [
            // 2025
            ['fecha' => '2025-01-01', 'nombre' => 'Año Nuevo', 'descripcion' => 'Celebración del inicio del año nuevo'],
            ['fecha' => '2025-01-06', 'nombre' => 'Día de Reyes', 'descripcion' => 'Epifanía del Señor'],
            ['fecha' => '2025-03-24', 'nombre' => 'Día de San José', 'descripcion' => 'Fiesta de San José'],
            ['fecha' => '2025-04-13', 'nombre' => 'Domingo de Ramos', 'descripcion' => 'Inicio de la Semana Santa'],
            ['fecha' => '2025-04-18', 'nombre' => 'Viernes Santo', 'descripcion' => 'Conmemoración de la crucifixión de Jesús'],
            ['fecha' => '2025-05-01', 'nombre' => 'Día del Trabajo', 'descripcion' => 'Celebración del trabajo y los trabajadores'],
            ['fecha' => '2025-05-29', 'nombre' => 'Ascensión del Señor', 'descripcion' => 'Ascensión de Jesús al cielo'],
            ['fecha' => '2025-06-08', 'nombre' => 'Corpus Christi', 'descripcion' => 'Fiesta del Cuerpo y la Sangre de Cristo'],
            ['fecha' => '2025-06-23', 'nombre' => 'Sagrado Corazón', 'descripcion' => 'Fiesta del Sagrado Corazón de Jesús'],
            ['fecha' => '2025-07-20', 'nombre' => 'Día de la Independencia', 'descripcion' => 'Declaración de Independencia de Colombia'],
            ['fecha' => '2025-08-07', 'nombre' => 'Batalla de Boyacá', 'descripcion' => 'Conmemoración de la Batalla de Boyacá'],
            ['fecha' => '2025-08-18', 'nombre' => 'Asunción de la Virgen', 'descripcion' => 'Asunción de María al cielo'],
            ['fecha' => '2025-10-13', 'nombre' => 'Día de la Raza', 'descripcion' => 'Descubrimiento de América'],
            ['fecha' => '2025-11-03', 'nombre' => 'Todos los Santos', 'descripcion' => 'Fiesta de Todos los Santos'],
            ['fecha' => '2025-11-17', 'nombre' => 'Independencia de Cartagena', 'descripcion' => 'Independencia de Cartagena de Indias'],
            ['fecha' => '2025-12-08', 'nombre' => 'Día de la Inmaculada Concepción', 'descripcion' => 'Inmaculada Concepción de María'],
            ['fecha' => '2025-12-25', 'nombre' => 'Navidad', 'descripcion' => 'Nacimiento de Jesús'],

            // 2026
            ['fecha' => '2026-01-01', 'nombre' => 'Año Nuevo', 'descripcion' => 'Celebración del inicio del año nuevo'],
            ['fecha' => '2026-01-06', 'nombre' => 'Día de Reyes', 'descripcion' => 'Epifanía del Señor'],
            ['fecha' => '2026-02-16', 'nombre' => 'Carnaval', 'descripcion' => 'Fiesta del Carnaval'],
            ['fecha' => '2026-03-23', 'nombre' => 'Día de San José', 'descripcion' => 'Fiesta de San José'],
            ['fecha' => '2026-04-05', 'nombre' => 'Domingo de Ramos', 'descripcion' => 'Inicio de la Semana Santa'],
            ['fecha' => '2026-04-10', 'nombre' => 'Viernes Santo', 'descripcion' => 'Conmemoración de la crucifixión de Jesús'],
            ['fecha' => '2026-05-01', 'nombre' => 'Día del Trabajo', 'descripcion' => 'Celebración del trabajo y los trabajadores'],
            ['fecha' => '2026-05-21', 'nombre' => 'Ascensión del Señor', 'descripcion' => 'Ascensión de Jesús al cielo'],
            ['fecha' => '2026-05-31', 'nombre' => 'Corpus Christi', 'descripcion' => 'Fiesta del Cuerpo y la Sangre de Cristo'],
            ['fecha' => '2026-06-15', 'nombre' => 'Sagrado Corazón', 'descripcion' => 'Fiesta del Sagrado Corazón de Jesús'],
            ['fecha' => '2026-07-20', 'nombre' => 'Día de la Independencia', 'descripcion' => 'Declaración de Independencia de Colombia'],
            ['fecha' => '2026-08-07', 'nombre' => 'Batalla de Boyacá', 'descripcion' => 'Conmemoración de la Batalla de Boyacá'],
            ['fecha' => '2026-08-17', 'nombre' => 'Asunción de la Virgen', 'descripcion' => 'Asunción de María al cielo'],
            ['fecha' => '2026-10-12', 'nombre' => 'Día de la Raza', 'descripcion' => 'Descubrimiento de América'],
            ['fecha' => '2026-11-02', 'nombre' => 'Todos los Santos', 'descripcion' => 'Fiesta de Todos los Santos'],
            ['fecha' => '2026-11-16', 'nombre' => 'Independencia de Cartagena', 'descripcion' => 'Independencia de Cartagena de Indias'],
            ['fecha' => '2026-12-08', 'nombre' => 'Día de la Inmaculada Concepción', 'descripcion' => 'Inmaculada Concepción de María'],
            ['fecha' => '2026-12-25', 'nombre' => 'Navidad', 'descripcion' => 'Nacimiento de Jesús'],
        ];

        foreach ($diasFestivos as $festivo) {
            \App\Models\DiaFestivo::create($festivo);
        }
    }
}
