<?php

namespace App\Http\Controllers;

use App\Models\ServicioTrabajador;
use App\Models\Trabajador;
use http\Client\Curl\User;
use Illuminate\Http\Request;

class TrabajadorController extends Controller
{
    public function obtenerTrabajadores(Request $request){
        $trabajador=Trabajador::select('trabajadores.*','personas.nombre','personas.ci','users.email')
            ->join('personas','personas.id','=','trabajadores.persona_id')
            ->join('users','users.persona_id','=','personas.id')
            ->where('personas.nombre', 'like', '%'.$request['nombre'].'%');

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
        $this->enviarCorreo($request['email'],-1);
    }
    public function rechazarTrabajadores(Request $request){
        $trabajador=Trabajador::find($request['id']);
        $trabajador->habilitado='r';
        $trabajador->save();
        $this->enviarCorreo($request['email'],-2);
    }

    public function detalleTrabajadores(Request $request){
        $trabajador=Trabajador::select('trabajadores.*','personas.nombre','personas.ci','users.email','personas.img_perfil','personas.direccion','personas.telefono','personas.sexo')
            ->join('personas','personas.id','=','trabajadores.persona_id')
            ->join('users','users.persona_id','=','personas.id')
            ->where('trabajadores.id',$request['id'])
            ->first();
        $servicios=ServicioTrabajador::select('servicio_trabajador.*','servicios.nombre')
            ->join('servicios','servicio_trabajador.servicio_id','=','servicios.id')
            ->where('servicio_trabajador.trabajador_id',$request['id'])
            ->get();
        $trabajador['servicios']=$servicios;
        return $trabajador;
    }

    public function enviarCorreo($email,$id)
    {
        $details = [
            'title' => 'Confirmar correo electrónico',
            'body' => 'This is for testing email using smtp'
        ];
        \Illuminate\Support\Facades\Mail::to($email)->send(new \App\Mail\MailController($details,$id));
       // return response()->json(['email'=>'ok']);
    }
}
