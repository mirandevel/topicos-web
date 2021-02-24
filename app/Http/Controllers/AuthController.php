<?php

namespace App\Http\Controllers;


use App\Actions\Fortify\PasswordValidationRules;
use App\Http\Controllers\Controller;
use App\Models\Persona;
use App\Models\ServicioTrabajador;
use App\Models\Trabajador;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use phpDocumentor\Reflection\Types\False_;

class AuthController extends Controller
{
    use PasswordValidationRules;

    public function register(Request $request)
    {
        $validator=Validator::make($request->all(), [
            'nombre' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required'],


            'ci' => ['required'],
            'img_perfil' => ['required'],
            'direccion' => ['required'],
            'telefono' => ['required'],
            'sexo' => ['required'],
            'tipo' => ['required'],


            //'servicio_trabajador' => ['required'],



        ]);

        if(!$validator->fails()) {
            $persona = $this->crearPersona($request);

            $user = $this->crearUsuario($request,$persona->id);

            $trabajador = $this->crearTrabajador($persona->id);

            foreach ($request['servicio_trabajador'] as $item) {
                crearServicioTrabajador($trabajador->id, $item);
            }

            $this->sendEmail($request['email'], $user->id);

            return $this->convertirAJSON('Correo enviado');
           // return response()->json(['message' => 'Correo enviado']);
        }else{
            return $this->convertirAJSON($validator->errors());
            //return response()->json(['message'=>$validator->errors()]);
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
    public function crearTrabajador($persona_id){
        return Trabajador::create([
            'persona_id' => $persona_id,
            'habilitado' => false,
        ]);
    }

    public function crearServicioTrabajador($trabajador_id, $item){
        ServicioTrabajador::create([
            'fecha' => Carbon::now('America/La_Paz')->toDateString(),
            'dias' => $item->dias,//lun,mar,mie,jue
            'hora_inicio' => $item->hora_inicio,
            'hora_fin' => $item->hora_fin,

            'trabajador_id' => $trabajador_id,
            'servicio_id' => $item->servicio_id,
        ]);
    }

    public function convertirAJSON($mensaje){
        return response()->json(['message'=>$mensaje]);
    }

/*
 * DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=topicos
DB_USERNAME=postgres
DB_PASSWORD=5654
 */
    public function login(Request $request)
    {
        $validator=Validator::make($request->all(), [
            'email' => ['required', 'string', 'email'],
            'password' => ['required'],
        ]);
        if($validator->fails()){
            return response()->json(['error'=>$validator->errors()->first('email')]);
        }

        $credentials=request(['email','password']);

        if(!Auth::attempt($credentials)){
            return response()->json(['error'=>'datos invalidos']);
        }

        $user=User::where('email',$request->email)->first();
        $tokenResult=$user->createToken('authToken')->plainTextToken;
        return response()->json(['token'=>$tokenResult,
            'email'=>Auth::user()->email,
            'error'=>null,
            'id'=>$user->id,
            'verification'=>Auth::user()->email_verified_at]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['status_code'=>200,'message'=>'token deleted']);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        $value=$status === Password::RESET_LINK_SENT;
        return ['respuesta'=>__($status)];
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

