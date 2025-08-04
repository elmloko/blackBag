<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cn extends Model
{
    use HasFactory;

    protected $table = 'cn35';

    protected $fillable = [
        'despacho',
        'origen',
        'destino',
        'saca',
        'categoria',
        'subclase',
        'servicio',
        'tipo',
        'paquetes',
        'peso',
        'aduana',
        'codigo_manifiesto',
        'receptaculo',
        'identificador',
        'nrosaca',
        'etiqueta',
    ];

    protected $casts = [
        'despacho' => 'integer',
        'saca' => 'integer',
        'paquetes' => 'integer',
        'peso' => 'float',
    ];
}
