<?php

namespace App\Livewire\Neumatico;

use App\Helpers\ResetDB;
use App\Models\Connection;
use App\Models\Mistral\OrdenTrabajo;
use App\Models\Neumatico;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Livewire\Component;

class Index extends Component
{
    public $connectionId;

    public $matricula = 'T043129';

    public OrdenTrabajo $ot;

    public Collection $neumaticos;

    public function mount() {
        $connection = (object) session('connection', Config::get('database.connections.taller'));

        if (isset($connection->host)) {
            $this->connectionId = Connection::where('database', $connection->database)->first()?->id;
        }
    }

    public function render()
    {
        $connection = (object) session('connection', Config::get('database.connections.taller'));
        ResetDB::setDBConfig('taller', (array) $connection);

        $error = null;
        $ots = [];

        try {
            if($this->matricula != '') {
                $date = now();

                $ots = OrdenTrabajo::where('MATRICULA', $this->matricula)
                    ->where('FECHAENTRADA', '<', $date)
                    ->whereNull('FECHASALIDA')->get();
            }
        } catch (\Exception $exception) {
            $error = $exception->getMessage();
            session()->remove('connection');
        }

        return view('livewire.neumatico.index', compact('connection', 'ots', 'error'));
    }

    public function setOt(string $codigoot)
    {
        $connection = (object) session('connection', Config::get('database.connections.taller'));
        ResetDB::setDBConfig('taller', (array) $connection);
        $this->ot = OrdenTrabajo::where('CODIGOOT', $codigoot)->first();
    }

    public function checkNeumaticosCargados()
    {
        $materiales = $this->ot->materials;
        $cantidad = 0;

        foreach ($materiales as $material) {
            if (str_contains($material->AREA, 'A11')) {
                $cantidad += $material->CANTIDAD;
            }
        }

        return $cantidad;
    }

    public function generarNeumaticos(string $ot)
    {
        $connection = (object) session('connection', Config::get('database.connections.taller'));
        ResetDB::setDBConfig('taller', (array) $connection);

        $this->setOt($ot);
        $materiales = $this->ot->materials;
        $neumaticos = count(Neumatico::where('CODIGOOT', $this->ot->CODIGOOT)->get());
        $cantidad = $this->checkNeumaticosCargados();

        foreach ($materiales as $material) {
            if(str_contains($material->AREA, 'A11')) {
                if($neumaticos == $cantidad) {
                    return;
                }

                $cantidad = $cantidad - $neumaticos;

                for ($i = 0; $i < $cantidad; $i++) {
                    $neumatico = new Neumatico();
                    $neumatico->CODIGOOT = $this->ot->CODIGOOT;
                    $neumatico->TALLER = $this->ot->Prisma;
                    $neumatico->user_id = Auth::user()->id;

                    $neumatico->save();
                }
            }
        }
    }

    public function checkMaterialsOfNeumaticos(OrdenTrabajo $ot)
    {
        $materiales = $ot->materials;

        foreach ($materiales as $material) {
            if(str_contains($material->AREA, 'A11')) {
                return true;
            }
        }

        return false;
    }
}
