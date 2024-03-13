<?php

namespace App\Services;

use App\Helpers\ResetDB;
use App\Models\Mistral\OrdenTrabajo;
use Illuminate\Support\Facades\Config;

class OrdenTrabajoService
{
    public function getAll($start_date, $end_date)
    {
        $connection = (object) session('connection', Config::get('database.connections.taller'));
        ResetDB::setDBConfig('taller', (array) $connection);

        return OrdenTrabajo::whereBetween('FECHAENTRADA', [$start_date, $end_date])
            ->orderBy('FECHAENTRADA', 'DESC')
            ->orderBy('FECHASALIDA', 'DESC')
            ->get();
    }

    public function get(string $codigoot)
    {
        $connection = (object) session('connection', Config::get('database.connections.taller'));
        ResetDB::setDBConfig('taller', (array) $connection);

        return OrdenTrabajo::where('CODIGOOT', $codigoot)->first();
    }
}
