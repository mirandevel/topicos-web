<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trabajador extends Model
{
    use HasFactory;
    protected $table='trabajadores';
    public $timestamps=false;
    protected $fillable=[
        'persona_id',
    ];
}
