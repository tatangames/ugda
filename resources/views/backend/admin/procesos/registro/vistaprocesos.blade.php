@extends('backend.menus.superior')

@section('content-admin-css')
    <link href="{{ asset('css/adminlte.min.css') }}" type="text/css" rel="stylesheet"/>
    <link href="{{ asset('css/dataTables.bootstrap4.css') }}" type="text/css" rel="stylesheet"/>
    <link href="{{ asset('css/toastr.min.css') }}" type="text/css" rel="stylesheet"/>
    <link href="{{ asset('css/buttons_estilo.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.min.css') }}" type="text/css" rel="stylesheet">
    <link href="{{ asset('css/select2-bootstrap-5-theme.min.css') }}" type="text/css" rel="stylesheet">
@stop

<style>
    table {
        /*Ajustar tablas*/
        table-layout: fixed;
    }

    .cursor-pointer:hover {
        cursor: pointer;
        color: #401fd2;
        font-weight: bold;
    }

    *:focus {
        outline: none;
    }
</style>

<div id="divcontenedor" style="display: none">

    <section class="content" style="margin-top: 20px">
        <div class="container-fluid">
            <div class="card card-gray-dark">
                <div class="card-header">
                    <h3 class="card-title">REGISTRO DE NUEVO PROCESO</h3>
                </div>
                <div class="card-body">

                    <section class="content">
                        <div class="container-fluid" style="border: 1px solid #ccc; padding: 15px; border-radius: 5px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                            <div class="row">
                                <div class="form-group col-md-5" style="margin-top: 5px">
                                    <label style="color: #686868">Fuente</label>
                                    <div>
                                        <select id="select-fuente" class="form-control">
                                            @foreach($arrayFuente as $item)
                                                <option value="{{$item->id}}">{{$item->nombreCompleto}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label class="control-label" style="color: #686868">Número de Proceso *</label>
                                    <input type="text" id="numero-proceso" maxlength="100" autocomplete="off" class="form-control">
                                </div>

                                <div class="form-group col-md-6" style="margin-left: 15px">
                                    <label class="control-label" style="color: #686868">Nombre de Proyecto *</label>
                                    <input type="text" id="nombre-proyecto" maxlength="300" autocomplete="off" class="form-control">
                                </div>

                            </div>

                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label class="control-label" style="color: #686868">Código de Proyecto</label>
                                    <input type="text" id="codigo-proyecto" maxlength="100" autocomplete="off" class="form-control">
                                </div>

                                <div class="form-group col-md-3">
                                    <label class="control-label" style="color: #686868">Número de Expediente</label>
                                    <input type="text" id="numero-expediente" maxlength="50" autocomplete="off" class="form-control">
                                </div>

                                <div class="form-group col-md-3">
                                    <label class="control-label" style="color: #686868">AMPO</label>
                                    <input type="text" id="ampo" maxlength="50" autocomplete="off" class="form-control">
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-5">
                                    <label class="control-label" style="color: #686868">Nombre del Proceso</label>
                                    <input type="text" id="nombre-proceso" maxlength="300" autocomplete="off" class="form-control">
                                </div>
                            </div>

                        </div>
                    </section>

                    <hr>

                    <section class="content">
                        <div class="container-fluid">

                            <div style="margin-right: 30px">
                                <button type="button" style="float: right" class="btn btn-success" onclick="guardarRegistro();">Registrar Prceso</button>

                            </div>

                        </div>
                    </section>
                </div>
            </div>
        </div>
    </section>


</div>


@extends('backend.menus.footerjs')
@section('archivos-js')

    <script src="{{ asset('js/toastr.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/axios.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('js/alertaPersonalizada.js') }}"></script>
    <script src="{{ asset('js/select2.min.js') }}" type="text/javascript"></script>

    <script type="text/javascript">
        $(document).ready(function () {

            document.getElementById("divcontenedor").style.display = "block";
        });
    </script>

    <script>

        function guardarRegistro(){

            var idfuente = document.getElementById('select-fuente').value;
            var numeroProceso = document.getElementById('numero-proceso').value;
            var nombreProyecto = document.getElementById('nombre-proyecto').value;
            var codigoProyecto = document.getElementById('codigo-proyecto').value;
            var numeroExpediente = document.getElementById('numero-expediente').value;
            var ampo = document.getElementById('ampo').value;
            var nombreProceso = document.getElementById('nombre-proceso').value;

            if(numeroProceso === ''){
                toastr.error('Número Proceso es requerido');
                return;
            }

            if(nombreProyecto === ''){
                toastr.error('Nombre Proyecto es requerido');
                return;
            }

            openLoading();
            var formData = new FormData();
            formData.append('idfuente', idfuente);
            formData.append('numeroProceso', numeroProceso);
            formData.append('nombreProyecto', nombreProyecto);
            formData.append('codigoProyecto', codigoProyecto);
            formData.append('numeroExpediente', numeroExpediente);
            formData.append('ampo', ampo);
            formData.append('nombreProceso', nombreProceso);

            axios.post(url+'/procesosnuevo/nuevo', formData, {
            })
                .then((response) => {
                    closeLoading();
                    if(response.data.success === 1){
                        toastr.success('Registrado');
                       limpiar()
                    }
                    else {
                        toastr.error('Error al registrar');
                    }
                })
                .catch((error) => {
                    toastr.error('Error al registrar');
                    closeLoading();
                });
        }

        function limpiar(){
            document.getElementById('numero-proceso').value = "";
            document.getElementById('nombre-proyecto').value = "";
            document.getElementById('codigo-proyecto').value = "";
            document.getElementById('numero-expediente').value = "";
            document.getElementById('ampo').value = "";
            document.getElementById('nombre-proceso').value = "";
        }



    </script>

@endsection






