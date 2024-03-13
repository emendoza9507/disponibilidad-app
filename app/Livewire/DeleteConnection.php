<?php

namespace App\Livewire;

use App\Models\Connection;
use Livewire\Component;

class DeleteConnection extends Component
{
    public $open = false;


    public Connection $connection;

    public function mount(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function render()
    {
        return view('livewire.delete-connection');
    }

    public function delete()
    {
        $this->connection->delete();
        $this->dispatch('connection.deleted');
    }
}
