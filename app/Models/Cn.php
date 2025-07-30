<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cn extends Model
{
    use HasFactory;

    protected $table = 'cn35'; // Nombre exacto de la tabla

    protected $fillable = [
        'despacho',
        'origen',
        'destino',
        'saca',
        'categoria',
        'subclase',
        'servicio',
        'paquetes',
        'peso',
        'aduana',
        'codigo_manifiesto',
    ];

}
