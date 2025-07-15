<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DespachoController extends Controller
{
    public function getAlllc ()
    {
        return view('despacho.alllc');
    }
    public function getAllems ()
    {
        return view('despacho.allems');
    }
    public function getAllmx ()
    {
        return view('despacho.allmx');
    }
    public function getIniciar ()
    {
        return view('despacho.iniciar');
    }
    public function getIniciarems ()
    {
        return view('despacho.iniciarems');
    }
    public function getIniciarmx ()
    {
        return view('despacho.iniciarmx');
    }
    public function getExpedicion ()
    {
        return view('despacho.expedicion');
    }
    public function getExpedicionems ()
    {
        return view('despacho.expedicionems');
    }
    public function getExpedicionmx ()
    {
        return view('despacho.expedicionmx');
    }
    public function getAdmitir ()
    {
        return view('despacho.admitir');
    }
    public function getAdmitirems ()
    {
        return view('despacho.admitirems');
    }
    public function getAdmitirmx ()
    {
        return view('despacho.admitirmx');
    }
}