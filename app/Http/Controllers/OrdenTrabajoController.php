<?php

namespace App\Http\Controllers;

use App\Models\Connection;
use App\Models\Mistral\OrdenTrabajo;
use App\Services\ConnectionService;
use App\Services\OrdenTrabajoService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OrdenTrabajoController extends Controller
{
    //
    public function index(Request $request, OrdenTrabajoService $ordenTrabajoService, ConnectionService $connectionService)
    {
        $connections = Connection::all();
        $end_date = $request->query->get('end_date') ? Carbon::create($request->query->get('end_date')) : Carbon::create(now());
        $start_date = $request->query->get('start_date') ? Carbon::create($request->query->get('start_date')) : $end_date->copy()->subMonth(1);
        $connection_id = $request->query->get('connection_id') ?: $connections[0]->id;
        $connection = $connectionService->setConnection($connection_id);

        $ordenes = [];

        try {
            $ordenes = $ordenTrabajoService->getAll($start_date, $end_date);
        } catch (\Exception $exception) {
            self::warning($exception);
        }

        return view('orden_trabajo.index',compact(
            'ordenes',
            'start_date','end_date', 'connections',
            'connection_id', 'connection'
        ));
    }

    public function Show(Request $request, string $codigoot, OrdenTrabajoService $ordenTrabajoService, ConnectionService $connectionService)
    {
        $connections = Connection::all();

        $ot = $ordenTrabajoService->get($codigoot);

        return view('orden_trabajo.show', compact(
              'ot'
        ));
    }
}
