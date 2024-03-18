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
            ->orderBy('FECHASALIDA', 'DESC');
    }

    public function get(string $codigoot)
    {
        $connection = (object) session('connection', Config::get('database.connections.taller'));
        ResetDB::setDBConfig('taller', (array) $connection);

        return OrdenTrabajo::withManoObras()->where('CODIGOOT', $codigoot)->first();
    }

    public function getQueryByEstado(&$estado, $start_date, $end_date)
    {
        $estado = strtoupper($estado);

        $query = OrdenTrabajo::orderBy('CODIGOOT', 'desc');

        switch ($estado) {
            case 'CERRADAS': {
                $query->whereNotNull('FECHACIERRE')
                    ->where('FECHAENTRADA', '>=', $start_date)
                    ->where('FECHACIERRE', '<=', $end_date->copy()->addDay(1));
                break;
            }
            default: {
                $query->whereNull('FECHACIERRE')
                    ->whereBetween('FECHAENTRADA', [$start_date, $end_date->copy()->addDay(1)]);

                $estado = 'ABIERTAS';
            }
        }

        return $query;
    }

    public function getQueryProduccionProceso()
    {
        return OrdenTrabajo::whereNull('FECHACIERRE')
            ->orderBy('CODIGOOT', 'desc')
            ->has('materials', '>', 0);
    }

    public function getByMaestro(string $codigom, $start_date = null, $end_date = null)
    {
        $connection = (object) session('connection', Config::get('database.connections.taller'));
        ResetDB::setDBConfig('taller', (array) $connection);

        $query = OrdenTrabajo::where('CODIGOM', $codigom)->orderBy('FECHAENTRADA', 'DESC');

        if($start_date) {
            $query = $query->where('FECHAENTRADA', '>=', $start_date);
        }

        if($end_date) {
            $query = $query->where('FECHACIERRE', '<=', $end_date);
        }

        return $query->get();
    }

    public function getByMaestroAbiertas(string $codigom)
    {
        $connection = (object) session('connection', Config::get('database.connections.taller'));
        ResetDB::setDBConfig('taller', (array) $connection);

        return OrdenTrabajo::where('CODIGOM', $codigom)
            ->whereNull('FECHACIERRE')
            ->orderBy('FECHAENTRADA', 'DESC')->get();
    }

    public function getByMaestroCerradas(string $codigom)
    {
        $connection = (object) session('connection', Config::get('database.connections.taller'));
        ResetDB::setDBConfig('taller', (array) $connection);

        return OrdenTrabajo::where('CODIGOM', $codigom)
            ->whereNotNull('FECHACIERRE')
            ->orderBy('FECHAENTRADA', 'DESC')->get();
    }

    public function getByMatricula(string $matricula, $fecha_entrada = null, $fecha_cierre = null, string $operation = '<')
    {
        $connection = (object) session('connection', Config::get('database.connections.taller'));
        ResetDB::setDBConfig('taller', (array) $connection);

        $query = OrdenTrabajo::where('MATRICULA', $matricula);

        if($fecha_entrada) {
            $query = $query->where('FECHAENTRADA', $operation, $fecha_entrada);
        }

        if ($fecha_cierre) {
            $query = $query->where('FECHACIERRE', $operation, $fecha_cierre);
        }

        return $query->get();
    }

    public function checkTipoMateriales(OrdenTrabajo $ot, string $area)
    {
        $materiales = $ot->materials;

        foreach ($materiales as $material) {
            if(str_contains($material->AREA, $area)) {
                return true;
            }
        }

        return false;
    }

    public function getMaterialesPorTipo(OrdenTrabajo $ot, string $area)
    {
        $materiales = [];
        foreach ($ot->materials as $material) {
            if(str_contains($material->AREA,$area)) {
                $materiales[] = $material;
            }
        }

        return $materiales;
    }
}
