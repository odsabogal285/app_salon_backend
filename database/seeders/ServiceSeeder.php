<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Service::query()->insert(array(
            [
                'name' => 'Corte de cabello para hombres',
                'price' => 100,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Corte de cabello para mujeres',
                'price' => 120,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Corte de cabello para niños',
                'price' => 90,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Corte de barba y bigote',
                'price' => 100,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Afeitado clásico con navaja',
                'price' => 100,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Coloración de cabello',
                'price' => 200,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Reflejos y mechas',
                'price' => 300,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Peinados para bodas y eventos especiales',
                'price' => 400,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Tratamientos capilares para cabello dañado o quebradizo',
                'price' => 100,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Masajes capilares para estimular el crecimiento del cabello',
                'price' => 140,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Maquillaje para bodas y eventos especiales',
                'price' => 300,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Manicura y pedicura',
                'price' => 120,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Depilación con cera',
                'price' => 120,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Tratamientos faciales para hombres y mujeres',
                'price' => 130,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Tratamientos de exfoliación corporal',
                'price' => 200,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Masajes relajantes para aliviar el estrés y la tensión muscular',
                'price' => 250,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Extensiones de cabello',
                'price' => 150,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Maquillaje de cejas y pestañas',
                'price' => 100,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Tintura de cejas y pestañas',
                'price' => 150,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Tratamientos de bronceado sin sol',
                'price' => 250,
                'created_at' => now(),
                'updated_at' => now()
            ]
            )
        );
    }
}
