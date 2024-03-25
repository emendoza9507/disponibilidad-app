<?php

namespace App\Http\Controllers\Reportes;

use App\Http\Controllers\Controller;
use App\Models\Mistral\Maestro;
use App\Services\AreaService;
use App\Services\AutoService;
use App\Services\ConnectionService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RAutoMaterialController extends Controller
{
    //
    public function index(
        Request $request,
        ConnectionService $connectionService,
        AutoService $autoService,
        AreaService $areaService,
        ?string $codigom = null,
    )
    {
        $end_date = $request->query->get('end_date') ? Carbon::create($request->query->get('end_date')) : Carbon::create(now());
        $start_date = $request->query->get('start_date') ? Carbon::create($request->query->get('start_date')) : $end_date->copy()->subMonth(1);
        $connection_id = $request->query->get('connection_id', 1);
        $area = $request->query->get('area', '*');

        $connection = $connectionService->setConnection($connection_id);

        if(!$connection) {
            if($request->ajax()) {
                return new JsonResponse([
                    'status' => false,
                    'message' => 'Taller desconectado',
                    'connection' => $connectionService->getCurrentConnection()
                ]);
            }

            self::warning('Taller desconectado. ('.$connectionService->getCurrentConnection()->name.')');
            return redirect(route('home'));
        }

        $maestro = null;
        if($codigom)
        {
            $maestro = Maestro::find($codigom);
        }

        $areas = $areaService->getAll();

        $ordenes = $autoService->getOtsWithMaterialArea($area, $start_date, $end_date, $codigom);

        if($request->ajax()) {
            return new JsonResponse([
                'status' => true,
                'html' => view('reportes.auto_material.partials.rows', compact(
                    'maestro', 'codigom', 'connection_id', 'connection',
                    'start_date', 'end_date', 'areas', 'ordenes', 'area'
                ))->render()
            ]);
        }

        return view('reportes.auto_material.index', compact(
            'maestro', 'codigom', 'connection_id', 'connection',
            'start_date', 'end_date', 'areas', 'ordenes', 'area'
        ));
    }

}
