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

class AuthController extends Controller
{
    use PasswordValidationRules;

    public function register(Request $request)
    {
        $validator=Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required'],
            'ciudad_id' => 'exists:App\Models\Pais,id'
        ]);

        if(!$validator->fails()) {


            $persona = Persona::create([
                'nombre' => $request['nombre'],
                'ci' => $request['ci'],
                'img_perfil' => $request['img_perfil'],
                'estado' => $request['estado'],
                'direccion' => $request['direccion'],
                'telefono' => $request['telefono'],
                'sexo' => $request['sexo'],
                'tipo' => $request['tipo'],
            ]);

            $user = User::create([
                'email' => $request['email'],
                'password' => Hash::make($request['password']),
                'persona_id' => $persona->id,
            ]);

            $trabajador = Trabajador::create([
                'persona_id' => $persona->id,
                'habilitado' => false,
            ]);

            foreach ($request['servicio_trabajador'] as $item) {
                ServicioTrabajador::create([
                    'fecha' => Carbon::now('America/La_Paz')->toDateString(),
                    'dias' => $item->dias,
                    'hora_inicio' => $item->hora_inicio,
                    'hora_fin' => $item->hora_fin,

                    'trabajador_id' => $trabajador->id,
                    'servicio_id' => $item->servicio_id,
                ]);
            }
            $this->sendEmail($request['email'], $user->id);

            return response()->json(['status_code' => 200, 'message' => 'Correo enviado']);
        }else{
            return response()->json(['status_code'=>400,'message'=>$validator->errors()]);
        }
    }

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

