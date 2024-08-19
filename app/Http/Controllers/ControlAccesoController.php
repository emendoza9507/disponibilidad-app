<?php

namespace App\Http\Controllers;

use App\Services\ConnectionService;
use Illuminate\Http\Request;

class ControlAccesoController extends Controller
{
    public function index(Request $request, ConnectionService $connectionService)
    {
        $connection_id = $request->query->get('connection_id', 1);
        $connection = $connectionService->setConnection($connection_id);

        return view('control_acceso.index', compact(
            'connection_id'
        ));
    }
}
