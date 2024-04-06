<?php

namespace Database\Seeders;

use App\Models\EstadoOT;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EstadoOTSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //


        EstadoOT::create([
            'estado_id' => '1',
            'estado_nombre' => 'Abierta'
        ]);

        EstadoOT::create([
            'estado_id' => '3',
            'estado_nombre' => 'Cerrada'
        ]);

        EstadoOT::create([
            'estado_id' => '2',
            'estado_nombre' => 'Cancelada'
        ]);

        EstadoOT::create([
            'estado_id' => '9',
            'estado_nombre' => 'Anulada'
        ]);

        EstadoOT::create([
            'estado_id' => '6',
            'estado_nombre' => 'Finalizada'
        ]);
    }
}
