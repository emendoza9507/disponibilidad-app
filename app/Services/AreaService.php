<?php

namespace App\Services;

use App\Models\Mistral\Area;

class AreaService
{
    public function getAll()
    {
        return Area::orderBy('DESCRIPCION')->get();
    }
}
