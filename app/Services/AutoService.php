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
            ->groupBy('super_maestro.MARCA', 'super_maestro.MODELO')
            ->orderBy('total', 'DESC');
    }

    public function getByMarcaModelo($marca, $modelo, $select = ['maestro.*'])
    {
        return Maestro::query()
            ->select($select)
            ->join('super_maestro', 'maestro.CODIGOSM', '=', 'super_maestro.CODIGOSM')
            ->whereNotNull('MATRICULA')
            ->whereNull('FECHABAJA')
            ->where('super_maestro.MARCA', $marca)
            ->where('super_maestro.MODELO', $modelo);
    }

    public function getLastAutos(int $limit)
    {
        return Maestro::orderBy('FECHAALTA', 'DESC')
            ->with('supermaestro' )
            ->whereNotNull('MATRICULA')
            ->whereNull('FECHABAJA')
            ->limit($limit)->get();
    }

    public function getOtsWithMaterialArea($material, $start_date, $end_date, string $codigom = null)
    {
        $materiales = Material::join('orden_trabajo', function (JoinClause $join) {
            $join->on('orden_trabajo.CODIGOOT', '=', 'material.CODIGOOT');
        })
            ->select('material.CODIGOOT')
            ->where('CANTIDAD', '>', 0);

        if($material != '*') {
            $materiales = $materiales
                ->where('AREA', $material);
        }

        return OrdenTrabajo::whereIn('CODIGOOT', $materiales)
            ->with('estado')
            ->with('materials',  function ($query) use ($material) {
                if($material != '*') {
                    $query->where('AREA', $material);
                }
            })
            ->whereNotNull('FECHACIERRE')
            ->when($codigom, function (Builder $query, string $codigom) {
                $query->where('CODIGOM', $codigom);
            })
            ->whereBetween('FECHACIERRE', [$start_date, $end_date])
            ->orderBy('FECHACIERRE', 'DESC')
            ->get();
    }

    public function getLastOt(string $codigom)
    {
        return OrdenTrabajo::where('CODIGOM', $codigom)
            ->with('estado')
            ->orderBy('FECHAENTRADA', 'DESC')
            ->first();
    }

}
