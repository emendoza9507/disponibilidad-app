<?php

namespace App\Http\Controllers\Reportes;

use App\Http\Controllers\Controller;
use App\Models\Mistral\OrdenTrabajo;
use App\Services\ConnectionService;
use App\Services\OrdenTrabajoService;
use Illuminate\Http\Request;

class RProduccionProceso extends Controller
{
    public function index(Request $request, ConnectionService $connectionService, OrdenTrabajoService $ordenTrabajoService)
    {
        $connection_id = $request->query->get('connection_id', 1);
        $connection = $connectionService->setConnection($connection_id );

        if(!$connection) return redirect(route('home'));

        $ordenes = [];

        $query = $ordenTrabajoService->getQueryProduccionProceso();

        $ordenes = $query->get();
        $combustible_tanque = $query->sum('DEPOSITOENTRADA');

        return view('reportes.produccion_proceso.index', compact(
            'ordenes', 'connection', 'connection_id', 'combustible_tanque'
        ));
    }
}
