<?php

namespace App\Services;

use App\Helpers\ResetDB;
use App\Models\EstadoOT;
use App\Models\Mistral\Material;
use App\Models\Mistral\OrdenTrabajo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\Config;

class OrdenTrabajoService
{
    public function getAll($start_date, $end_date)
    {
        return OrdenTrabajo::whereBetween('FECHAENTRADA', [$start_date, $end_date])
            ->with('estado')
            ->orderBy('FECHAENTRADA', 'DESC')
            ->orderBy('FECHASALIDA', 'DESC');
    }

    public function get(string $codigoot)
    {
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

    public function getByMaestro(string $codigom, $start_date = null, $end_date = null, $hideAnuladas = false)
    {
        $query = OrdenTrabajo::where('CODIGOM', $codigom)
            ->with('estado')
            ->orderBy('FECHAENTRADA', 'DESC');

        if($hideAnuladas) {
            $query = $query->whereNotIn('ESTADO', EstadoOT::IDS_ESTADOS_ANULADOS);
        }

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
        return OrdenTrabajo::where('CODIGOM', $codigom)
            ->whereNull('FECHACIERRE')
            ->where('ESTADO', 1)
            ->orderBy('FECHAENTRADA', 'DESC')->get();
    }

    public function getByMaestroCerradas(string $codigom)
    {
        return OrdenTrabajo::where('CODIGOM', $codigom)
            ->whereNotNull('FECHACIERRE')
            ->where('ESTADO', 3)
            ->orderBy('FECHAENTRADA', 'DESC')->get();
    }

    public function getByMaestroAnuladas(string $codigom)
    {
        return OrdenTrabajo::where('CODIGOM', $codigom)
            ->whereIn('ESTADO', [9, 2])
            ->orderBy('FECHAENTRADA', 'DESC')->get();
    }

    public function getByMatricula(string $matricula, $fecha_entrada = null, $fecha_cierre = null, string $operation = '<')
    {
        $query = OrdenTrabajo::where('MATRICULA', $matricula)
        ->with('estado')
        ->whereNotIn('ESTADO', EstadoOT::IDS_ESTADOS_ANULADOS);

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

    public function getMaterialesPorArea(OrdenTrabajo $ot, string $area)
    {
        $materiales = [];
        foreach ($ot->materials as $material) {
            if(str_contains($material->AREA,$area)) {
                $materiales[] = $material;
            }
        }

        return $materiales;
    }


    public function getLastOtWithMaterialType($material, string $codigom = null)
    {
        $materiales = Material::join('orden_trabajo', function (JoinClause $join) {
            $join->on('orden_trabajo.CODIGOOT', '=', 'material.CODIGOOT');
        })
            ->where('AREA', $material)
            ->where('CANTIDAD', '>', 0)
            ->select('material.CODIGOOT');

        return OrdenTrabajo::whereIn('CODIGOOT', $materiales)
            ->with('estado')
            ->whereNotNull('FECHACIERRE')
            ->whereNotIn('ESTADO', EstadoOT::IDS_ESTADOS_ANULADOS)
            ->when($codigom, function (Builder $query, string $codigom) {
                $query->where('CODIGOM', $codigom);
            })
            ->orderBy('FECHACIERRE', 'DESC')
            ->first();
    }
}
