<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class EmpleadorController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required'],


            'ci' => ['required'],
            'img_perfil' => ['required'],
            'direccion' => ['required'],
            'telefono' => ['required'],
            'sexo' => ['required'],
            'tipo' => ['required'],


        ]);

        if(!$validator->fails()) {
            $persona = $this->crearPersona($request);

            $user = $this->crearUsuario($request,$persona->id);


            $this->sendEmail($request['email'], $user->id);

            return $this->convertirAJSON('Correo enviado');

        }else{
            return $this->convertirAJSON($validator->errors());
        }
    }

    public function crearPersona(Request $request){
        return Persona::create([
            'nombre' => $request['nombre'],
            'ci' => $request['ci'],
            'img_perfil' => $request['img_perfil'],
            'estado' => 'A',
            'direccion' => $request['direccion'],
            'telefono' => $request['telefono'],
            'sexo' => $request['sexo'],
            'tipo' => $request['tipo'],
        ]);
    }
    public function crearUsuario(Request $request, $persona_id){
        return User::create([
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'persona_id' => $persona_id,
        ]);
    }


    public function convertirAJSON($mensaje){
        return response()->json(['message'=>$mensaje]);
    }

    public function sendEmail($email,$id)
    {
        $details = [
            'title' => 'Confirmar correo electrónico',
            'body' => 'This is for testing email using smtp'
        ];
        \Illuminate\Support\Facades\Mail::to($email)->send(new \App\Mail\MailController($details,$id));
        return response()->json(['email'=>'ok']);
    }
}
