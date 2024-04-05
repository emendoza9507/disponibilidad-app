<?php

namespace App\Http\Controllers\Consecutivos;

use App\Http\Controllers\Controller;
use App\Models\Connection;
use App\Models\Mistral\Material;
use App\Models\Mistral\OrdenTrabajo;
use App\Models\Neumatico;
use App\Services\AutoService;
use App\Services\BateriasService;
use App\Services\ConnectionService;
use App\Services\NeumaticosService;
use App\Services\OrdenTrabajoService;
use Carbon\Carbon;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CNeumaticoController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission.taller:tecnico')->only('store', 'edit', 'update');
    }

    //
    public function index(
        Request             $request,
        AutoService         $autoService,
        OrdenTrabajoService $ordenTrabajoService,
        ConnectionService   $connectionService,
        NeumaticosService   $neumaticosService)
    {
        $end_date = $request->query->get('end_date') ? Carbon::create($request->query->get('end_date')) : Carbon::create(now());
        $start_date = $request->query->get('start_date') ? Carbon::create($request->query->get('start_date')) : $end_date->copy()->subMonth(1);
        $connection_id = $request->query->get('connection_id', 1);
        $connection = $connectionService->setConnection($connection_id, function () {
            back();
        });;
        $matricula = $request->query->get('matricula');
        $show = $request->query->get('show', 5);

        if (!$connection) return redirect(route('home'));

        $ordenes = [];
        $consecutivos_anteriores = [];

        try {
            $ordenes = $ordenTrabajoService->getAll($start_date, $end_date->clone()->addDay(1))
                ->with('materials')
                ->with('consecutivoNeumaticos')
                ->get();

            if ($matricula) {
                $autos = $autoService->getBy($matricula);
                if (isset($autos[0])) {
                    $consecutivos_anteriores = $neumaticosService->getByCodigoCodigoMaestro($autos[0]->CODIGOM, $show);
                }

                foreach ($ordenes as $key => $ordenen) {
                    if (strtoupper($ordenen->MATRICULA) != strtoupper($matricula)) {
                        unset($ordenes[$key]);
                    }
                }
            }

            foreach ($ordenes as $key => &$orden) {
                $cantidad_neumaticos = $neumaticosService->getCantidadNeumaticosCargados($orden);

                if ($cantidad_neumaticos == 0) {
                    unset($ordenes[$key]);
                } else {
                    $orden->neumaticos = $ordenTrabajoService->getMaterialesPorArea($orden, 'A11');
                    $orden->cant_neumaticos = $cantidad_neumaticos;
                }
            }

        } catch (\Exception $exception) {
            self::warning($exception);
            return back();
        }

        return view('consecutivo.neumatico.index',
        [
            'auto' => $matricula ? $autoService->getBy($matricula)->first() : null
        ]
        ,compact(
            'start_date', 'end_date', 'connection_id', 'connection',
            'ordenes', 'matricula', 'consecutivos_anteriores'
        ));
    }

    public function store(Request $request, ConnectionService $connectionService, OrdenTrabajoService $ordenTrabajoService, NeumaticosService $neumaticosService)
    {
        $connection_id = $request->query->get('connection_id');
        $codigoot = $request->request->get('codigoot');
        $connection = $connectionService->setConnection($connection_id);

        if(!$connection) {
            return redirect(route('home'));
        }

        $orden = $ordenTrabajoService->get($codigoot);

        $neumaticosService->generarConsecutivosDeNeumaticos($orden);

        self::success('Consecutivos generados satisfactoriamente');
        return back();
    }

    public function all(Request $request, NeumaticosService $neumaticosService)
    {
        $query = $request->query->get('query', '');
        $show = $request->query->getInt('show', 10);


        if ($query) {
            $consecutivos = Neumatico::where('CODIGOOT', 'LIKE', '%' . $query . '%')
                ->orWhere('CODIGOM', 'LIKE', '%' . $query . '%')
                ->orWhere('TALLER', 'LIKE', '%' . $query . '%')
                ->orWhere('id', 'LIKE', substr($query, 2))
                ->orderBy('id', 'desc')
                ->with('connection')
                ->paginate($show)
                ->withQueryString();
        } else {
            $consecutivos = Neumatico::orderBy('id', 'desc')->paginate($show)->withQueryString();
        }

        return view('consecutivo.neumatico.list', compact(
            'consecutivos', 'query'
        ));
    }

    public function jsonAll(Request $request)
    {
        $query = $request->query->get('query', '');

        return new JsonResponse(Neumatico::where('id', 'LIKE', '%'.$query.'%')->get());
    }

    public function show(Request $request, Neumatico $neumatico)
    {
        $connection_id = $request->query->get('connection_id');
        return view('consecutivo.neumatico.show', compact(
            'neumatico', 'connection_id'
        ));
    }

    public function edit(Request $request, Neumatico $neumatico)
    {
        $connection_id = $request->query->get('connection_id');
        return view('consecutivo.neumatico.edit', compact(
            'neumatico', 'connection_id'
        ));
    }

    public function update(Request $request, Neumatico $neumatico)
    {
        $connection_id = $request->query->get('connection_id');
        $validated = $request->validate([
            'anterior' => 'exists:neumaticos,id|nullable',
            'cons_manual' => 'unique:neumaticos,cons_manual|nullable'
        ]);

        $neumatico->neumatico_anterior = $validated['anterior'];
        $neumatico->cons_manual = $validated['cons_manual'];

        $neumatico->save();

        self::success('Consecutivo guardado satisfactoriamente');
        return redirect(route('consecutivo.neumatico.show', [$neumatico->id, 'connection_id' => $connection_id]));
    }

    public function showMaestro(Request $request, string $maestro, ConnectionService
    $connectionService, AutoService $autoService
    )
    {
        $connection_id = $request->query->get('connection_id', 1);
        $connection = $connectionService->setConnection($connection_id);
        if(!$connection) {
            return  redirect(route('home'));
        }

        $maestro = $autoService->getByMaestro($maestro);

        if(!$maestro) {
            return redirect(route('consecutivo.neumatico.index', $request->query->all()));
        }

        return view('consecutivo.neumatico.show_maestro', compact(
           'maestro'
        ));
    }

    public function jsonUltimaOTConNeumaticos(
        Request $request,
        string $codigom,
        NeumaticosService $neumaticosService,
        ConnectionService $connectionService,
        OrdenTrabajoService $ordenTrabajoService
    ) {
        $connection_id = $request->query->get('connection_id');

        if(!$connection_id) {
            return new JsonResponse([
               'status' => false,
               'data' => null,
                'error' => 'connection_id is required'
            ]);
        }

        $connection = $connectionService->setConnection($connection_id);

        if(!$connection) {
            return new JsonResponse([
                'status' => false,
                'data' => null,
                'taller' => $connectionService->getCurrentConnection()->codigo_taller
            ]);
        }
        $orden = $ordenTrabajoService->getLastOtWithMaterialType('A11', $codigom);

        return new JsonResponse([
            'status' => true,
            'data' => $orden,
            'taller' => $connection->codigo_taller,
            'connection_id' => $connection->id
        ]);
    }
}
