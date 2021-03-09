<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solicitud extends Model
{
    protected $table = 'solicitudes';
    use HasFactory;

    protected $fillable = [
        'estado',
        'trabajador_id',
        'persona_id',
    ];
}
