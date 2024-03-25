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
use function Laravel\Prompts\select;

class AutoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(
        Request $request,
        ConnectionService $connectionService,
        AutoService $autoService
    )
    {
        $connection_id = $request->query->get('connection_id', 1);
        $connection = $connectionService->setConnection($connection_id);
        $matricula = $request->query->get('matricula');
        $marca = $request->query->get('marca');
        $modelo = $request->query->get('modelo');

        if(!$connection) {
            if($request->ajax()) {
                return new JsonResponse([
                    'status' => false,
                    'message' => 'Taller desconectado',
                    'connection' => $connectionService->getCurrentConnection()
                ]);
            }

            self::warning('Taller desconectado ('. $connectionService->getCurrentConnection()->name.')');
            return redirect(route('home'));
        }

        if($matricula) {
            $autos = $autoService->getBy($matricula);
        } else {
            $autos = $autoService->getByMarcaModelo($marca, $modelo, ['CODIGOM', 'TIPO', 'MATRICULA', 'MATRICULAANT'])->get();
        }

        if($request->ajax()) {

            return new JsonResponse([
                'status' => true,
                'data' => $autos
            ]);

        } else {
            $flota = $autoService->getResumenFlota()->get();

            return view('auto.index', compact(
                'connection_id','autos', 'matricula',
                'flota'
            ));
        }

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

    public function jsonflota(
        Request $request,
        ConnectionService $connectionService,
        AutoService $autoService
    )
    {
        $connection_id = $request->query->get('connection_id', 1);
        $connection = $connectionService->setConnection($connection_id);

        if(!$connection) {
            return new JsonResponse([
                'status' => false,
                'connection_id' => $connection_id,
                'connection' => $connectionService->getCurrentConnection()
            ]);
        }

        $flota = $autoService->getResumenFlota()->get();

        return new JsonResponse([
            'status' => true,
            'connection_id' => $connection_id,
            'connection' => $connection,
            'data' => $flota
        ]);
    }

    public function track(
        Request $request,
        ConnectionService $connectionService,
        AutoService $autoService,
        string $codigom
    )
    {
        $connection_id = $request->query->get('connection_id', 1);
        $connection = $connectionService->setConnection($connection_id);

        if(!$connection) {
            if($request->ajax()) {
                return new JsonResponse([
                    'status' => false,
                    'error' => 'Taller desconectado'
                ]);
            }

            self::warning('Taller desconectado ('. $connectionService->getCurrentConnection()->name .')');
            return redirect(route('home'));
        }


        $maestro = $autoService->getByMaestro($codigom);

        if(!$maestro) {
            if($request->ajax()) {
                return new JsonResponse([
                    'status' =>  true,
                    'maestro' => false,
                    'message' => 'El auto no existe'
                ]);
            }

            self::warning('El auto no existe');
            return  redirect(route('autos.index'));
        }

        $ot = $autoService->getLastOpenOt($codigom);

        if($request->ajax()) {
            return new JsonResponse([
                'status' => true,
                'ot' => $ot,
                'connection' => $connection
            ]);
        }

        return view('auto.track', compact(
            'connection_id', 'connection', 'ot', 'maestro'
        ));
    }
}
