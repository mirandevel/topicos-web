<?php

namespace Database\Seeders;

use App\Models\Servicio;
use Illuminate\Database\Seeder;

class ServicioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Servicio::create([
            'nombre'=>'Plomeria',
            'descripcion'=>'Sin descripcion',
        ]);

        Servicio::create([
            'nombre'=>'Carpinteria',
            'descripcion'=>'Sin descripcion',
        ]);

        Servicio::create([
            'nombre'=>'Jardinería',
            'descripcion'=>'Sin descripcion',
        ]);

        Servicio::create([
            'nombre'=>'Albañileria',
            'descripcion'=>'Sin descripcion',
        ]);

        Servicio::create([
            'nombre'=>'Electricista',
            'descripcion'=>'Sin descripcion',
        ]);
    }
}
