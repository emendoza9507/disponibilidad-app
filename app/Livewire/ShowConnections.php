<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Connection;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class ShowConnections extends Component
{
    use WithPagination;

    public $listeners = ['connection.updated'];

    #[On('connection.updated')]
    #[On('connection.deleted')]
    public function render()
    {

        $connections = Connection::paginate(5);

        return view('livewire.show-connections', compact('connections'));
    }
}
