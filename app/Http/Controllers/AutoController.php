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
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AutoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, ConnectionService $connectionService, AutoService $autoService)
    {
        $connection_id = $request->query->get('connection_id',1);
        $connection = $connectionService->setConnection($connection_id);;
        $limit = $request->query->getInt('limit', 100);
        $matricula = $request->query->get('matricula');

        if(!$connection) return redirect(route('home'));

        $autos = [];

        if(isset($matricula)) {
            try {
                $autos = $autoService->getBy($matricula);
            } catch (\Exception $exception) {
                return redirect(route('autos.index'))->with('error', $exception->getMessage());
            }
        } else {
            $autos = $autoService->getLastAutos($limit);
        }

//        $flota = $autoService->getResumenFlota()->get();

        return view('auto.index', compact(
            'connection_id','autos', 'matricula'
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
    public function show(Request $request, string $auto, OrdenTrabajoService $ordenTrabajoService, ConnectionService $connectionService)
    {
        $connection_id = $request->query->get('connection_id', 1);
        $connection = $connectionService->setConnection($connection_id, function () { back(); });;

        $ordenes = [];
        try {
            $auto = Maestro::find($auto);

            $ordenes = $ordenTrabajoService->getByMaestro($auto->CODIGOM);
        } catch (\Exception $exception) {
            return redirect(route('autos.index'))->with('error', $exception->getMessage());
        }

        return view('auto.show', compact(
            'auto', 'ordenes',  'connection_id', 'connection'
        ));
    }

    public function jsonShow(Request $request, string $auto, OrdenTrabajoService $ordenTrabajoService, ConnectionService $connectionService)
    {
        $connection_id = $request->query->get('connection_id');
        $connection = $connectionService->setConnection($connection_id);

        if(!$connection) {
            return new JsonResponse([
                'taller' => $connectionService->getCurrentConnection()->codigo_taller,
                'total' => 0,
                'abiertas' => 0,
                'cerradas' => 0,
                'conectado' => false
            ]);
        }

        try {
            $auto = Maestro::find($auto);

            $ordenes_total = $ordenTrabajoService->getByMaestro($auto->CODIGOM)->count();
            $ordenes_abiertas =  $ordenTrabajoService->getByMaestroAbiertas($auto->CODIGOM)->count();
            $ordenes_cerradas =  $ordenTrabajoService->getByMaestroCerradas($auto->CODIGOM)->count();
        } catch (\Exception $exception) {
            return redirect(route('autos.index'))->with('error', $exception->getMessage());
        }

        return new JsonResponse([
            'taller' => $connection->codigo_taller,
            'total' => $ordenes_total,
            'abiertas' => $ordenes_abiertas,
            'cerradas' => $ordenes_cerradas,
            'conectado' => true
        ]);
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
