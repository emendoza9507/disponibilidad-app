<?php

namespace App\Services;

use App\Helpers\ResetDB;
use App\Models\Bateria;
use App\Models\Mistral\OrdenTrabajo;
use App\Models\Neumatico;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class BateriasService
{

    public function __construct(protected OrdenTrabajoService $ordenTrabajoService)
    {}

    public function getByCodigoOt(string $codigoot)
    {
        return Bateria::where('CODIGOOT', $codigoot)->get();
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
            $bateria->TALLER = $ot->Prisma;
            $bateria->user_id = Auth::user()->id;

            $bateria->save();
            $consecutivos[] = $bateria;
        }

        return $consecutivos;
    }
}
