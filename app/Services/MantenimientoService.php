<?php

namespace App\Services;

use App\Livewire\OrdenTrabajo\Materiales;
use App\Models\Mistral\Material;
use App\Models\Mistral\OrdenTrabajo;
use Illuminate\Database\Query\JoinClause;

class MantenimientoService
{
    public function getMantenimientosByDate($start_date, $end_date)
    {

        $materiales = Material::join('orden_trabajo', function (JoinClause $join) {
                $join->on('orden_trabajo.CODIGOOT', '=', 'material.CODIGOOT');
            })
            ->where('AREA', 'A08')
            ->where('CANTIDAD', '>', 3)
            ->select('material.CODIGOOT');

        $query = OrdenTrabajo::whereIn('CODIGOOT', $materiales)
            ->whereBetween('FECHAENTRADA', [$start_date, $end_date]);

        return $query;
    }
}
