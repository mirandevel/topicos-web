<?php

namespace App\Http\Controllers;

use App\Models\Horario;
use Illuminate\Http\Request;

class DatosMaestrosController extends Controller
{
    public function obtenerHorarios(){
        return Horario::all();
    }
}
