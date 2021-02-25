<?php

namespace App\Http\Controllers;

use App\Models\Trabajador;
use Illuminate\Http\Request;

class TrabajadorController extends Controller
{
    public function obtenerTrabajadores(Request $request){
        return Trabajador::all();
    }
}
