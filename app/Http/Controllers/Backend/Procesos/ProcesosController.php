<?php

namespace App\Http\Controllers\Backend\Procesos;

use App\Http\Controllers\Controller;
use App\Models\Anios;
use App\Models\Fuentes;
use App\Models\Procesos;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProcesosController extends Controller
{

    public function __construct(){
        $this->middleware('auth');
    }

    public function vistaNuevoProceso()
    {
        $arrayFuente = Fuentes::orderBy('nombre', 'asc')
            ->where('visible', 1)
            ->get();

        foreach ($arrayFuente as $dato) {
            $infoAnio = Anios::where('id', $dato->id_anio)->first();

            $dato->nombreCompleto = "(" . $infoAnio->nombre . ") " . $dato->nombre;
        }

        return view('backend.admin.procesos.registro.vistaprocesos', compact('arrayFuente'));
    }


    public function nuevoRegistroProceso(Request $request)
    {
        $regla = array(
            'idfuente' => 'required',
            'numeroProceso' => 'required',
            'nombreProyecto' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        DB::beginTransaction();
        try {

            $registro = new Procesos();
            $registro->id_fuente  = $request->idfuente;
            $registro->numero_proceso  = $request->numeroProceso;
            $registro->nombre_proyecto  = $request->nombreProyecto;
            $registro->codigo_proyecto  = $request->codigoProyecto;
            $registro->numero_expediente  = $request->numeroExpediente;
            $registro->ampo  = $request->ampo;
            $registro->nombre_proceso  = $request->nombreProceso;
            $registro->save();

            DB::commit();
            return ['success' => 1];

        }catch(\Throwable $e){
            Log::info('error: ' . $e);
            DB::rollback();
            return ['success' => 99];
        }
    }




    public function vistaListadoProcesos()
    {
        $arrayFuente = Fuentes::where('visible', 1)
            ->orderBy('nombre', 'asc')
            ->get();

        foreach ($arrayFuente as $dato) {
            $info = Anios::where('id', $dato->id_anio)->first();
            $dato->nombreCompleto = "(" . $info->nombre . ") " . $dato->nombre;
        }

        return view('backend.admin.procesos.listado.vistalistaprocesos', compact('arrayFuente'));
    }


    public function tablaListadoProcesos($id)
    {
        $listado = Procesos::where('id_fuente', $id)
            ->orderBy('numero_proceso', 'asc')
            ->get();

        return view('backend.admin.procesos.listado.tablalistaprocesos', compact('listado'));
    }


    public function informacionProceso(Request $request)
    {
        $regla = array(
            'id' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        if($info = Procesos::where('id', $request->id)->first()){

            $arrayanios = Fuentes::orderBy('nombre', 'asc')->get();

            return ['success' => 1, 'info' => $info, 'arrayanios' => $arrayanios];
        }else{
            return ['success' => 2];
        }
    }









}
