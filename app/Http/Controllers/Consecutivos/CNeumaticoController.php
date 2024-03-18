<?php

namespace App\Http\Controllers\Consecutivos;

use App\Http\Controllers\Controller;
use App\Models\Connection;
use App\Models\Neumatico;
use App\Services\AutoService;
use App\Services\BateriasService;
use App\Services\ConnectionService;
use App\Services\NeumaticosService;
use App\Services\OrdenTrabajoService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CNeumaticoController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission.taller:tecnico')->only('store');
    }

    //
    public function index(
        Request $request,
        AutoService $autoService,
        OrdenTrabajoService $ordenTrabajoService,
        ConnectionService $connectionService,
        NeumaticosService $neumaticosService)
    {
        $end_date = $request->query->get('end_date') ? Carbon::create($request->query->get('end_date')) : Carbon::create(now());
        $start_date = $request->query->get('start_date') ? Carbon::create($request->query->get('start_date')) : $end_date->copy()->subDay(1);
        $connection_id = $request->query->get('connection_id', 1);
        $connection = $connectionService->setConnection($connection_id);
        $matricula = $request->query->get('matricula');
        $show = $request->query->get('show', 5);

        $ordenes = [];
        $consecutivos_anteriores = [];

        try {
            $ordenes = $ordenTrabajoService->getAll($start_date, $end_date->clone()->addDay(1))
            ->with('materials')
            ->with('consecutivoNeumaticos')
            ->get();

            if($matricula) {
                $autos = $autoService->getBy($matricula);
                if(isset($autos[0])) {
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

                if($cantidad_neumaticos == 0) {
                    unset($ordenes[$key]);
                } else {
                    $orden->neumaticos = $ordenTrabajoService->getMaterialesPorTipo($orden, 'A11');
                    $orden->cant_neumaticos = $cantidad_neumaticos;
                }
            }

        } catch (\Exception $exception) {
            self::warning($exception);
            return back();
        }

        return view('consecutivo.neumatico.index', compact(
            'start_date', 'end_date', 'connection_id', 'connection',
            'ordenes', 'matricula', 'consecutivos_anteriores'
        ));
    }

    public function store(Request $request, ConnectionService $connectionService, OrdenTrabajoService $ordenTrabajoService, NeumaticosService $neumaticosService)
    {
        $connection_id = $request->query->get('connection_id');
        $codigoot = $request->request->get('codigoot');
        $connectionService->setConnection($connection_id);

        $orden = $ordenTrabajoService->get($codigoot);

        $neumaticosService->generarConsecutivosDeNeumaticos($orden);

        self::success('Consecutivos generados satisfactoriamente');
        return back();
    }

    public function all(Request $request, NeumaticosService $neumaticosService)
    {
        $query = $request->query->get('query', '');
        $show = $request->query->getInt('show', 10);


        if($query) {
            $consecutivos = Neumatico::where('CODIGOOT', 'LIKE', '%' . $query . '%')
                                ->orWhere('CODIGOM', 'LIKE', '%' . $query . '%')
                                ->orWhere('TALLER', 'LIKE', '%' . $query . '%')
                                ->orWhere('id', 'LIKE',  substr($query, 2))
                                ->orderBy('id', 'desc')
                                ->paginate($show)
                                ->withQueryString();
        } else {
            $consecutivos = Neumatico::orderBy('id', 'desc')->paginate($show)->withQueryString();
        }

        return view('consecutivo.neumatico.list', compact(
            'consecutivos', 'query'
        ));
    }

    public function show(Neumatico $neumatico)
    {
        return view('consecutivo.neumatico.show', compact(
            'neumatico'
        ));
    }
}
