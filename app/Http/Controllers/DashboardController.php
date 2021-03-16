<?php

namespace App\Http\Controllers;

use App\Models\Solicitud;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
public function dashboard(){
        $datos['usuarios']=$this->usuarios();
        $datos['solicitudes']=$this->solicitudes();
}

public function usuarios(){
    $users = User::select(User::raw('DATE(created_at) as date'), User::raw('count(*) as cantidad'))
        ->groupBy('date')
        ->orderBy('date','asc')
        ->get();

    $i = 0;
    $fechas=[];
    $cantidades=[];
    foreach ($users as $user) {
        $fechas[$i] = $user->date;
        $cantidades[$i] = $user->cantidad;
        $i = $i + 1;
    }
    $datos['fechas']=$fechas;
    $datos['cantidades']=$cantidades;
    $datos['total_usuarios']=User::count();

    return $datos;
}

    public function solicitudes(){
        $users = Solicitud::select(Solicitud::raw('DATE(created_at) as date'), Solicitud::raw('count(*) as cantidad'))
            ->groupBy('date')
            ->orderBy('date','asc')
            ->get();

        $i = 0;
        $fechas=[];
        $cantidades=[];
        foreach ($users as $user) {
            $fechas[$i] = $user->date;
            $cantidades[$i] = $user->cantidad;
            $i = $i + 1;
        }
        $datos['fechas']=$fechas;
        $datos['cantidades']=$cantidades;
        $datos['total_solicitudes']=Solicitud::count();

        return $datos;
    }
}