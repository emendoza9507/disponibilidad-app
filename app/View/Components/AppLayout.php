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
                'label' => 'Consecutivos',
                'icon' => 'consecutive',
                'dropdown' => [
                    (object) [
                        'label' => 'Baterias',
                        'icon' => 'battery',
                        'href' => 'consecutivo.bateria.index'
                    ],
                    (object) [
                        'label' => 'Neumaticos',
                        'icon' => 'lifebuoy',
                        'href' => 'consecutivo.neumatico.index'
                    ]
                ]
            ],
            (object) [
                'label' => 'Taller',
                'icon' => 'taller',
                'dropdown' => [
                    (object) [
                        'label' => 'Control Acceso',
                        'icon' => 'qr',
                        'href' => 'control_acceso.index'
                    ],
                    (object) [
                        'label' => 'Mantenimientos',
                        'icon' => 'settings',
                        'href' => 'mantenimiento.index'
                    ]
                ]
            ],
            (object) [
                'label' => 'Reportes',
                'icon' => 'report',
                'dropdown' => [
                    (object) [
                        'label' => 'Ordenes de Trabajo',
                        'icon' => 'documents',
                        'href' => 'orden.index',
                    ],
                    (object) [
                        'label' => 'Ordenes Abiertas',
                        'icon' => 'lock-open',
                        'href' => 'reporte.ordenes.index',
                        'params' => [null,'estado' => 1]
                    ],
                    (object) [
                        'label' => 'Ordenes Cerradas',
                        'icon' => 'lock-closed',
                        'href' => 'reporte.ordenes.index',
                        'params' => [null,'estado' => 3]
                    ],
                    (object) [
                        'label' => 'Prod. en Proceso',
                        'icon' => 'prod-proceso',
                        'href' => 'reporte.proceso.index',
                    ],
                    (object) [
                        'label' => 'Mantenimientos',
                        'icon' => 'settings',
                        'href' => 'reporte.mantenimiento.index',
                    ],
                    (object) [
                        'label' => 'Baterias',
                        'icon' => 'battery',
                        'href' => 'reporte.bateria.index'
                    ],
                    (object) [
                        'label' => 'Neumaticos',
                        'icon' => 'lifebuoy',
                        'href' => 'reporte.neumatico.index'
                    ]
                ]
            ],
            (object) [
                'label' => 'Configuracion',
                'icon' => 'settings',
                'can' => 'admin.app',
                'dropdown' => [
                    (object) [
                        'label' => 'Conecciones',
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
                ]
            ],
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
