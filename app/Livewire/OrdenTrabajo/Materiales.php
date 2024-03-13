<?php


namespace App\Livewire\OrdenTrabajo;

use App\Helpers\ResetDB;
use App\Models\Mistral\Maestro;
use App\Models\Mistral\Material;
use App\Models\Mistral\OrdenTrabajo;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Livewire\Component;


class Materiales extends Component
{
    public $open = false;

    public string $codigoot;

    public OrdenTrabajo $ot;

    public function render()
    {
        $connection = (object) session('connection', Config::get('database.connections.taller'));
        ResetDB::setDBConfig('taller', (array) $connection);

        $this->ot = OrdenTrabajo::where('CODIGOOT', $this->codigoot)->first();

        try {
            $materiales = Material::where('CODIGOOT', $this->ot->CODIGOOT)->get();
            $entrada = $this->ot->FECHAENTRADA;
            $salida = $this->ot->FECHASALIDA;
        } catch (\Exception $exception) {
            $materiales = [];
            $entrada = '';
            $salida = '';
        }

        return view('livewire.orden-trabajo.materiales', compact('materiales', 'entrada', 'salida'));
    }
}
