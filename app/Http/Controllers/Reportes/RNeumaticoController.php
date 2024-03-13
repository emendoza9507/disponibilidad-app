<?php

namespace App\Http\Controllers\Reportes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RNeumaticoController extends Controller
{
    //
    public function index()
    {
        return view('reportes.neumatico.index');
    }
}
