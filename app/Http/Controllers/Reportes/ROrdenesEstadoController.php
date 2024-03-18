<?php

namespace App\Http\Controllers\Reportes;

use App\Http\Controllers\Controller;
use App\Models\Mistral\OrdenTrabajo;
use App\Services\ConnectionService;
use App\Services\OrdenTrabajoService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ROrdenesEstadoController extends Controller
{
    public function index(Request $request, ConnectionService $connectionService, OrdenTrabajoService $ordenTrabajoService)
    {

        $end_date = $request->query->get('end_date') ? Carbon::create($request->query->get('end_date')) : Carbon::create(now());
        $start_date = $request->query->get('start_date') ? Carbon::create($request->query->get('start_date')) : $end_date->copy()->subMonth(1);

        $connection_id = $request->query->get('connection_id', 1);
        $connection = $connectionService->setConnection($connection_id );

        if(!$connection) return redirect(route('home'));

        $estado = strtoupper($request->query->get('estado', 'abiertas'));

        $query = $ordenTrabajoService->getQueryByEstado($estado, $start_date, $end_date);

        $ordenes = $query->get();
        $combustible_tanque = $query->sum('DEPOSITOENTRADA');

        return view('reportes.ordenes_estado.index', compact(
            'ordenes', 'connection', 'connection_id', 'combustible_tanque', 'estado',
            'start_date', 'end_date'
        ));
    }
}
