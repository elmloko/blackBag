<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DespachoController extends Controller
{
    public function getIniciar ()
    {
        return view('despacho.iniciar ');
    }
}