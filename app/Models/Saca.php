<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Saca extends Model
{
    use HasFactory;

    // Nombre de la tabla
    protected $table = 'saca';

    // Llave primaria
    protected $primaryKey = 'id';

    // Indica que la llave primaria no es autoincremental
    public $incrementing = false;

    // Define el tipo de la llave primaria
    protected $keyType = 'string';

    // Campos asignables en el modelo
    protected $fillable = [
        'id',
        'nrosaca',
        'tipo',
        'peso',
        'nropaquetes',
        'despacho_id',
        'identificador',
        'estado',
        'etiqueta',
        'receptaculo',
    ];

    // Definir las relaciones
    public function despacho()
    {
        return $this->belongsTo(Despacho::class, 'despacho_id', 'id');
    }

    public function contenidos()
    {
        return $this->hasMany(Contenido::class, 'saca_id', 'id');
    }

    // Desactivar timestamps si no se desea manejar created_at y updated_at automáticamente
    public $timestamps = true;
}
