<?php

namespace Database\Seeders;


use App\Models\Horario;
use GuzzleHttp\Promise\Create;
use Illuminate\Database\Seeder;

class HorariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Horario::create([
            'hora'=>'07:00:00',
        ]);
        Horario::create([
            'hora'=>'07:30:00',
        ]);
        Horario::create([
            'hora'=>'08:00:00',
        ]);
        Horario::create([
            'hora'=>'08:30:00',
        ]);
        Horario::create([
            'hora'=>'09:00:00',
        ]);
        Horario::create([
            'hora'=>'09:30:00',
        ]);
        Horario::create([
            'hora'=>'10:00:00',
        ]);
        Horario::create([
            'hora'=>'10:30:00',
        ]);
        Horario::create([
            'hora'=>'11:00:00',
        ]);
        Horario::create([
            'hora'=>'11:30:00',
        ]);
        Horario::create([
            'hora'=>'12:00:00',
        ]);
        Horario::create([
            'hora'=>'12:30:00',
        ]);
        Horario::create([
            'hora'=>'13:00:00',
        ]);
        Horario::create([
            'hora'=>'13:30:00',
        ]);
        Horario::create([
            'hora'=>'14:00:00',
        ]);
        Horario::create([
            'hora'=>'14:30:00',
        ]);
        Horario::create([
            'hora'=>'15:00:00',
        ]);
        Horario::create([
            'hora'=>'15:30:00',
        ]);
        Horario::create([
            'hora'=>'16:00:00',
        ]);
        Horario::create([
            'hora'=>'16:30:00',
        ]);
        Horario::create([
            'hora'=>'17:00:00',
        ]);
        Horario::create([
            'hora'=>'17:30:00',
        ]);
        Horario::create([
            'hora'=>'18:00:00',
        ]);

    }
}
