<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use App\Models\Servicio;
use App\Models\ServicioTrabajador;
use App\Models\Trabajador;
use Illuminate\Http\Request;

class BusquedaController extends Controller
{
    public function buscarServicios(Request $request){

        $personas=Persona::select('personas.*')
            ->join('trabajadores','personas.id','=','trabajadores.persona_id')
            ->join('servicio_trabajador','servicio_trabajador.trabajador_id','=','trabajadores.id')
            ->join('servicios','servicio_trabajador.servicio_id','=','servicios.id')
            ->where('servicios.nombre',$request['servicio'])
            ->where('trabajadores.habilitado','a')
            ->where('personas.direccion',$request['direccion']);

            if($request['turno']=='mañana'){
               $personas=$personas->whereBetween('servicio_trabajador.hora_inicio',['00:00:00','11:59:59']);
            }else{
                $personas=$personas->whereBetween('servicio_trabajador.hora_fin',['12:00:00','23:59:59']);
            }
            //->whereBetween($request['hora'],['servicio_trabajador.hora_inicio','servicio_trabajador.hora_fin']);
            $personas=$personas->distinct()
            ->get();


            return $personas;

    }
    public function seviciosLugares(){
        $servicios=Servicio::select('nombre')->distinct()->get();
        $lugares=Persona::select('direccion')->distinct()->get();
        return response()->json(['servicios'=>$servicios,'lugares'=>$lugares]);
    }

    public function detalleTrabajadores(Request $request){
        $trabajador=Trabajador::select('trabajadores.*','personas.nombre','personas.ci','users.email','personas.img_perfil','personas.direccion','personas.telefono','personas.sexo')
            ->join('personas','personas.id','=','trabajadores.persona_id')
            ->join('users','users.persona_id','=','personas.id')
            ->where('personas.id',$request['id'])
            ->first();
        $servicios=ServicioTrabajador::select('servicio_trabajador.*','servicios.nombre')
            ->join('servicios','servicio_trabajador.servicio_id','=','servicios.id')
            ->where('servicio_trabajador.trabajador_id',$trabajador->id)
            ->get();
        $trabajador['servicios']=$servicios;
        return $trabajador;
    }
}
