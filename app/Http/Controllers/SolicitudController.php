<?php

namespace App\Http\Controllers;

use App\Models\DetalleSolicitud;
use App\Models\Solicitud;
use App\Models\Trabajador;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SolicitudController extends Controller
{
    public function obtenerSolicitudesTrabajador(Request $request){
        $trabajador=Trabajador::select('trabajadores.*')
            ->join('personas','personas.id','=','trabajadores.persona_id')
            ->join('users','users.persona_id','=','personas.id')
            ->where('trabajadores.id',$request->user()->id)
            ->first();

        Solicitud::select('detalle_solicitud.*','solicitudes.estado')
            ->join('detalle_solicitud','solicitudes.id','=','detalle_solicitud.solicitud_id')
            ->where('solicitudes.trabajador_id',$trabajador->id)
            ->get();
    }

    public function crearSolicitud(Request $request){

        $solicitud=Solicitud::create([
            'estado'=>'p',
            'trabajador_id'=>$request['trabajador_id'],
            'persona_id'=>1,
        ]);

        $detalle=DetalleSolicitud::create([
            'descripcion'=>$request['descripcion'],
            'ubicacion'=>$request['ubicacion'],
            'costo'=>$request['costo'],
            'fecha'=>Carbon::now('America/La_Paz')->toDateString(),
            'solicitud_id'=>$solicitud->id,
            'servicio_id'=>$request['servicio_id'],
        ]);
        return response()->json(['mensaje'=>'Solicitud creada']);
    }
}
