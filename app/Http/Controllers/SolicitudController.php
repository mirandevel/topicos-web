<?php

namespace App\Http\Controllers;

use App\Models\DetalleSolicitud;
use App\Models\FCMToken;
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
            'costo'=>null,
            'fecha'=>Carbon::now('America/La_Paz')->toDateString(),
            'solicitud_id'=>$solicitud->id,
            'servicio_id'=>$request['servicio_id'],
        ]);

        $this->prepareNotification($request['trabajador_id'],'Tienes una nueva solicitud');

        return response()->json(['mensaje'=>'Solicitud creada']);
    }



    public function prepareNotification($id,$description){
        $usuario=User::select('users.*')
            ->join('personas','personas.id','=','users.persona_id')
            ->join('trabajadores','personas.id','=','trabajadores.persona_id')
            ->where('trabajadores.id',$id)
            ->first();

        $to = FCMToken::where('user_id',$usuario->id)->get();
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
