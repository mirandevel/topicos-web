<?php

namespace App\Http\Controllers;

use App\Models\DetalleSolicitud;
use App\Models\FCMToken;
use App\Models\Persona;
use App\Models\Solicitud;
use App\Models\Trabajador;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SolicitudController extends Controller
{
    public function obtenerSolicitudesTrabajador(Request $request){
        $trabajador=Trabajador::select('trabajadores.*')
            ->join('personas','personas.id','=','trabajadores.persona_id')
            ->join('users','users.persona_id','=','personas.id')
            ->where('users.id',$request->user()->id)
            ->first();

        return Solicitud::select('detalle_solicitud.*','solicitudes.estado')
            ->join('detalle_solicitud','solicitudes.id','=','detalle_solicitud.solicitud_id')
            ->where('solicitudes.trabajador_id',$trabajador->id)
            ->get();

    }
public function detalles(Request $request){
    $solicitudes= Solicitud::select('detalle_solicitud.*','solicitudes.estado','solicitudes.trabajador_id','solicitudes.persona_id','servicios.nombre')
        ->join('detalle_solicitud','solicitudes.id','=','detalle_solicitud.solicitud_id')
        ->join('servicios','servicios.id','=','detalle_solicitud.servicio_id')
        ->where('solicitudes.id',$request['solicitud_id'])
        ->get();
    foreach ($solicitudes as $solicitud){
        $solicitud['empleador']=Persona::find($solicitud['persona_id']);
        $solicitud['trabajador']=Persona::select('personas.*')
            ->join('trabajadores','personas.id','trabajadores.id')
            ->where('trabajadores.id',$solicitud->trabajador_id)
            ->first();
    }

    return $solicitudes;

}
    public function obtenerTodasSolicitudes(){
        return Solicitud::select('detalle_solicitud.*','solicitudes.estado','servicios.nombre')
            ->join('detalle_solicitud','solicitudes.id','=','detalle_solicitud.solicitud_id')
            ->join('servicios','servicios.id','=','detalle_solicitud.servicio_id')
            ->get();
    }
    public function aceptarRechazar(Request $request){
        $detalle=DetalleSolicitud::where('id',$request['detalle_id'])->first();
        $detalle->costo=$request['costo'];
        $detalle->save();

        $solicitud=Solicitud::where('id',$request['solicitud_id'])->first();
        $solicitud->estado=$request['estado']; //a aceptado,r rechazado
        $solicitud->save();

        return response()->json(['mensaje'=>'Se cambio el estado de la solicitud']);

    }

    public function crearSolicitud(Request $request){
        $empleador=Persona::where('tipo','like','%E%')
            ->first();
        $solicitud=Solicitud::create([
            'estado'=>'p',
            'trabajador_id'=>$request['trabajador_id'],
            'persona_id'=>$empleador->id,
        ]);

        $detalle=DetalleSolicitud::create([
            'descripcion'=>$request['descripcion'],
            'latitud'=>$request['latitud'],
            'longitud'=>$request['longitud'],
            'costo'=>null,
            'fecha'=>Carbon::now('America/La_Paz')->toDateString(),
            'solicitud_id'=>$solicitud->id,
            'servicio_id'=>$request['servicio_id'],
        ]);

        $usuario=User::select('users.*')
            ->join('personas','personas.id','=','users.persona_id')
            ->join('trabajadores','personas.id','=','trabajadores.persona_id')
            ->where('trabajadores.id',$request['trabajador_id'])
            ->first();

        $this->prepareNotification($usuario->id,'Tienes una nueva solicitud');

        return response()->json(['mensaje'=>'Solicitud creada']);
    }


    public function historial(){
        $empleador=Persona::where('tipo','E')
            ->first();

        return Solicitud::select('detalle_solicitud.*','solicitudes.estado','servicios.nombre')
            ->join('detalle_solicitud','solicitudes.id','=','detalle_solicitud.solicitud_id')
            ->join('servicios','servicios.id','=','detalle_solicitud.servicio_id')
            ->where('solicitudes.persona_id',$empleador->id)
            ->get();
    }

    public function prepareNotification($id,$description){


        $to = FCMToken::where('user_id',$id)->get();
        foreach ($to as $token){
            $to=$token->token;
            $notification = array(
                'title' => "Nueva tarea",
                'body' => $description
            );
            $notification = array('to' => $to, 'notification' => $notification);
            $this->sendNotification($notification);
        }

    }
    public function sendNotification($notification)
    {
//$to = "/topics/tournaments";



        //$this->sendNotif($to, $notification);
        //$feilds = array('registration_ids' => $to, 'notification' => $notification);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($notification));

        $headers = array();
        $headers[] = 'Authorization: Key= AAAALGBzQTk:APA91bED_xjXkZQQNRqC55O1p8T3D2zREvTCu8CKwo7kHuzEaFDo5s_oaSVKXdU4VMfbDudmd0S6mJVriMJgO4_gCntKhP-X5lFEZ5-StNffdFrzOSwRe6czeDOU0GN1izeoUswg6yxY';
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
    }
}
