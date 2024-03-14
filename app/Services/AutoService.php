<?php

namespace App\Services;

use App\Helpers\ResetDB;
use App\Models\Mistral\Maestro;
use Illuminate\Support\Facades\Config;

class AutoService
{
    public function find($matricula)
    {
        $connection = (object) session('connection', Config::get('database.connections.taller'));
        ResetDB::setDBConfig('taller', (array) $connection);

        return Maestro::where('MATRICULA', 'like', '%'.$matricula.'%')
            ->orWhere('MATRICULAANT', 'like', '%'.$matricula.'%')
            ->get();
    }
}
