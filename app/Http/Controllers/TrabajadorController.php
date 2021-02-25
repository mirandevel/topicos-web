<?php

namespace App\Http\Controllers;

use App\Models\Trabajador;
use http\Client\Curl\User;
use Illuminate\Http\Request;

class TrabajadorController extends Controller
{
    public function obtenerTrabajadores(Request $request){
        $trabajador=Trabajador::select('trabajadores.*','personas.nombre','personas.ci','users.email')
            ->join('personas','personas.id','=','trabajadores.persona_id')
            ->join('users','users.persona_id','=','personas.id');

            if($request['estado']!='t'){
                $trabajador=$trabajador->where('habilitado',$request['estado'])->get();
            }else{
                $trabajador=$trabajador->get();
            }

        return $trabajador;
    }

    public function aceptarTrabajadores(Request $request){
        $trabajador=Trabajador::find($request['id']);
        $trabajador->habilitado='a';
        $trabajador->save();
    }
    public function rechazarTrabajadores(Request $request){
        $trabajador=Trabajador::find($request['id']);
        $trabajador->habilitado='r';
        $trabajador->save();
    }
}
