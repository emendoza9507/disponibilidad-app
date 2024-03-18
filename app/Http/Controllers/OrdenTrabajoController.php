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
        $end_date = $request->query->get('end_date') ? Carbon::create($request->query->get('end_date')) : Carbon::create(now());
        $start_date = $request->query->get('start_date') ? Carbon::create($request->query->get('start_date')) : $end_date->copy()->subMonth(1);

        $connection_id = $request->query->get('connection_id', 1);
        $connection = $connectionService->setConnection($connection_id);
        $matricula = $request->query->get('matricula');
        $maestro = $request->query->get('maestro');

        $ordenes = [];

        try {
            if($maestro) {
                self::info('Mostrando informacion del Maestro, para actualizar click en GENERAR');
                $ordenes = $ordenTrabajoService->getByMaestro($maestro);
            } elseif ($matricula) {
                $ordenes = $ordenTrabajoService->getByMatricula($matricula, $start_date, $end_date);
            } else {
                $ordenes = $ordenTrabajoService->getAll($start_date, $end_date)
                ->get();
            }

        } catch (\Exception $exception) {
            self::warning($exception);
        }

        return view('orden_trabajo.index',compact(
            'ordenes','start_date','end_date',
            'connection_id', 'connection', 'matricula'
        ));
    }

    public function Show(Request $request, string $codigoot, OrdenTrabajoService $ordenTrabajoService, ConnectionService $connectionService)
    {
        $connection_id = $request->query->get('connection_id', 1);

        if($connection_id) {
            $connection = $connectionService->setConnection($connection_id);
        }

        $ot = $ordenTrabajoService->get($codigoot);

        return view('orden_trabajo.show', compact(
              'ot', 'connection_id'
        ));
    }
}
