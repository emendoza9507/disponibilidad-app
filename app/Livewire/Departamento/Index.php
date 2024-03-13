<?php

namespace App\Livewire\Departamento;

use App\Models\Departamento;
use Livewire\Attributes\On;
use Livewire\Component;

class Index extends Component
{
    #[On('departamento.created'), On('departamento.updated'), On('departamento.deleted')]
    public function render()
    {
        $departamentos = Departamento::paginate(5);

        return view('livewire.departamento.index', compact('departamentos'));
    }
}
