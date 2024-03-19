<?php

namespace App\Http\Controllers;

use App\Models\Connection;
use App\Services\ConnectionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\StoreConnectionRequest;

class ConnectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $connections = Connection::paginate(4);

        return view('connections.list');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreConnectionRequest $request)
    {
        Connection::create([
            'name' => $request->get('name'),
            'hostname' => $request->get('hostname'),
            'database' => $request->get('database'),
            'codigo_taller' => strtoupper($request->get('codigo_taller')),
            'username' => $request->get('username'),
            'password' => $request->get('password'),
            'description' => $request->get('description', 'Conecion de Base de Datos')
        ]);

        return redirect('/connections');
    }

    public function jsonCheckStatusConnection(Request $request, ConnectionService $connectionService)
    {
        $connection_id = $request->query->get('connection_id');

        if (!$connection_id) {
            return new JsonResponse([
                'status' => false,
                'error' => 'connection_id is required'
            ]);
        }

        $connection = $connectionService->setConnection($connection_id);

        if(!$connection) {
            return new JsonResponse([
                'status' => false,
                'connection_id' => $connection_id,
                'error' => 'Desconectado'
            ]);
        }

        return new JsonResponse([
            'status' => true,
            'connection_id' => $connection_id,
            'data' => $connection,
            'susses' => 'Coneccion establecida satisfactoriamente'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Connection $connection)
    {
        return "Hola a todos los que han entrado";
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Connection $connection)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Connection $connection)
    {
        //
    }
}
