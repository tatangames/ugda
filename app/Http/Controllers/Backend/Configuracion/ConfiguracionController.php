<?php

namespace App\Http\Controllers\Backend\Configuracion;

use App\Http\Controllers\Controller;
use App\Models\Anios;
use App\Models\Empresas;
use App\Models\Fuentes;
use App\Models\Procesos;
use App\Models\ProcesosAdministrador;
use App\Models\ProcesosSolicitante;
use App\Models\ProcesosUcp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ConfiguracionController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    // *************** AÑO ************************


    public function vistaAnio(){
        return view('backend.admin.configuracion.anio.vistaanio');
    }

    public function vistaAnioTabla(){
        $listado = Anios::orderBy('nombre', 'ASC')->get();
        return view('backend.admin.configuracion.anio.tablaanio', compact('listado'));
    }

    public function nuevaAnio(Request $request){
        $regla = array(
            'nombre' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        DB::beginTransaction();
        try {

            $registro = new Anios();
            $registro->nombre = $request->nombre;
            $registro->save();

            DB::commit();
            return ['success' => 1];

        }catch(\Throwable $e){
            Log::info('error: ' . $e);
            DB::rollback();
            return ['success' => 99];
        }
    }

    public function informacionAnio(Request $request){
        $regla = array(
            'id' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        if($lista = Anios::where('id', $request->id)->first()){

            return ['success' => 1, 'info' => $lista];
        }else{
            return ['success' => 2];
        }
    }

    public function actualizarAnio(Request $request){
        $regla = array(
            'id' => 'required',
            'nombre' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        if(Anios::where('id', $request->id)->first()){

            Anios::where('id', $request->id)->update([
                'nombre' => $request->nombre,
            ]);

            return ['success' => 1];
        }else{
            return ['success' => 2];
        }
    }








    //************* FUENTE ****************************

    public function vistaFuente(){

        $arrayAnio = Anios::orderBy('nombre', 'ASC')->get();

        return view('backend.admin.configuracion.fuentes.vistafuentes', compact('arrayAnio'));
    }

    public function vistaFuenteTabla(){
        $listado = Fuentes::orderBy('nombre', 'ASC')->get();

        foreach ($listado as $item) {
            $infoAnio = Anios::where('id', $item->id_anio)->first();
            $item->nombreAnio = $infoAnio->nombre;
        }

        return view('backend.admin.configuracion.fuentes.tablafuentes', compact('listado'));
    }

    public function nuevaFuente(Request $request){
        $regla = array(
            'nombre' => 'required',
            'anio' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        DB::beginTransaction();
        try {

            $registro = new Fuentes();
            $registro->nombre = $request->nombre;
            $registro->id_anio = $request->anio;
            $registro->visible = 1;
            $registro->save();

            DB::commit();
            return ['success' => 1];

        }catch(\Throwable $e){
            Log::info('error: ' . $e);
            DB::rollback();
            return ['success' => 99];
        }
    }

    public function informacionFuente(Request $request){
        $regla = array(
            'id' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        if($lista = Fuentes::where('id', $request->id)->first()){

            $arrayAnio = Anios::orderBy('nombre', 'ASC')->get();

            return ['success' => 1, 'info' => $lista, 'arrayanios' => $arrayAnio];
        }else{
            return ['success' => 2];
        }
    }

    public function actualizarFuente(Request $request){
        $regla = array(
            'id' => 'required',
            'nombre' => 'required',
            'anio' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        if(Fuentes::where('id', $request->id)->first()){

            Fuentes::where('id', $request->id)->update([
                'nombre' => $request->nombre,
                'id_anio' => $request->anio,
            ]);

            return ['success' => 1];
        }else{
            return ['success' => 2];
        }
    }

    public function actualizarFuenteMostrar(Request $request)
    {
        $regla = array(
            'id' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        if(Fuentes::where('id', $request->id)->first()){

            Fuentes::where('id', $request->id)->update([
                'visible' => 1,
            ]);

            return ['success' => 1];
        }else{
            return ['success' => 2];
        }
    }

    public function actualizarFuenteOcultar(Request $request)
    {
        $regla = array(
            'id' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        if(Fuentes::where('id', $request->id)->first()){

            Fuentes::where('id', $request->id)->update([
                'visible' => 0,
            ]);

            return ['success' => 1];
        }else{
            return ['success' => 2];
        }
    }

    //*************** EMPRESAS ****************************


    public function vistaEmpresa(){
        return view('backend.admin.configuracion.empresas.vistaempresas');
    }

    public function vistaEmpresaTabla(){
        $listado = Empresas::orderBy('nombre')->get();
        return view('backend.admin.configuracion.empresas.tablaempresas', compact('listado'));
    }

    public function nuevaEmpresa(Request $request){
        $regla = array(
            'nombre' => 'required'
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        DB::beginTransaction();
        try {

            $registro = new Empresas();
            $registro->nombre = $request->nombre;
            $registro->save();

            DB::commit();
            return ['success' => 1];

        }catch(\Throwable $e){
            Log::info('error: ' . $e);
            DB::rollback();
            return ['success' => 99];
        }
    }

    public function informacionEmpresa(Request $request){
        $regla = array(
            'id' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        if($lista = Empresas::where('id', $request->id)->first()){

            return ['success' => 1, 'info' => $lista];
        }else{
            return ['success' => 2];
        }
    }

    public function actualizarEmpresa(Request $request){
        $regla = array(
            'id' => 'required',
            'nombre' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        if(Empresas::where('id', $request->id)->first()){

            Empresas::where('id', $request->id)->update([
                'nombre' => $request->nombre,
            ]);

            return ['success' => 1];
        }else{
            return ['success' => 2];
        }
    }




    //*********************** FILTROS **********************************

    public function indexVistaFiltros()
    {
        $arrayAnio = Anios::orderBy('nombre', 'asc')->get();

        return view('backend.admin.filtros.vistafiltros', compact('arrayAnio'));
    }


    public function indexBusquedaNoConsolidado($id)
    {
        return view('backend.admin.filtros.listado.vistanoconsolidado', compact('id'));
    }


    // PROCESOS LISTOS PARA CONSOLIDAR Y NO SE ACTIVADO EL BOOLEAN DEL CAMPO TABLA
    public function tablaBusquedaNoConsolidado($id)
    {
        $pilaArrayAnio = array();

        $arrayFuentes = Fuentes::where('id_anio', $id)->get();

        foreach ($arrayFuentes as $item){
            array_push($pilaArrayAnio, $item->id);
        }

        $listadoFiltro = Procesos::whereIn('id_fuente', $pilaArrayAnio)
            ->orderBy('numero_proceso', 'asc')
            ->get();

       // YA HAY EXPEDIENTE PERO NO ESTA CONSOLIDADO

        $pilaProcesos = array();

        foreach ($listadoFiltro as $item){
            $docSolitante = false;
            $docUcp = false;
            $docAdministrador = false;

            if(ProcesosSolicitante::where('id_proceso', $item->id)->first()){
                $docSolitante = true;
            }

            if(ProcesosUcp::where('id_proceso', $item->id)->first()){
                $docUcp = true;
            }

            if(ProcesosAdministrador::where('id_proceso', $item->id)->first()){
                $docAdministrador = true;
            }

            // NO ESTA CONSOLIDADO Y YA HAY EXPEDIENTES
            if($docSolitante && $docUcp && $docAdministrador){
                if($item->consolidado == 0){
                    array_push($pilaProcesos, $item->id);
                }
            }
        }

        $listado = Procesos::where('id', $pilaProcesos)
            ->orderBy('numero_proceso', 'asc')
            ->get();

        return view('backend.admin.filtros.listado.tablanoconsolidado', compact('listado'));
    }






}
