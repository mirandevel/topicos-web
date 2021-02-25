<?php

namespace App\Http\Controllers;

use App\Models\Trabajador;
use Illuminate\Http\Request;

class TrabajadorController extends Controller
{
    public function obtenerTrabajadores(Request $request){
        return Trabajador::select('trabajadores.*','personas.nombre','personas.ci','users.email')
            ->join('personas','personas.id','=','trabajadores.persona_id')
            ->join('users','users.persona_id','=','personas.id')
            ->get();
    }
}
