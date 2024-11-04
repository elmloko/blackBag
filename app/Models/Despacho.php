<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Despacho extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'despacho';

    protected $fillable = [
        'ofdestino',
        'categoria',
        'subclase',
        'nroenvase',
        'nrodespacho',
        'peso',
        'identificador',
        'ano',
    ];
}
