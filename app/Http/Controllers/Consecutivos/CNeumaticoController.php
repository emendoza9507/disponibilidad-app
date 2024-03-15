<?php

namespace App\Http\Controllers\Consecutivos;

use App\Http\Controllers\Controller;
use App\Models\Connection;
use App\Services\BateriasService;
use App\Services\ConnectionService;
use App\Services\NeumaticosService;
use App\Services\OrdenTrabajoService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CNeumaticoController extends Controller
{
    //
    public function index(Request $request, OrdenTrabajoService $ordenTrabajoService, ConnectionService $connectionService, NeumaticosService $neumaticosService)
    {
        $connections = Connection::all();
        $end_date = $request->query->get('end_date') ? Carbon::create($request->query->get('end_date')) : Carbon::create(now());
        $start_date = $request->query->get('start_date') ? Carbon::create($request->query->get('start_date')) : $end_date->copy()->subDay(1);
        $connection_id = $request->query->get('connection_id') ?: $connections[0]->id;
        $connection = $connectionService->setConnection($connection_id);

        $ordenes = [];

        try {
            $ordenes = $ordenTrabajoService->getAll($start_date, $end_date->clone()->addDay(1));

            foreach ($ordenes as $key => &$orden) {
                $cantidad_neumaticos = $neumaticosService->getCantidadNeumaticosCargados($orden);

                if($cantidad_neumaticos == 0) {
                    unset($ordenes[$key]);
                } else {
                    $orden->neumaticos = $ordenTrabajoService->getMaterialesPorTipo($orden, 'A11');
                    $orden->cant_neumaticos = $cantidad_neumaticos;
                }
            }

        } catch (\Exception $exception) {
            self::warning($exception);
            return back();
        }

        return view('consecutivo.neumatico.index', compact(
            'connections', 'start_date', 'end_date', 'connection_id', 'connection',
            'ordenes'
        ));
    }

    public function store(Request $request, ConnectionService $connectionService, OrdenTrabajoService $ordenTrabajoService, NeumaticosService $neumaticosService)
    {
        $connection_id = $request->query->get('connection_id');
        $codigoot = $request->request->get('codigoot');
        $connectionService->setConnection($connection_id);

        $orden = $ordenTrabajoService->get($codigoot);

        $neumaticosService->generarConsecutivosDeNeumaticos($orden);

        self::success('Consecutivos generados satisfactoriamente');
        return back();
    }
}
