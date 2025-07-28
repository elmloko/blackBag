<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EtiquetasController extends Controller
{
    public function getCn38 ()
    {
        return view('etiquetas.cn38');
    }

    public function getCn35 ()
    {
        return view('etiquetas.cn35');
    }
}