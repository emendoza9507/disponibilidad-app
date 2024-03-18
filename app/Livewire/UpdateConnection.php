<?php

namespace App\Livewire;

use Livewire\Attributes\Validate;
use Livewire\Component;
use App\Models\Connection;

class UpdateConnection extends Component
{

    public $open = false;

    #[Validate('required')]
    public $name;

    #[Validate('required')]
    public $codigo_taller;

    #[Validate('required')]
    public $database;

    #[Validate('required')]
    public $hostname;

    #[Validate('required')]
    public $username;

    #[Validate('required')]
    public $password;

    #[Validate('required')]
    public $description;

    public Connection $connection;

    public function mount($connection) {
        $this->connection = $connection;
        $this->name = $this->connection->name;
        $this->codigo_taller = $this->connection->codigo_taller;
        $this->database = $this->connection->database;
        $this->hostname = $this->connection->hostname;
        $this->username = $this->connection->username;
        $this->password = $this->connection->password;
        $this->description = $this->connection->description;
    }

    public function render()
    {
        return view('livewire.update-connection');
    }

    public function save() {
        $this->connection->name = $this->name;
        $this->connection->codigo_taller = $this->codigo_taller;
        $this->connection->hostname = $this->hostname;
        $this->connection->database = $this->database;
        $this->connection->username = $this->username;
        $this->connection->password = $this->password;
        $this->connection->description = $this->description;

        $this->connection->save();

        $this->dispatch('connection.updated');
    }
}
