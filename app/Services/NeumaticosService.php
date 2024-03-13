<?php

namespace App\Services;

use App\Livewire\OrdenTrabajo\Materiales;
use App\Models\Mistral\OrdenTrabajo;
use Carbon\Carbon;

class NeumaticosService
{
    public function resumenNeumaticosPorMesAnno($start_date, $end_date)
    {
        $fch_start = Carbon::create(Carbon::createFromDate($start_date)->format('d-m-Y h:m:s'));
        $fch_end = Carbon::create(Carbon::createFromDate($end_date)->format('d-m-Y h:m:s'));

        $ots = OrdenTrabajo::whereBetween('FECHAENTRADA', [$fch_start, $fch_end])->get();

        $result = [];

        foreach ($ots as $ot) {

            $result[$ot->CODIGOOT] = ['ot' => $ot, 'neumaticos' => 0];
            $materials = $ot->materials;

            foreach ($materials as $material) {
                if(str_contains($material->AREA, 'A11')) {
                    $result[$ot->CODIGOOT]['neumaticos'] = $result[$ot->CODIGOOT]['neumaticos'] + $material->CANTIDAD ;
                }
            }

            if ($result[$ot->CODIGOOT]['neumaticos'] == 0) {
                unset($result[$ot->CODIGOOT]);
            }
        }

        return $result;
    }
    public function resumenBaterias($start_date, $end_date)
    {
        $fch_start = Carbon::create(Carbon::createFromDate($start_date)->format('d-m-Y h:m:s'));
        $fch_end = Carbon::create(Carbon::createFromDate($end_date)->format('d-m-Y h:m:s'));

        $ots = OrdenTrabajo::whereBetween('FECHAENTRADA', [$fch_start, $fch_end])->get();

        $result = [];

        foreach ($ots as $ot) {

            $result[$ot->CODIGOOT] = ['ot' => $ot, 'baterias' => 0];
            $materials = $ot->materials;

            foreach ($materials as $material) {
                if(str_contains($material->AREA, 'A10')) {
                    $result[$ot->CODIGOOT]['baterias'] = $result[$ot->CODIGOOT]['baterias'] + $material->CANTIDAD ;
                }
            }

            if ($result[$ot->CODIGOOT]['baterias'] == 0) {
                unset($result[$ot->CODIGOOT]);
            }
        }

        return $result;
    }
}
