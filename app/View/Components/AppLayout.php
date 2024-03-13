<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{

    protected function menu() {
        return [
            (object) [
                'label' => 'Dashboard',
                'icon' => 'dashboard',
                'href' => 'dashboard'
            ],
            (object) [
                'label' => 'Connecciones',
                'icon' => 'connections',
                'href' => 'connections.index',
                'can' => 'connections.index'
            ],
            (object) [
                'label' => 'Usuarios',
                'icon' => 'users',
                'href' => 'users.index',
                'can' => 'users.index'
            ],
            (object) [
                'label' => 'Departamentos',
                'icon' => 'departments',
                'href' => 'departamentos.index',
                'can' => 'departamentos.index'
            ],
            (object) [
                'label' => 'Autos',
                'icon' => 'car',
                'href' => 'autos.index',
            ],
            (object) [
                'label' => 'Neumaticos',
                'icon' => 'settings',
                'href' => 'neumatico.index',
                'can' => 'neumatico.index'
            ]
        ];
    }

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        $menu = $this->menu();
        return view('layouts.app', compact('menu'));
    }
}
