<?php

namespace App\Http\Controllers;

use App\Models\Connection;
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
