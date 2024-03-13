<?php

namespace App\Livewire\Departamento;

use App\Models\Departamento;
use Livewire\Component;

class Update extends Component
{

    public Departamento $departamento;

    public $open = false;

    public $codigodp;

    public $nombre;

    public $descripcion;

    public $direccion;

    public function mount(Departamento $departamento)
    {
        $this->departamento = $departamento;

        $this->codigodp = $departamento->codigodp;
        $this->nombre = $departamento->nombre;
        $this->descripcion = $departamento->descripcion;
        $this->direccion = $departamento->direccion;
    }

    public function render()
    {
        return view('livewire.departamento.update');
    }

    public function save()
    {
        $validated = $this->validate([
            'codigodp' => 'required|unique:departamentos,codigodp,'.$this->departamento->codigodp.',codigodp',
            'nombre' => 'required|unique:departamentos,nombre,'.$this->departamento->codigodp.',nombre',
            'descripcion' => 'required',
            'direccion' => 'required'
        ]);

        $this->departamento->fill($validated);
        $this->departamento->save();

        $this->open = false;
        $this->dispatch('departamento.updated');
    }
}
