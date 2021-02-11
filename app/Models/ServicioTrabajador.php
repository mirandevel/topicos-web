<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicioTrabajador extends Model
{
    use HasFactory;

    protected $table = 'servicio_trabajador';
    public $timestamps = false;
    protected $fillable = [
        'fecha',
        'dias',
        'hora_inicio',
        'hora_fin',

        'trabajador_id',
        'servicio_id',
    ];
}
