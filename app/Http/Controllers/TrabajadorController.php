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
        /*
         public function crearServicioTrabajador($trabajador_id, $item){
        ServicioTrabajador::create([
            'fecha' => Carbon::now('America/La_Paz')->toDateString(),
            'dias' => $item["dias"],//lun,mar,mie,jue
            'hora_inicio' => $item["hora_inicio"],
            'hora_fin' => $item["hora_fin"],

            'trabajador_id' => $trabajador_id,
            'servicio_id' => $item["servicio_id"],
        ]);
    }
         */
    }
}
