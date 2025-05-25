<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CursoController extends Controller
{

    public function realizacion()
    {
        return view('cursos.realizacion');
    }

    public function resultado()
    {
        return view('cursos.resultado');
    }
}
