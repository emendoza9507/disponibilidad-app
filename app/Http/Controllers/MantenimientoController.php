<?php

namespace App\Http\Controllers;

use App\Models\Mantenimiento;
use App\Http\Requests\StoreMantenimientoRequest;
use App\Http\Requests\UpdateMantenimientoRequest;
use App\Models\EstadoOT;
use App\Services\ConnectionService;
use App\Services\MantenimientoService;
use App\Services\OrdenTrabajoService;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class MantenimientoController extends Controller
{

    public function __construct() {
        $this->middleware('permission.taller:tecnico')->only('store', 'destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(
        Request $request,
        ConnectionService $connectionService,
        MantenimientoService $mantenimientoService,
        OrdenTrabajoService $ordenTrabajoService
    )
    {
        $connection_id = $request->query->get('connection_id', 1);
        $connection = $connectionService->setConnection($connection_id);

        $end_date = $request->query->get('end_date') ? Carbon::create($request->query->get('end_date'))->addDay() : Carbon::create(now());
        $start_date = $request->query->get('start_date') ? Carbon::create($request->query->get('start_date')) : $end_date->copy()->subMonth(1);

        if(!$connection) {
            self::warning('Taller desconectado. ('.$connectionService->getCurrentConnection()->name.')');
            return redirect(route('home'));
        }

        $query = $request->query->get('query');

        if($query) {
            $mantenimientos = $mantenimientoService->getAll($start_date, $end_date)->paginate();
        } else {
            $mantenimientos = $mantenimientoService->getByConnection($connection_id, $start_date, $end_date)->paginate()->withQueryString();
            $ordenes = $ordenTrabajoService->getAll($start_date, $end_date)->whereNotIn('ESTADO', EstadoOT::IDS_ESTADOS_ANULADOS)->get();
        }

        return view('mantenimiento.index', compact(
            'start_date', 'end_date',
            'connection', 'connection_id',
            'ordenes', 'mantenimientos'
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
    public function store(StoreMantenimientoRequest $request)
    {
        //
        $connection_id = $request->query->get('connection_id');
        $codigoot = $request->get('codigoot');
        $matricula = $request->get('matricula');
        $created_at = $request->get('created_at');

        $mantenimiento = new Mantenimiento([
            'codigoot' => $codigoot,
            'matricula' => $matricula,
            'connection_id' => $connection_id,
            'user_id' => auth()->user()->id,
            'created_at' => $created_at
        ]);

        $mantenimiento->save();

        if($request->ajax()) {
            return response()->json([
                'status' => true,
                'data' => $mantenimiento
            ]);
        }

        self::success('Mantenimiento creado satisfactoriamente.');
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Mantenimiento $mantenimiento)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Mantenimiento $mantenimiento)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMantenimientoRequest $request, Mantenimiento $mantenimiento)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        FormRequest $request,
        Mantenimiento $mantenimiento,
        ConnectionService $connectionService
    )
    {
        $connection_id = $request->query->getInt('connection_id');
        $connection = $connectionService->setConnection($connection_id);

        if(!$connection) {
            $message = 'Taller no disponible.';
            if($request->ajax()) {
                return response()->json([
                    'status' => false,
                    'message' => $message
                ]);
            }

            self::warning($message);
            return redirect(route('home'));
        }

        $mantenimiento->delete();
        $message = 'Mantenimiento eliminado satisfactoriamente.';

        if($request->ajax()) {
            return response()->json([
                'status' => true,
                'message' => $message
            ]);
        }

        self::success($message);
        return back();
    }
}
