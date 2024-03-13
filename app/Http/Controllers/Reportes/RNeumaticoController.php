<?php

namespace App\Http\Controllers\Reportes;

use App\Http\Controllers\Controller;
use App\Models\Connection;
use App\Services\NeumaticosService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RNeumaticoController extends Controller
{
    //
    public function index(Request $request, NeumaticosService $neumaticosService)
    {
        $connections = Connection::all();
        $end_date = $request->query->get('end_date') ? Carbon::create($request->query->get('end_date')) : Carbon::create(now());
        $start_date = $request->query->get('start_date') ? Carbon::create($request->query->get('start_date')) : $end_date->copy()->subMonth(1);
        $connection_id = $request->query->get('connection_id') ?: $connections[0]->id;

        $connection = Connection::find($connection_id);

        if($connection) {
            session(['connection' => [
                'host' => $connection->hostname,
                'port' => '1433',
                'database' => $connection->database,
                'username' => $connection->username,
                'password' => $connection->password
            ]]);
        }

        $resumenNeumaticos = $neumaticosService->resumenNeumaticosPorMesAnno($start_date, $end_date);

        return view('reportes.neumatico.index',compact(
            'resumenNeumaticos',
            'start_date','end_date', 'connections',
            'connection_id', 'connection'
        ));
    }
}
