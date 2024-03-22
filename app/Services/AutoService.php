<?php

namespace App\Services;

use App\Helpers\ResetDB;
use App\Models\Mistral\Maestro;
use App\Models\Mistral\Material;
use App\Models\Mistral\OrdenTrabajo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class AutoService
{
    public function getBy($matricula)
    {
        $connection = (object) session('connection', Config::get('database.connections.taller'));
        ResetDB::setDBConfig('taller', (array) $connection);

        return Maestro::where('MATRICULA', 'like', '%'.$matricula.'%')
            ->orWhere('MATRICULAANT', 'like', '%'.$matricula.'%')
            ->get();
    }

    public function getByMaestro(string $maestro)
    {
        return Maestro::where('CODIGOM', $maestro)->first();
    }


    public function getResumenFlota()
    {
        return Maestro::query()
            ->select('super_maestro.MARCA','super_maestro.MODELO', DB::raw('count(*) as total'))
            ->join('super_maestro', 'maestro.CODIGOSM', '=', 'super_maestro.CODIGOSM')
            ->whereNotNull('MATRICULA')
            ->whereNull('FECHABAJA')
            ->where('MATRICULA', 'LIKE', 'T%')
            ->groupBy('super_maestro.MARCA', 'super_maestro.MODELO');
    }

    public function getLastAutos(int $limit)
    {
        return Maestro::orderBy('FECHAALTA', 'DESC')
            ->with('supermaestro' )
            ->whereNotNull('MATRICULA')
            ->whereNull('FECHABAJA')
            ->limit($limit)->get();
    }

    public function getOtsWithMaterialType($material, $start_date, $end_date, string $codigom = null)
    {
        $materiales = Material::join('orden_trabajo', function (JoinClause $join) {
            $join->on('orden_trabajo.CODIGOOT', '=', 'material.CODIGOOT');
        })
            ->where('AREA', $material)
            ->where('CANTIDAD', '>', 0)
            ->select('material.CODIGOOT');

        return OrdenTrabajo::whereIn('CODIGOOT', $materiales)
            ->with('materials',  function ($query) use ($material) {
                $query->where('AREA', $material);
            })
            ->whereNotNull('FECHACIERRE')
            ->when($codigom, function (Builder $query, string $codigom) {
                $query->where('CODIGOM', $codigom);
            })
            ->whereBetween('FECHACIERRE', [$start_date, $end_date])
            ->orderBy('FECHACIERRE', 'DESC')
            ->get();
    }

}
