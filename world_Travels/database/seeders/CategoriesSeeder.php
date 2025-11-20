<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'nombre' => 'Aventura',
                'descripcion' => 'Actividades de aventura y deportes extremos en Boyacá',
                'imagen' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80',
                'estado' => true
            ],
            [
                'nombre' => 'Cultural',
                'descripcion' => 'Experiencias culturales, historia y tradiciones de Boyacá',
                'imagen' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80',
                'estado' => true
            ],
            [
                'nombre' => 'Gastronomía',
                'descripcion' => 'Degustación de platos típicos y experiencias culinarias',
                'imagen' => 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80',
                'estado' => true
            ],
            [
                'nombre' => 'Naturaleza',
                'descripcion' => 'Actividades en contacto con la naturaleza y paisajes',
                'imagen' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80',
                'estado' => true
            ],
            [
                'nombre' => 'Relajación',
                'descripcion' => 'Spa, termales y actividades de descanso',
                'imagen' => 'https://images.unsplash.com/photo-1571003123894-1f0594d2b5d9?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80',
                'estado' => true
            ],
            [
                'nombre' => 'Familiar',
                'descripcion' => 'Actividades adecuadas para toda la familia',
                'imagen' => 'https://images.unsplash.com/photo-1511895426328-dc8714191300?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80',
                'estado' => true
            ],
            [
                'nombre' => 'Deportes Extremos',
                'descripcion' => 'Actividades de alto riesgo y adrenalina',
                'imagen' => 'https://images.unsplash.com/photo-1544966503-7cc5ac882d5f?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80',
                'estado' => false
            ]
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
