<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleSolicitud extends Model
{
    protected $table = 'detalle_solicitud';
    use HasFactory;
    protected $fillable = [
        'descripcion',
        'latitud',
        'longitud',
        'costo',
        'fecha',
        'solicitud_id',
        'servicio_id',
    ];
}
