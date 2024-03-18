<?php

namespace App\Http\Controllers\Reportes;

use App\Http\Controllers\Controller;
use App\Models\Connection;
use App\Services\ConnectionService;
use App\Services\NeumaticosService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RNeumaticoController extends Controller
{
    //
    public function index(Request $request, NeumaticosService $neumaticosService, ConnectionService $connectionService)
    {
        $end_date = $request->query->get('end_date') ? Carbon::create($request->query->get('end_date')) : Carbon::create(now());
        $start_date = $request->query->get('start_date') ? Carbon::create($request->query->get('start_date')) : $end_date->copy()->subMonth(1);
        $connection_id = $request->query->get('connection_id', 1);
        $connectionService->setConnection($connection_id);

        $resumenNeumaticos = $neumaticosService->resumenNeumaticosPorMesAnno($start_date, $end_date);

        return view('reportes.neumatico.index',compact(
            'resumenNeumaticos',
            'start_date','end_date', 'connection_id'
        ));
    }
}
