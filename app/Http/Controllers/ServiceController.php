<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function obtenerServicios(){

        return Servicio::all();
    }
}
