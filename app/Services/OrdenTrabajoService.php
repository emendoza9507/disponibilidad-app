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

    public function getByMaestro(string $codigom)
    {
        $connection = (object) session('connection', Config::get('database.connections.taller'));
        ResetDB::setDBConfig('taller', (array) $connection);

        return OrdenTrabajo::where('CODIGOM', $codigom)->orderBy('FECHAENTRADA', 'DESC')->get();
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
