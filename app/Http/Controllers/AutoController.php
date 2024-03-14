<?php

namespace App\Http\Controllers;

use App\Models\Auto;
use App\Http\Requests\StoreAutoRequest;
use App\Http\Requests\UpdateAutoRequest;
use App\Models\Connection;
use App\Models\Mistral\Maestro;
use App\Services\AutoService;
use App\Services\ConnectionService;
use App\Services\OrdenTrabajoService;
use Illuminate\Http\Request;

class AutoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, ConnectionService $connectionService, AutoService $autoService)
    {
        //
        $connections = Connection::all();
        $connection_id = $request->query->get('connection_id') ?: $connections[0]->id;
        $connectionService->setConnection($connection_id);

        $matricula = $request->query->get('matricula');

        $autos = [];

        if(isset($matricula)) {
            try {
                $autos = $autoService->find($matricula);
            } catch (\Exception $exception) {}
        }

        return view('auto.index', compact(
            'connections', 'connection_id',
            'autos', 'matricula'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAutoRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Maestro $auto, OrdenTrabajoService $ordenTrabajoService, ConnectionService $connectionService)
    {
        $session_connection = session('connection');
        $connections = Connection::all();
        $connection_id = $request->query->get('connection_id') ?: (isset($session_connection['connection_id']) ? $session_connection['connection_id'] :  $connections[0]->id);
        $connectionService->setConnection($connection_id);

        $ordenes = [];
        try {
            $ordenes = $ordenTrabajoService->getByMaestro($auto->CODIGOM);
        } catch (\Exception $exception) {}

        return view('auto.show', compact(
            'auto', 'ordenes', 'connections', 'connection_id'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Auto $auto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAutoRequest $request, Auto $auto)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Auto $auto)
    {
        //
    }
}
