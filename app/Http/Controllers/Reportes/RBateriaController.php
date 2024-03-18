<?php

namespace App\Http\Controllers\Reportes;

use App\Helpers\ResetDB;
use App\Http\Controllers\Controller;
use App\Models\Connection;
use App\Services\ConnectionService;
use App\Services\NeumaticosService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class RBateriaController extends Controller
{
    //
    public function index(Request $request, NeumaticosService $neumaticosService, ConnectionService $connectionService)
    {
        $end_date = $request->query->get('end_date') ? Carbon::create($request->query->get('end_date')) : Carbon::create(now());
        $start_date = $request->query->get('start_date') ? Carbon::create($request->query->get('start_date')) : $end_date->copy()->subMonth(1);
        $connection_id = $request->query->get('connection_id', 1);

        $connection = $connectionService->setConnection($connection_id);

        if(!$connection) return redirect(route('home'));

        if($connection) {
            session(['connection' => [
                'host' => $connection->hostname,
                'port' => '1433',
                'database' => $connection->database,
                'username' => $connection->username,
                'password' => $connection->password
            ]]);
        }

        $resumenBaterias = $neumaticosService->resumenBaterias($start_date, $end_date);

        return view('reportes.bateria.index',compact(
            'resumenBaterias',
            'start_date','end_date',
            'connection_id', 'connection'
        ));
    }
}
