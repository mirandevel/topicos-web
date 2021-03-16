<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
public function dashboard(){
    $users = User::
    select(User::raw('DATE(created_at) as date'), User::raw('count(*) as cantidad'))
        ->groupBy('date')
        ->orderBy('date','asc')
        ->get();
    $i = 0;
    $fechas=[];
    $cantidades=[];
    foreach ($this->users as $user) {
        $fechas[$i] = $user->date;
        $cantidades[$i] = $user->cantidad;
        $i = $i + 1;
    }
    $datos['fechas']=$fechas;
    $datos['cantidades']=$cantidades;
    $datos['total_usuarios']=User::count();

    return $datos;
}
}
