<?php

namespace App\Services;

use App\Helpers\ResetDB;
use App\Models\Mistral\OrdenTrabajo;
use App\Models\Neumatico;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class NeumaticosService
{

    public function __construct(protected OrdenTrabajoService $ordenTrabajoService,protected ConnectionService $connectionService)
    {}

    public function resumenNeumaticosPorMesAnno($start_date, $end_date)
    {
        $connection = (object) session('connection', Config::get('database.connections.taller'));
        ResetDB::setDBConfig('taller', (array) $connection);

        $fch_start = Carbon::create(Carbon::createFromDate($start_date)->format('d-m-Y h:m:s'));
        $fch_end = Carbon::create(Carbon::createFromDate($end_date)->format('d-m-Y h:m:s'));

        $ots = OrdenTrabajo::whereBetween('FECHAENTRADA', [$fch_start, $fch_end])->orderBy('CODIGOOT', 'DESC')
            ->with('materials')
            ->with('consecutivoNeumaticos')
            ->get();

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
        $connection = (object) session('connection', Config::get('database.connections.taller'));
        ResetDB::setDBConfig('taller', (array) $connection);

        $fch_start = Carbon::create(Carbon::createFromDate($start_date)->format('d-m-Y h:m:s'));
        $fch_end = Carbon::create(Carbon::createFromDate($end_date)->format('d-m-Y h:m:s'));

        $ots = OrdenTrabajo::whereBetween('FECHAENTRADA', [$fch_start, $fch_end])->orderBy('CODIGOOT', 'DESC')
            ->with('materials')
            ->with('consecutivoBaterias')
            ->get();

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

    public function getByCodigoOt(string $codigoot)
    {
        return Neumatico::where('CODIGOOT', $codigoot)->get();
    }

    public function getByCodigoCodigoMaestro(string $codigom, $show = 5)
    {
        return Neumatico::where('CODIGOM', $codigom)
            ->orderBy('id', 'desc')
            ->paginate($show)
            ->withQueryString();
    }


    public function getCantidadNeumaticosCargados(OrdenTrabajo $ot)
    {
        $materiales = $this->ordenTrabajoService->getMaterialesPorArea($ot,'A11');
        $cantidad = 0;

        foreach ($materiales as $material) {
            if(str_contains($material->DESCRIPCION, 'NEUM')) {
                $cantidad += $material->CANTIDAD;
            }
        }

        return $cantidad;
    }

    public function generarConsecutivosDeNeumaticos(OrdenTrabajo $ot)
    {
        $materiales = $this->ordenTrabajoService->getMaterialesPorArea($ot,'A11');
        $neumaticos = count($this->getByCodigoOt($ot));
        $neumaticosCargados = $this->getCantidadNeumaticosCargados($ot);

        $consecutivos = [];


        for ($i = $neumaticos; $i < $neumaticosCargados; $i++) {
            $neumatico = new Neumatico();
            $neumatico->CODIGOOT = $ot->CODIGOOT;
            $neumatico->CODIGOM = $ot->CODIGOM;
            $neumatico->connection_id = $this->connectionService->getCurrentConnection()->id;
            $neumatico->TALLER = $ot->Prisma;
            $neumatico->user_id = Auth::user()->id;

            $neumatico->save();
            $consecutivos[] = $neumatico;
        }


        return $consecutivos;
    }
}
