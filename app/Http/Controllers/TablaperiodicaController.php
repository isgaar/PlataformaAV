<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TablaperiodicaController extends Controller
{
    public function index()
    {
        return view('tablaperiodica.index');
    }
}
