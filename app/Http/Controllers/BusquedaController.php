<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use App\Models\Servicio;
use Illuminate\Http\Request;

class BusquedaController extends Controller
{
    public function buscarServicios(Request $request){
        return Persona::select('personas.*')
            ->join('trabajadores','personas.id','=','trabajadores.persona_id')
            ->join('servicio_trabajador','servicio_trabajador.trabajador_id','=','trabajadores.id')
            ->join('servicios','servicio_trabajador.servicio_id','=','servicios.id')
            ->where('servicios.nombre', 'like', '%'.$request['busqueda'].'%')
            ->get();

    }
    public function seviciosLugares(){
        $servicios=Servicio::select('nombre')->get();
        $lugares=Persona::select('direccion')->get();
        return response()->json(['servicios'=>$servicios,'lugares'=>$lugares]);

    }
}
