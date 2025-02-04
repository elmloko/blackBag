<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contenido extends Model
{
    use HasFactory;

    protected $table = 'contenido';

    protected $fillable = [
        'descripcion',
        'listas',
        'nropaquetesro',
        'nropaquetesbl',
        'nropaquetesems',
        'nropaquetescp',
        'nropaquetesco',
        'nropaquetessn',
        'nropaquetessu',
        'nropaqueteset',
        'nropaquetesii',
        'nropaquetesof',
        'sacasm',
        'lcao',
        'saca_id',
        'correotradicional',
        'encomiendas',
        'enviotrans',
    ];

    protected $casts = [
        'peso' => 'float',
    ];

    public $timestamps = true;

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    // Define the relationship with the Saca model if necessary
    public function saca()
    {
        return $this->belongsTo(Saca::class, 'saca_id');
    }
}
