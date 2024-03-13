<?php

namespace App\Livewire\Auto;

use App\Helpers\ResetDB;
use App\Models\Connection;
use App\Models\Mistral\Maestro;
use App\Services\NeumaticosService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Date;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public function __construct()
    {
        $this->neumaticosService = new NeumaticosService();

        $date = now();
        $this->endDateResumen = now()->format('Y-m-d');
        $this->startDateResumen = ($date->subMonth(1))->copy()->format('Y-m-d');
    }

    public $connectionId;

    public $search = 'T034015';

    public $resumenNeumaticos = [];
    public $resumenBaterias = [];

    public $startDateResumen;
    public $endDateResumen;


    public function mount() {
        $connection = (object) session('connection', Config::get('database.connections.taller'));

        if (isset($connection->host)) {
            $this->connectionId = Connection::where('database', $connection->database)->first()?->id;
        }
    }

    #[On('connection.reset')]
    public function render()
    {
        $connection = (object) session('connection', Config::get('database.connections.taller'));
        ResetDB::setDBConfig('taller', (array) $connection);
        $error = null;

        if ($this->search != '') {
            try {
                $query = Maestro::where('MATRICULA', '<>', '');
                $query = $query
                    ->where('MATRICULA', $this->search)
                    ->orWhere('MATRICULAANT', $this->search);

                $autos = $query->get();
            } catch (\Exception $exception) {
                $error = $exception->getMessage();
                $autos = [];
            }
        } else {
            $autos = [];
        }

        if(!$error) {
//            try {
//                $this->loadResumen();
//            } catch (\Exception $exception) {}
        } else {
            session()->remove('connection');
        }

        return view('livewire.auto.index', compact('autos', 'connection', 'error'));
    }

    public function updatedStartDateResumen()
    {
        try {
            $this->loadResumen();
        } catch (\Exception $exception) {}
    }

    public function loadResumen()
    {
        $end_date = Carbon::create($this->endDateResumen);
        $start_date = $this->startDateResumen ?: $end_date->copy()->subMonth(1);
        $this->resumenNeumaticos = $this->neumaticosService->resumenNeumaticosPorMesAnno($start_date, $this->endDateResumen);
        $this->resumenBaterias = $this->neumaticosService->resumenBaterias($start_date, $this->endDateResumen);
    }

    public function search()
    {
        $this->dispatch('connection.search');
    }
}
