<?php

namespace App\Livewire\Departamento;

use App\Models\Departamento;
use Livewire\Component;

class Create extends Component
{

    public $open = false;

    public $codigodp;

    public $nombre;

    public $descripcion;

    public $direccion;

    public function render()
    {
        return view('livewire.departamento.create');
    }

    public function save()
    {
        $validated = $this->validate([
           'codigodp' => 'required|unique:departamentos',
           'nombre' => 'required|unique:departamentos',
           'descripcion' => 'required',
           'direccion' => 'required'
        ]);

        $departamento = Departamento::create($validated);

        $departamento->save();

        $this->dispatch('departamento.created');
        $this->open = false;
        $this->reset();
    }
}
