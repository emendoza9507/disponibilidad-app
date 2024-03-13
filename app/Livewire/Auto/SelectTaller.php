<?php

namespace App\Livewire\Auto;

use App\Helpers\ResetDB;
use App\Models\Connection;
use Illuminate\Support\Facades\Config;
use Livewire\Component;

class SelectTaller extends Component
{

    public $open = false;

    public $connectionId;

    public $route;


    public function render()
    {
        $connections = Connection::all();
        return view('livewire.auto.select-taller', compact('connections'));
    }

    public function mount($connectionId, $route)
    {
        $this->connectionId = $connectionId;
        $this->route = $route;
    }

    public function import()
    {
        $connection = Connection::findOrFail($this->connectionId);

        ResetDB::setDBConfig('taller', [
            'host' => $connection->hostname,
            'port' => '1433',
            'database' => $connection->database,
            'username' => $connection->username,
            'password' => $connection->password
        ]);

        session(['connection' => Config::get('database.connections.taller')]);

        $this->redirect(route($this->route));
    }
}
