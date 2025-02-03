<?php

namespace App\Http\Controllers\Backend\Procesos;

use App\Http\Controllers\Controller;
use App\Models\Fuentes;
use Illuminate\Http\Request;

class ProcesosController extends Controller
{

    public function __construct(){
        $this->middleware('auth');
    }

    public function vistaNuevoProceso()
    {
        $arrayFuente = Fuentes::orderBy('nombre', 'asc')->get();

        return view('backend.admin.procesos.registro.vistaprocesos', compact('arrayFuente'));
    }
}
