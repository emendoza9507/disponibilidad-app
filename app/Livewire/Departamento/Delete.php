<?php

namespace App\Livewire\Departamento;

use App\Models\Departamento;
use Livewire\Component;

class Delete extends Component
{
    public $open = false;

    public Departamento $departamento;

    public function mount(Departamento $departamento) {
        $this->departamento = $departamento;
    }

    public function render()
    {
        return view('livewire.departamento.delete');
    }

    public function delete()
    {
        $this->departamento->delete();

        $this->open = false;
        $this->dispatch('departamento.deleted');
    }
}
