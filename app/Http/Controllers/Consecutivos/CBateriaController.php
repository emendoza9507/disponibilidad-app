<?php

namespace App\Http\Controllers\Consecutivos;

use App\Http\Controllers\Controller;
use App\Models\Bateria;
use App\Models\Connection;
use App\Services\AutoService;
use App\Services\BateriasService;
use App\Services\ConnectionService;
use App\Services\OrdenTrabajoService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CBateriaController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission.taller:tecnico')->only('store');
    }

    public function index(
        Request $request,
        AutoService $autoService,
        OrdenTrabajoService $ordenTrabajoService,
        ConnectionService $connectionService,
        BateriasService $bateriasService)
    {
        $end_date = $request->query->get('end_date') ? Carbon::create($request->query->get('end_date')) : Carbon::create(now());
        $start_date = $request->query->get('start_date') ? Carbon::create($request->query->get('start_date')) : $end_date->copy()->subDay(1);
        $connection_id = $request->query->get('connection_id', 1);
        $connection = $connectionService->setConnection($connection_id);
        $matricula = $request->query->get('matricula');

        $ordenes = [];
        $consecutivos_anteriores = [];

        try {
            $ordenes = $ordenTrabajoService->getAll($start_date, $end_date->clone()->addDay(1))
                ->with('materials')
                ->with('consecutivoBaterias')
                ->get();

            if($matricula) {
                $autos = $autoService->getBy($matricula);
                if(isset($autos[0])) {
                    $consecutivos_anteriores = $bateriasService->getByCodigoCodigoMaestro($autos[0]->CODIGOM);
                }

                foreach ($ordenes as $key => $ordenen) {
                    if (strtoupper($ordenen->MATRICULA) != strtoupper($matricula)) {
                        unset($ordenes[$key]);
                    }
                }
            }

            foreach ($ordenes as $key => &$orden) {
                $cant_baterias = $bateriasService->getCantidadBateriasCargadas($orden);

                if($cant_baterias == 0) {
                    unset($ordenes[$key]);
                } else {
                    $orden->baterias = $ordenTrabajoService->getMaterialesPorTipo($orden, 'A10');
                    $orden->cant_baterias = $cant_baterias;
                }
            }

        } catch (\Exception $exception) {
            self::warning($exception);
            return back();
        }

        return view('consecutivo.bateria.index', compact(
             'start_date', 'end_date', 'connection_id', 'connection',
            'ordenes','matricula', 'consecutivos_anteriores'
        ));
    }

    public function store(Request $request, ConnectionService $connectionService, OrdenTrabajoService $ordenTrabajoService, BateriasService $bateriasService)
    {
        $connection_id = $request->query->get('connection_id');
        $codigoot = $request->request->get('codigoot');
        $connectionService->setConnection($connection_id);

        $orden = $ordenTrabajoService->get($codigoot);

        $bateriasService->generarConsecutivosDeBaterias($orden);

        self::success('Consecutivos generados satisfactoriamente');
        return back();
    }

    public function all(Request $request, BateriasService $bateriasService)
    {
        $query = $request->query->get('query', '');
        $show = $request->query->getInt('show', 10);


        if($query) {
            $consecutivos = Bateria::where('CODIGOOT', 'LIKE', '%' . $query . '%')
                ->orWhere('CODIGOM', 'LIKE', '%' . $query . '%')
                ->orWhere('TALLER', 'LIKE', '%' . $query . '%')
                ->orWhere('id', 'LIKE',  substr($query, 2))
                ->orderBy('id', 'desc')
                ->paginate($show)
                ->withQueryString();
        } else {
            $consecutivos = Bateria::orderBy('id', 'desc')->paginate($show)->withQueryString();
        }

        return view('consecutivo.bateria.list', compact(
            'consecutivos', 'query'
        ));
    }

    public function show(Bateria $bateria)
    {
        return view('consecutivo.bateria.show', compact(
            'bateria'
        ));
    }
}
