<?php

namespace App\Services;

use App\Helpers\ResetDB;
use App\Models\Bateria;
use App\Models\Mistral\Material;
use App\Models\Mistral\OrdenTrabajo;
use App\Models\Neumatico;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class BateriasService
{

    public function __construct(protected OrdenTrabajoService $ordenTrabajoService, protected ConnectionService $connectionService)
    {}

    public function getByCodigoOt(string $codigoot)
    {
        return Bateria::where('CODIGOOT', $codigoot)->get();
    }


    public function getByCodigoCodigoMaestro(string $codigom)
    {
        return Bateria::where('CODIGOM', $codigom)
            ->orderBy('id', 'desc')
            ->get();
    }

    public function getCantidadBateriasCargadas(OrdenTrabajo $ot)
    {
        $materiales = $this->ordenTrabajoService->getMaterialesPorTipo($ot,'A10');
        $cantidad = 0;

        foreach ($materiales as $material) {
            $cantidad += $material->CANTIDAD;
        }

        return $cantidad;
    }

    public function generarConsecutivosDeBaterias(OrdenTrabajo $ot)
    {
        $materiales = $this->ordenTrabajoService->getMaterialesPorTipo($ot,'A10');
        $baterias = count($this->getByCodigoOt($ot));
        $bateriasCargadas = $this->getCantidadBateriasCargadas($ot);;

        $consecutivos = [];

        for ($i = $baterias; $i < $bateriasCargadas; $i++) {
            $bateria = new Bateria();
            $bateria->CODIGOOT = $ot->CODIGOOT;
            $bateria->CODIGOM = $ot->CODIGOM;
            $bateria->connection_id = $this->connectionService->getCurrentConnection()->id;
            $bateria->TALLER = $ot->Prisma;
            $bateria->user_id = Auth::user()->id;

            $bateria->save();
            $consecutivos[] = $bateria;
        }

        return $consecutivos;
    }

}
