<?php

namespace App\Http\Controllers\Backend\Procesos;

use App\Http\Controllers\Controller;
use App\Models\Anios;
use App\Models\Empresas;
use App\Models\Fuentes;
use App\Models\Procesos;
use App\Models\ProcesosAdministrador;
use App\Models\ProcesosSolicitante;
use App\Models\ProcesosUcp;
use App\Models\ProcesosUcpEmpresa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;

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
            $registro->consolidado = 0;
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

        $primerId = optional($arrayFuente->first())->id;

        foreach ($arrayFuente as $dato) {
            $info = Anios::where('id', $dato->id_anio)->first();
            $dato->nombreCompleto = "(" . $info->nombre . ") " . $dato->nombre;
        }

        return view('backend.admin.procesos.listado.vistalistaprocesos', compact('arrayFuente', 'primerId'));
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



    public function editarProceso(Request $request)
    {
        $regla = array(
            'id' => 'required',
            'idfuente' => 'required',
            'numeroProceso' => 'required',
            'nombreProyecto' => 'required',
            'toggle' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        if(Procesos::where('id', $request->id)->first()){

            Procesos::where('id', $request->id)->update([
                'id_fuente' => $request->idfuente,
                'numero_proceso' => $request->numeroProceso,
                'nombre_proyecto' => $request->nombreProyecto,
                'codigo_proyecto' => $request->codigoProyecto,
                'numero_expediente' => $request->numeroExpediente,
                'ampo' => $request->ampo,
                'nombre_proceso' => $request->nombreProceso,
                'consolidado' => $request->toggle,
            ]);

            return ['success' => 1];
        }else{
            return ['success' => 2];
        }
    }

    public function borrarProcesoCompleto(Request $request)
    {
        $regla = array(
            'id' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        if(Procesos::where('id', $request->id)->first()){

            DB::beginTransaction();

            try {

                // BORRAR DOCUMENTOS

                // PROCESOS SOLICI
                $arrayProSolicitante = ProcesosSolicitante::where('id_proceso', $request->id)->get();
                foreach ($arrayProSolicitante as $item){
                    if($item->documento != null){
                        if (Storage::disk('archivos')->exists($item->documento)) {
                            Storage::disk('archivos')->delete($item->documento);
                        }
                    }
                }

                $arrayProUcp = ProcesosUcp::where('id_proceso', $request->id)->get();
                foreach ($arrayProUcp as $item){
                    if($item->documento != null){
                        if (Storage::disk('archivos')->exists($item->documento)) {
                            Storage::disk('archivos')->delete($item->documento);
                        }
                    }
                }


                $arrayProAdministrador = ProcesosAdministrador::where('id_proceso', $request->id)->get();
                foreach ($arrayProAdministrador as $item){
                    if($item->documento != null){
                        if (Storage::disk('archivos')->exists($item->documento)) {
                            Storage::disk('archivos')->delete($item->documento);
                        }
                    }
                }

                // BORRAR FILAS

                ProcesosAdministrador::where('id_proceso', $request->id)->delete();

                foreach ($arrayProUcp as $item){
                    ProcesosUcpEmpresa::where('id_procesoucp', $item->id)->delete();
                }

                ProcesosUcp::where('id_proceso', $request->id)->delete();
                ProcesosSolicitante::where('id_proceso', $request->id)->delete();
                Procesos::where('id', $request->id)->delete();

                DB::commit();
                return ['success' => 1];

            }catch(\Throwable $e){
                Log::info('error: ' . $e);
                DB::rollback();
                return ['success' => 99];
            }
        }else{
            return ['success' => 2];
        }
    }


    public function informacionDocumentos(Request $request)
    {
        $regla = array(
            'id' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}


        // INFORMACION DE SOLICITANTE
        $totalSolicitante = ProcesosSolicitante::where('id_proceso', $request->id)->count();
        // INFORMACION DE UCP
        $totalUcp = ProcesosUcp::where('id_proceso', $request->id)->count();
        // INFORMACION DE ADMINISTRADOR
        $totalAdministrador = ProcesosAdministrador::where('id_proceso', $request->id)->count();

        return ['success' => 1,
            'totalSolicitante' => $totalSolicitante,
            'totalUCP' => $totalUcp,
            'totalAdministrador' => $totalAdministrador,

        ];
    }


    // CONSOLIDADO FINAL

    public function consolidadoProcesoFinal($id){

        if(ProcesosSolicitante::where('id_proceso', $id)->first()){

            // **** ORDEN DE LOS DOCUMENTOS A UNIR *****
            $documentosArray = [];

            // SOLICITANTE
            $arraySolicitantes = ProcesosSolicitante::where('id_proceso', $id)
                ->orderBy('fecha_entrega', 'ASC')
                ->get();

            foreach ($arraySolicitantes as $item){
                if($item->documento != null){
                    $documentosArray[] = [
                        'ruta' => storage_path("app/public/archivos/" . $item->documento),
                        'nombre' => $item->texto_documento
                    ];
                }
            }


            // UCP
            $arrayUcp = ProcesosUcp::where('id_proceso', $id)
                ->orderBy('fecha_entrega', 'ASC')
                ->get();

            foreach ($arrayUcp as $item){
                if($item->documento != null){
                    $documentosArray[] = [
                        'ruta' => storage_path("app/public/archivos/" . $item->documento),
                        'nombre' => $item->texto_documento
                    ];                }
            }


            // ADMINISTRADOR
            $arrayAdministrador = ProcesosAdministrador::where('id_proceso', $id)
                ->orderBy('fecha_entrega', 'ASC')
                ->get();

            foreach ($arrayAdministrador as $item){
                if($item->documento != null){
                    $documentosArray[] = [
                        'ruta' => storage_path("app/public/archivos/" . $item->documento),
                        'nombre' => $item->texto_documento
                    ];
                }
            }


            // Crear instancia de mPDF
            $mpdf = new Mpdf();

            foreach ($documentosArray as $documento) {
                if (file_exists($documento['ruta'])) {

                    // AGREGAR PAGINA DE TEXTO
                    if($documento['nombre'] != null){
                        // Agregar página en blanco con texto en medio
                        $mpdf->AddPage();
                        $mpdf->SetFont('Arial', 'B', 40);
                        $offset = 90;
                        $mpdf->SetXY(10, ($mpdf->h / 2) - $offset);
                        // Ajustar interlineado aumentando la altura de línea
                        $lineHeight = 18; // Puedes probar con 40 o 60 según el resultado
                        $mpdf->MultiCell(190, $lineHeight, $documento['nombre'], 0, 'C');
                    }

                    // Importar PDF al documento principal
                    $pageCount = $mpdf->setSourceFile($documento['ruta']);
                    for ($i = 1; $i <= $pageCount; $i++) {
                        $tplId = $mpdf->importPage($i);
                        $mpdf->AddPage();
                        $mpdf->useTemplate($tplId);
                    }
                }
            }

            // Enviar PDF al navegador sin guardarlo
            $mpdf->Output('Consolidado.pdf', Destination::INLINE);

        }else{
            return "Archivo no encontrado";
        }
    }




    //************ VISTAS PARA SOLICITANTES  ************************

    public function indexProcesoSolicitante($id)
    {
        return view('backend.admin.procesos.listado.solicitante.vistasolicitante', compact('id'));
    }


    public function tablaProcesoSolicitante($id)
    {
        $listado = ProcesosSolicitante::where('id_proceso', $id)
            ->orderBy('fecha_entrega', 'asc')
            ->get();

        foreach ($listado as $dato) {
            $dato->fechaFormat = date("d-m-Y", strtotime($dato->fecha_entrega));
        }

        return view('backend.admin.procesos.listado.solicitante.tablasolicitante', compact('listado'));
    }


    public function nuevoProcesoSolicitante(Request $request)
    {
        $regla = array(
            'id' => 'required',
            'fecha' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        // SIEMPRE VENDRA DOCUMENTO
        $cadena = Str::random(15);
        $tiempo = microtime();
        $union = $cadena . $tiempo;
        $nombre = str_replace(' ', '_', $union);

        $extension = '.' . $request->documento->getClientOriginalExtension();
        $nomDocumento = $nombre . strtolower($extension);
        $avatar = $request->file('documento');
        $archivo = Storage::disk('archivos')->put($nomDocumento, \File::get($avatar));

        if($archivo){

            DB::beginTransaction();
            try {

                $registro = new ProcesosSolicitante();
                $registro->id_proceso = $request->id;
                $registro->fecha_entrega = $request->fecha;
                $registro->documento = $nomDocumento;
                $registro->nombre_documento = $request->nombreDocumento;
                $registro->folio = $request->folio;
                $registro->texto_documento = $request->textoDocumento;
                $registro->save();


                DB::commit();
                return ['success' => 1];
            }catch(\Throwable $e){
                DB::rollback();
                return ['success' => 99];
            }
        }else{
            return ['success' => 99];
        }
    }

    public function borrarProcesoSolicitante(Request $request)
    {
        $regla = array(
            'id' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        if($infoPro = ProcesosSolicitante::where('id', $request->id)->first()){

            $documentoOld = $infoPro->documento;

            // borrar documento anterior
            if (Storage::disk('archivos')->exists($documentoOld)) {
                Storage::disk('archivos')->delete($documentoOld);
            }

            ProcesosSolicitante::where('id', $request->id)->delete();

            return ['success' => 1];
        }else{
            return ['success' => 2];
        }
    }


    public function informacionProcesoSolicitante(Request $request)
    {
        $regla = array(
            'id' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        if($infoPro = ProcesosSolicitante::where('id', $request->id)->first()){

            return ['success' => 1, 'info' => $infoPro];
        }
        else{
            return ['success' => 2];
        }
    }


    public function editarProcesoSolicitante(Request $request)
    {
        $regla = array(
            'id' => 'required',
            'fecha' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        if ($request->hasFile('documento')) {

            $infoPro = ProcesosSolicitante::where('id', $request->id)->first();
            $documentoOld = $infoPro->documento;

            $cadena = Str::random(15);
            $tiempo = microtime();
            $union = $cadena . $tiempo;
            $nombre = str_replace(' ', '_', $union);

            $extension = '.' . $request->documento->getClientOriginalExtension();
            $nomDocumento = $nombre . strtolower($extension);
            $avatar = $request->file('documento');
            $archivo = Storage::disk('archivos')->put($nomDocumento, \File::get($avatar));

            if($archivo){
                ProcesosSolicitante::where('id', $request->id)->update([
                    'fecha_entrega' => $request->fecha,
                    'nombre_documento' => $request->nombreDocumento,
                    'folio' => $request->folio,
                    'texto_documento' => $request->textoDocumento,
                    'documento' => $nomDocumento,
                ]);

                if (Storage::disk('archivos')->exists($documentoOld)) {
                    Storage::disk('archivos')->delete($documentoOld);
                }

                return ['success' => 1];
            }
            else{
                return ['success' => 99];
            }
        }else{
            ProcesosSolicitante::where('id', $request->id)->update([
                'fecha_entrega' => $request->fecha,
                'nombre_documento' => $request->nombreDocumento,
                'folio' => $request->folio,
                'texto_documento' => $request->textoDocumento,
            ]);

            return ['success' => 1];
        }
    }


    public function visualizarDocSolicitante($id)
    {
        $infoPro = ProcesosSolicitante::where('id', $id)->first();
        $pathToFile = storage_path("app/public/archivos/" . $infoPro->documento);

        if (!file_exists($pathToFile)) {
            abort(404, "El archivo no existe.");
        }

        return response()->file($pathToFile, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="Documento.pdf"' // Aquí forzamos el nombre
        ]);
    }


    public function descargarDocSolicitante($id)
    {
      $infoPro = ProcesosSolicitante::where('id', $id)->first();
      $pathToFile = "storage/archivos/" . $infoPro->documento;
      $extension = pathinfo(($pathToFile), PATHINFO_EXTENSION);

      $nombre = "Documento";
      if($infoPro->nombre_documento != null){
          $nombre = $infoPro->nombre_documento;
      }

      $nombreFinal = $nombre . "." . $extension;
      return response()->download($pathToFile, $nombreFinal);
    }


    //*********** PROCESOS UCP *****************************************


    public function indexProcesoUcp($id)
    {
        $arrayEmpresas = Empresas::orderBy('nombre', 'ASC')->get();
        return view('backend.admin.procesos.listado.ucp.vistaucp', compact('id', 'arrayEmpresas'));
    }


    public function tablaProcesoUcp($id)
    {
        $listado = ProcesosUcp::where('id_proceso', $id)
            ->orderBy('fecha_entrega', 'asc')
            ->get();

        foreach ($listado as $dato) {

            $dato->fechaFormat = date("d-m-Y", strtotime($dato->fecha_entrega));

            if($dato->estado == 0){
                $dato->nombreEstado = "Adjudicado";
            }else{
                $dato->nombreEstado = "Desierto";
            }

            $arrayEmpresa = ProcesosUcpEmpresa::where('id_procesoucp', $dato->id)->get();

            $nombreEmpresas = "";
            foreach ($arrayEmpresa as $item) {
                $infoEmpre = Empresas::where('id', $item->id_empresa)->first();
                $nombreEmpresas .= "Empresa: " . $infoEmpre->nombre . "\n";
            }
            $dato->nombreEmpresas = $nombreEmpresas;
        }

        return view('backend.admin.procesos.listado.ucp.tablaucp', compact('listado'));
    }


    public function nuevoProcesoUcp(Request $request)
    {
        $regla = array(
            'id' => 'required',
            'fecha' => 'required',
            'estado' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}


        // SIEMPRE VENDRA DOCUMENTO
        $cadena = Str::random(15);
        $tiempo = microtime();
        $union = $cadena . $tiempo;
        $nombre = str_replace(' ', '_', $union);

        $extension = '.' . $request->documento->getClientOriginalExtension();
        $nomDocumento = $nombre . strtolower($extension);
        $avatar = $request->file('documento');
        $archivo = Storage::disk('archivos')->put($nomDocumento, \File::get($avatar));

        if($archivo){

            DB::beginTransaction();
            try {

                $datosContenedor = json_decode($request->empresas, true);

                $registro = new ProcesosUcp();
                $registro->id_proceso = $request->id;
                $registro->fecha_entrega = $request->fecha;
                $registro->documento = $nomDocumento;
                $registro->nombre_documento = $request->nombreDocumento;
                $registro->folio = $request->folio;
                $registro->texto_documento = $request->textoDocumento;
                $registro->estado = $request->estado;
                $registro->save();

                // Registrar la empresa

                if (!empty($datosContenedor)) {
                    foreach ($datosContenedor as $filaArray) {

                        $detalle = new ProcesosUcpEmpresa();
                        $detalle->id_procesoucp = $registro->id;
                        $detalle->id_empresa = $filaArray;
                        $detalle->save();

                    }
                }

                DB::commit();
                return ['success' => 1];
            }catch(\Throwable $e){
                Log::info("error: " . $e);
                DB::rollback();
                return ['success' => 99];
            }
        }else{
            return ['success' => 99];
        }
    }


    public function borrarProcesoUcp(Request $request)
    {
        $regla = array(
            'id' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        if($infoPro = ProcesosUcp::where('id', $request->id)->first()){

            $documentoOld = $infoPro->documento;

            // borrar documento anterior
            if (Storage::disk('archivos')->exists($documentoOld)) {
                Storage::disk('archivos')->delete($documentoOld);
            }

            ProcesosUcpEmpresa::where('id_procesoucp', $request->id)->delete();
            ProcesosUcp::where('id', $request->id)->delete();

            return ['success' => 1];
        }else{
            return ['success' => 2];
        }
    }


    public function informacionProcesoUcp(Request $request)
    {
        $regla = array(
            'id' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        if($infoPro = ProcesosUcp::where('id', $request->id)->first()){

            return ['success' => 1, 'info' => $infoPro];
        }
        else{
            return ['success' => 2];
        }
    }



    public function editarProcesoUcp(Request $request)
    {
        $regla = array(
            'id' => 'required',
            'fecha' => 'required',
            'estado' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        if ($request->hasFile('documento')) {

            $infoPro = ProcesosUcp::where('id', $request->id)->first();
            $documentoOld = $infoPro->documento;

            $cadena = Str::random(15);
            $tiempo = microtime();
            $union = $cadena . $tiempo;
            $nombre = str_replace(' ', '_', $union);

            $extension = '.' . $request->documento->getClientOriginalExtension();
            $nomDocumento = $nombre . strtolower($extension);
            $avatar = $request->file('documento');
            $archivo = Storage::disk('archivos')->put($nomDocumento, \File::get($avatar));

            if($archivo){
                ProcesosUcp::where('id', $request->id)->update([
                    'fecha_entrega' => $request->fecha,
                    'nombre_documento' => $request->nombreDocumento,
                    'folio' => $request->folio,
                    'texto_documento' => $request->textoDocumento,
                    'documento' => $nomDocumento,
                    'estado' => $request->estado
                ]);

                if (Storage::disk('archivos')->exists($documentoOld)) {
                    Storage::disk('archivos')->delete($documentoOld);
                }

                return ['success' => 1];
            }
            else{
                return ['success' => 99];
            }
        }else{
            ProcesosUcp::where('id', $request->id)->update([
                'fecha_entrega' => $request->fecha,
                'nombre_documento' => $request->nombreDocumento,
                'folio' => $request->folio,
                'texto_documento' => $request->textoDocumento,
                'estado' => $request->estado
            ]);

            return ['success' => 1];
        }
    }



    public function visualizarDocUcp($id)
    {
        $infoPro = ProcesosUcp::where('id', $id)->first();
        $pathToFile = storage_path("app/public/archivos/" . $infoPro->documento);

        if (!file_exists($pathToFile)) {
            abort(404, "El archivo no existe.");
        }

        return response()->file($pathToFile, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="Documento.pdf"' // Aquí forzamos el nombre
        ]);
    }


    public function descargarDocUcp($id)
    {
        $infoPro = ProcesosUcp::where('id', $id)->first();
        $pathToFile = "storage/archivos/" . $infoPro->documento;
        $extension = pathinfo(($pathToFile), PATHINFO_EXTENSION);

        $nombre = "Documento";
        if($infoPro->nombre_documento != null){
            $nombre = $infoPro->nombre_documento;
        }

        $nombreFinal = $nombre . "." . $extension;
        return response()->download($pathToFile, $nombreFinal);
    }



    //*********** PROCESOS UCP - EMPRESAS *****************************************


    public function indexProcesoUcpEmpresas($id)
    {
        $arrayEmpresas = Empresas::orderBy('nombre', 'ASC')->get();
        return view('backend.admin.procesos.listado.ucp.empresas.vistaucpempresas', compact('id', 'arrayEmpresas'));
    }

    public function tablaProcesoUcpEmpresas($id)
    {
        $listado = ProcesosUcpEmpresa::where('id_procesoucp', $id)->get();
        foreach ($listado as $dato) {
            $infoEmpre = Empresas::where('id', $dato->id_empresa)->first();
            $dato->nombreEmpresas = $infoEmpre->nombre;
        }

        return view('backend.admin.procesos.listado.ucp.empresas.tablaucpempresas', compact('listado'));
    }


    public function nuevoProcesoUcpEmpresas(Request $request)
    {
        $regla = array(
            'id' => 'required',
            'idempresa' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        DB::beginTransaction();
        try {

            Log::info($request->all());

            $registro = new ProcesosUcpEmpresa();
            $registro->id_procesoucp = $request->id;
            $registro->id_empresa = $request->idempresa;
            $registro->save();

            DB::commit();
            return ['success' => 1];
        }catch(\Throwable $e){
            Log::info("error: " . $e);
            DB::rollback();
            return ['success' => 99];
        }
    }


    public function borrarProcesoUcpEmpresas(Request $request)
    {
        $regla = array(
            'id' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        if(ProcesosUcpEmpresa::where('id', $request->id)->first()){

            ProcesosUcpEmpresa::where('id', $request->id)->delete();

            return ['success' => 1];
        }else{
            return ['success' => 2];
        }
    }






    //************ VISTAS PARA ADMINISTRADOR  ************************

    public function indexProcesoAdministrador($id)
    {
        return view('backend.admin.procesos.listado.administrador.vistaadministrador', compact('id'));
    }


    public function tablaProcesoAdministrador($id)
    {
        $listado = ProcesosAdministrador::where('id_proceso', $id)
            ->orderBy('fecha_entrega', 'asc')
            ->get();

        foreach ($listado as $dato) {
            $dato->fechaFormat = date("d-m-Y", strtotime($dato->fecha_entrega));
        }

        return view('backend.admin.procesos.listado.administrador.tablaadministrador', compact('listado'));
    }


    public function nuevoProcesoAdministrador(Request $request)
    {
        $regla = array(
            'id' => 'required',
            'fecha' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        // SIEMPRE VENDRA DOCUMENTO
        $cadena = Str::random(15);
        $tiempo = microtime();
        $union = $cadena . $tiempo;
        $nombre = str_replace(' ', '_', $union);

        $extension = '.' . $request->documento->getClientOriginalExtension();
        $nomDocumento = $nombre . strtolower($extension);
        $avatar = $request->file('documento');
        $archivo = Storage::disk('archivos')->put($nomDocumento, \File::get($avatar));

        if($archivo){

            DB::beginTransaction();
            try {

                $registro = new ProcesosAdministrador();
                $registro->id_proceso = $request->id;
                $registro->fecha_entrega = $request->fecha;
                $registro->documento = $nomDocumento;
                $registro->nombre_documento = $request->nombreDocumento;
                $registro->folio = $request->folio;
                $registro->texto_documento = $request->textoDocumento;
                $registro->save();

                DB::commit();
                return ['success' => 1];
            }catch(\Throwable $e){
                DB::rollback();
                return ['success' => 99];
            }
        }else{
            return ['success' => 99];
        }
    }

    public function borrarProcesoAdministrador(Request $request)
    {
        $regla = array(
            'id' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        if($infoPro = ProcesosAdministrador::where('id', $request->id)->first()){

            $documentoOld = $infoPro->documento;

            // borrar documento anterior
            if (Storage::disk('archivos')->exists($documentoOld)) {
                Storage::disk('archivos')->delete($documentoOld);
            }

            ProcesosAdministrador::where('id', $request->id)->delete();

            return ['success' => 1];
        }else{
            return ['success' => 2];
        }
    }


    public function informacionProcesoAdministrador(Request $request)
    {
        $regla = array(
            'id' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        if($infoPro = ProcesosAdministrador::where('id', $request->id)->first()){

            return ['success' => 1, 'info' => $infoPro];
        }
        else{
            return ['success' => 2];
        }
    }


    public function editarProcesoAdministrador(Request $request)
    {
        $regla = array(
            'id' => 'required',
            'fecha' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        if ($request->hasFile('documento')) {

            $infoPro = ProcesosAdministrador::where('id', $request->id)->first();
            $documentoOld = $infoPro->documento;

            $cadena = Str::random(15);
            $tiempo = microtime();
            $union = $cadena . $tiempo;
            $nombre = str_replace(' ', '_', $union);

            $extension = '.' . $request->documento->getClientOriginalExtension();
            $nomDocumento = $nombre . strtolower($extension);
            $avatar = $request->file('documento');
            $archivo = Storage::disk('archivos')->put($nomDocumento, \File::get($avatar));

            if($archivo){
                ProcesosAdministrador::where('id', $request->id)->update([
                    'fecha_entrega' => $request->fecha,
                    'nombre_documento' => $request->nombreDocumento,
                    'folio' => $request->folio,
                    'texto_documento' => $request->textoDocumento,
                    'documento' => $nomDocumento,
                ]);

                if (Storage::disk('archivos')->exists($documentoOld)) {
                    Storage::disk('archivos')->delete($documentoOld);
                }

                return ['success' => 1];
            }
            else{
                return ['success' => 99];
            }
        }else{
            ProcesosAdministrador::where('id', $request->id)->update([
                'fecha_entrega' => $request->fecha,
                'nombre_documento' => $request->nombreDocumento,
                'folio' => $request->folio,
                'texto_documento' => $request->textoDocumento,
            ]);

            return ['success' => 1];
        }
    }


    public function visualizarDocAdministrador($id)
    {
        $infoPro = ProcesosAdministrador::where('id', $id)->first();
        $pathToFile = storage_path("app/public/archivos/" . $infoPro->documento);

        if (!file_exists($pathToFile)) {
            abort(404, "El archivo no existe.");
        }

        return response()->file($pathToFile, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="Documento.pdf"' // Aquí forzamos el nombre
        ]);
    }


    public function descargarDocAdministrador($id)
    {
        $infoPro = ProcesosAdministrador::where('id', $id)->first();
        $pathToFile = "storage/archivos/" . $infoPro->documento;
        $extension = pathinfo(($pathToFile), PATHINFO_EXTENSION);

        $nombre = "Documento";
        if($infoPro->nombre_documento != null){
            $nombre = $infoPro->nombre_documento;
        }

        $nombreFinal = $nombre . "." . $extension;
        return response()->download($pathToFile, $nombreFinal);
    }




    //******************** BUSCADOR ********************************


    public function indexBuscador()
    {
        return view('backend.admin.buscador.vistabuscador');
    }


    public function buscadorArchivos(Request $request)
    {
        if ($request->get('query')) {
            $query = $request->get('query');


            $data = Procesos::where('numero_proceso', 'LIKE', "%{$query}%")
                ->orWhere('nombre_proyecto', 'LIKE', "%{$query}%")
                ->orWhere('codigo_proyecto', 'LIKE', "%{$query}%")
                ->orWhere('numero_expediente', 'LIKE', "%{$query}%")
                ->orWhere('nombre_proceso', 'LIKE', "%{$query}%")
                ->limit(40)
                ->get();

            $output = '<ul class="dropdown-menu" style="display:block; position:relative; overflow: auto; max-height: 300px; width: 600px">';
            $tiene = true;
            foreach ($data as $row) {

                $infoFuente = Fuentes::where('id', $row->id_fuente)->first();
                $infoAnio = Anios::where('id', $infoFuente->id_anio)->first();

                $nombreCompleto = "(" . $infoAnio->nombre . " | " . $infoFuente->nombre . ") " . $row->nombre_proyecto;

                // si solo hay 1 fila, No mostrara el hr, salto de linea
                if (count($data) == 1) {
                    if (!empty($row)) {
                        $tiene = false;
                        $output .= '
                 <li class="cursor-pointer" onclick="modificarValor(this)" id="' . $row->id . '">' . $nombreCompleto . '</li>
                ';
                    }
                } else {
                    if (!empty($row)) {
                        $tiene = false;
                        $output .= '
                 <li class="cursor-pointer" onclick="modificarValor(this)" id="' . $row->id . '">' . $nombreCompleto . '</li>
                   <hr>
                ';
                    }
                }
            }
            $output .= '</ul>';
            if ($tiene) {
                $output = '';
            }
            echo $output;
        }
    }


    public function indexBuscadorItemEncontrado($id)
    {
        return view('backend.admin.buscador.lista.vistalistabuscador', compact('id'));
    }


    public function tablaBuscadorItemEncontrado($id)
    {
        if(Procesos::where('id', $id)->first()){

            $listado = Procesos::where('id', $id)->get();
            return view('backend.admin.buscador.lista.tablalistabuscador', compact('listado'));
        }else{
            return "No encontrado";
        }
    }

}
