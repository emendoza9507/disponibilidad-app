<?php

namespace App\Livewire\OrdenTrabajo;

use App\Helpers\ResetDB;
use App\Models\Connection;
use App\Models\Mistral\Maestro;
use App\Models\Mistral\Material;
use App\Models\Mistral\OrdenTrabajo;
use Illuminate\Support\Facades\Config;
use Livewire\Component;

class Index extends Component
{

    public $open = false;

    public $openMaterial = false;

    public Maestro $maestro;

    public OrdenTrabajo $ot;

    public function mount(Maestro $maestro)
    {
        $this->maestro = $maestro;
    }

    public function render()
    {
        $connection = (object) session('connection', Config::get('database.connections.taller'));
        ResetDB::setDBConfig('taller', (array) $connection);

        $ots = OrdenTrabajo::where('CODIGOM', $this->maestro->CODIGOM)->orderBy('FECHAENTRADA', 'DESC')->get();

        $materials = [];
        if(isset($this->ot)) {
            $materials = Material::where('CODIGOOT', $this->ot->CODIGOOT)->get();
        }

        return view('livewire.orden-trabajo.index', compact('ots', 'materials'));
    }

    public function showMaterial(string $ot)
    {
        $connection = (object) session('connection', Config::get('database.connections.taller'));
        ResetDB::setDBConfig('taller', (array) $connection);

        $this->ot = OrdenTrabajo::where('CODIGOOT', $ot)->first();
    }
}
