<?php

namespace App\Http\Controllers\Reportes;

use App\Http\Controllers\Controller;
use App\Models\Mistral\Material;
use App\Models\Mistral\OrdenTrabajo;
use App\Services\ConnectionService;
use App\Services\MantenimientoService;
use App\Services\OrdenTrabajoService;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;
use function Laravel\Prompts\select;

class RMantenimientosController extends Controller
{
    //
    public function index(
        Request $request,
        ConnectionService $connectionService,
        OrdenTrabajoService $ordenTrabajoService,
        MantenimientoService $mantenimientoService
    )
    {
        $end_date = $request->query->get('end_date') ? Carbon::create($request->query->get('end_date')) : Carbon::create(now());
        $start_date = $request->query->get('start_date') ? Carbon::create($request->query->get('start_date')) : $end_date->copy()->subMonth(1);
        $connection_id = $request->query->get('connection_id', 1);
        $connection = $connectionService->setConnection($connection_id);



        $ordenes = $mantenimientoService->getMantenimientosByDate($start_date, $end_date)
            ->get();



        return view('reportes.mantenimiento.index', compact(
            'connection_id', 'start_date', 'end_date', 'ordenes',
            'connection'
        ));
    }
}
