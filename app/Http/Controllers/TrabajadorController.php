<?php

namespace App\Http\Controllers;

use App\Models\FCMToken;
use App\Models\Persona;
use App\Models\ServicioTrabajador;
use App\Models\Trabajador;
use App\Models\User;
use Illuminate\Http\Request;

class TrabajadorController extends Controller
{
    public function obtenerTrabajadores(Request $request)
    {
        $trabajador = Trabajador::select('trabajadores.*', 'personas.nombre', 'personas.ci', 'users.email')
            ->join('personas', 'personas.id', '=', 'trabajadores.persona_id')
            ->join('users', 'users.persona_id', '=', 'personas.id')
            ->where('personas.nombre', 'like', '%' . $request['nombre'] . '%');

        if ($request['estado'] != 't') {
            $trabajador = $trabajador->where('habilitado', $request['estado'])->get();
        } else {
            $trabajador = $trabajador->get();
        }

        return $trabajador;
    }

    public function obtenerTodos()
    {
    return Persona::select('personas.*','users.email')
        ->join('users','users.persona_id','=','personas.id')
        ->join('trabajadores','trabajadores.persona_id','=','personas.id')
        ->get();
    }

    public function aceptarTrabajadores(Request $request)
    {
        $trabajador = Trabajador::find($request['id']);
        $trabajador->habilitado = 'a';
        $trabajador->save();
        $this->enviarCorreo($request['email'], -1);
        $this->prepareNotification($request['id'], 'Tu cuenta ha sido aceptada');
    }

    public function rechazarTrabajadores(Request $request)
    {
        $trabajador = Trabajador::find($request['id']);
        $trabajador->habilitado = 'r';
        $trabajador->save();
        $this->enviarCorreo($request['email'], -2);
        $this->prepareNotification($request['id'], 'Tu cuenta ha sido rechazada');
    }

    public function detalleTrabajadores(Request $request)
    {
        $trabajador = Trabajador::select('trabajadores.*', 'personas.nombre', 'personas.ci', 'users.email', 'personas.img_perfil', 'personas.direccion', 'personas.telefono', 'personas.sexo')
            ->join('personas', 'personas.id', '=', 'trabajadores.persona_id')
            ->join('users', 'users.persona_id', '=', 'personas.id')
            ->where('trabajadores.id', $request['id'])
            ->first();
        $servicios = ServicioTrabajador::select('servicio_trabajador.*', 'servicios.nombre')
            ->join('servicios', 'servicio_trabajador.servicio_id', '=', 'servicios.id')
            ->where('servicio_trabajador.trabajador_id', $request['id'])
            ->get();
        $trabajador['servicios'] = $servicios;
        return $trabajador;
    }

    public function enviarCorreo($email, $id)
    {
        $details = [
            'title' => 'Confirmar correo electrónico',
            'body' => 'This is for testing email using smtp'
        ];
        \Illuminate\Support\Facades\Mail::to($email)->send(new \App\Mail\MailController($details, $id));
        // return response()->json(['email'=>'ok']);
    }


    public function prepareNotification($id, $description)
    {
        $usuario = User::select('users.*')
            ->join('personas', 'personas.id', '=', 'users.persona_id')
            ->join('trabajadores', 'personas.id', '=', 'trabajadores.persona_id')
            ->where('trabajadores.id', $id)
            ->first();

        $to = FCMToken::where('user_id', $usuario->id)->get();
        foreach ($to as $token) {
            $to = $token->token;
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
