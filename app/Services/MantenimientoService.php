<?php

namespace App\Services;

use App\Models\Mantenimiento;
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

    public function getAll($start_date, $end_date)
    {
        return Mantenimiento::whereBetween('created_at', [$start_date, $end_date]);
    }

    public function getByConnection($connection_id, $start_date, $end_date)
    {
        return $this->getAll($start_date, $end_date)->where('connection_id', $connection_id);
    }
}
