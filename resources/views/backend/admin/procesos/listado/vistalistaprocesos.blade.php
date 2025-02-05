@extends('backend.menus.superior')

@section('content-admin-css')
    <link href="{{ asset('css/adminlte.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/dataTables.bootstrap4.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/toastr.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/estiloToggle.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/select2.min.css') }}" type="text/css" rel="stylesheet">
    <link href="{{ asset('css/select2-bootstrap-5-theme.min.css') }}" type="text/css" rel="stylesheet">
@stop

<style>
    table{
        /*Ajustar tablas*/
        table-layout:fixed;
    }
</style>


<div id="divcontenedor" style="display: none">

    <section class="content" style="margin-top: 10px">
        <div class="container-fluid">
            <div class="card card-gray-dark">
                <div class="card-header">
                    <h3 class="card-title">Listado</h3>
                </div>
                <div class="card-body">

                    <div class="row d-flex align-items-center">
                        <div class="form-group col-md-3">
                            <label style="color: #686868">Fuente</label>
                            <div>
                                <select id="select-fuente" class="form-control">
                                    @foreach($arrayFuente as $item)
                                        <option value="{{$item->id}}">{{$item->nombreCompleto}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-auto">
                            <button type="button" onclick="buscarListado()" class="btn btn-success btn-sm">
                                <i class="fas fa-search"></i>
                                Buscar
                            </button>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-12">
                            <div id="tablaDatatable">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- modal editar -->
    <div class="modal fade" id="modalEditar">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Editar</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formulario-editar">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">

                                    <div class="form-group">
                                        <input type="hidden" id="id-editar"/>
                                    </div>

                                    <div class="container-fluid" style="border: 1px solid #ccc; padding: 15px; border-radius: 5px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                                        <div class="row">
                                            <div class="form-group col-md-6" style="margin-top: 5px">
                                                <label style="color: #686868">Fuente</label>
                                                <div>
                                                    <select id="select-fuente-editar" class="form-control">
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
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary btn-sm" onclick="editarRegistro()">Actualizar</button>
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade" id="modalInfoDocumentos">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Información</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formulario-infodocumento">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="hidden" id="id-filadocumentos"/>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3 col-6">
                                            <!-- small box -->
                                            <div class="small-box bg-info">
                                                <div class="inner">
                                                    <h3 id="totalSolicitud"></h3>

                                                    <p>SOLICITANTE</p>
                                                </div>
                                                <div class="icon">
                                                    <i class="ion ion-bag"></i>
                                                </div>
                                                <a href="#" class="small-box-footer" onclick="vistaSolicitante()">Ver Información <i class="fas fa-arrow-circle-right"></i></a>
                                            </div>
                                        </div>
                                        <!-- ./col -->
                                        <div class="col-lg-3 col-6">
                                            <!-- small box -->
                                            <div class="small-box bg-success">
                                                <div class="inner">
                                                    <h3 id="totalUCP"></h3>

                                                    <p>UCP</p>
                                                </div>
                                                <div class="icon">
                                                    <i class="ion ion-stats-bars"></i>
                                                </div>
                                                <a href="#" class="small-box-footer" onclick="vistaUcp()">Ver Información <i class="fas fa-arrow-circle-right"></i></a>
                                            </div>
                                        </div>
                                        <!-- ./col -->
                                        <div class="col-lg-3 col-6">
                                            <!-- small box -->
                                            <div class="small-box bg-warning">
                                                <div class="inner">
                                                    <h3 id="totalAdministrador"></h3>

                                                    <p>ADMINISTRADOR</p>
                                                </div>
                                                <div class="icon">
                                                    <i class="ion ion-person-add"></i>
                                                </div>
                                                <a href="#" class="small-box-footer" onclick="vistaAdministrador()">Ver Información <i class="fas fa-arrow-circle-right"></i></a>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>




</div>


@extends('backend.menus.footerjs')
@section('archivos-js')

    <script src="{{ asset('js/jquery.dataTables.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/dataTables.bootstrap4.js') }}" type="text/javascript"></script>

    <script src="{{ asset('js/toastr.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/axios.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('js/alertaPersonalizada.js') }}"></script>
    <script src="{{ asset('js/select2.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function(){

            document.getElementById("divcontenedor").style.display = "block";

            $('#select-fuente-editar').select2({
                theme: "bootstrap-5",
                "language": {
                    "noResults": function(){
                        return "Búsqueda no encontrada";
                    }
                },
            });
            var id = {{ $primerId }};

            if (id === null) {
                console.log("El valor es null");
            } else {
                openLoading()

                var ruta = "{{ URL::to('/admin/procesos/tabla') }}/" + id;
                $('#tablaDatatable').load(ruta);
            }

        });
    </script>

    <script>

        function buscarListado(){
            var idFuente = document.getElementById('select-fuente').value;

            if(idFuente === ''){
                toastr.error('Fuente es requerida');
                return;
            }

            openLoading()

            var ruta = "{{ URL::to('/admin/procesos/tabla') }}/" + idFuente;
            $('#tablaDatatable').load(ruta);
        }

        function recargar(){
            var idFuente = document.getElementById('select-fuente').value;

            if(idFuente === ''){
                toastr.error('Fuente es requerida');
                return;
            }

            openLoading()

            var ruta = "{{ URL::to('/admin/procesos/tabla') }}/" + idFuente;
            $('#tablaDatatable').load(ruta);
        }

        function modalAgregar(){
            document.getElementById("formulario-nuevo").reset();
            $('#modalAgregar').modal('show');
        }

        function informacion(id){
            openLoading();
            document.getElementById("formulario-editar").reset();

            axios.post(url+'/procesos/informacion',{
                'id': id
            })
                .then((response) => {
                    closeLoading();
                    if(response.data.success === 1){
                        $('#modalEditar').modal('show');
                        $('#id-editar').val(id);

                        $('#numero-proceso').val(response.data.info.numero_proceso);
                        $('#nombre-proyecto').val(response.data.info.nombre_proyecto);
                        $('#codigo-proyecto').val(response.data.info.codigo_proyecto);
                        $('#numero-expediente').val(response.data.info.numero_expediente);
                        $('#ampo').val(response.data.info.ampo);
                        $('#nombre-proceso').val(response.data.info.nombre_proceso);

                        document.getElementById("select-fuente-editar").options.length = 0;

                        $.each(response.data.arrayanios, function( key, val ){
                            if(response.data.info.id_fuente == val.id){
                                $('#select-fuente-editar').append('<option value="' +val.id +'" selected="selected">'+ val.nombre +'</option>');
                            }else{
                                $('#select-fuente-editar').append('<option value="' +val.id +'">'+ val.nombre +'</option>');
                            }
                        });

                    }else{
                        toastr.error('Información no encontrada');
                    }

                })
                .catch((error) => {
                    closeLoading();
                    toastr.error('Información no encontrada');
                });
        }


        function editarRegistro(){
            var id = document.getElementById('id-editar').value;

            var idfuente = document.getElementById('select-fuente-editar').value;
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
            formData.append('id', id);
            formData.append('idfuente', idfuente);
            formData.append('numeroProceso', numeroProceso);
            formData.append('nombreProyecto', nombreProyecto);
            formData.append('codigoProyecto', codigoProyecto);
            formData.append('numeroExpediente', numeroExpediente);
            formData.append('ampo', ampo);
            formData.append('nombreProceso', nombreProceso);

            axios.post(url+'/procesos/editar', formData, {
            })
                .then((response) => {
                    closeLoading();

                    if(response.data.success === 1){
                        toastr.success('Actualizado');
                        $('#modalEditar').modal('hide');
                        recargar();
                    }
                    else {
                        toastr.error('Error al actualizar');
                    }
                })
                .catch((error) => {
                    toastr.error('Error al actualizar');
                    closeLoading();
                });
        }


        // MOSTRAR REGISTROS DE CADA UNO
        function infoDocumentos(id){

            openLoading();
            var formData = new FormData();
            formData.append('id', id);

            axios.post(url+'/procesos/informacion/documentos', formData, {
            })
                .then((response) => {
                    closeLoading();

                    if(response.data.success === 1){

                        $('#id-filadocumentos').val(id);

                        document.getElementById("totalSolicitud").innerText = response.data.totalSolicitante;
                        document.getElementById("totalUCP").innerText = response.data.totalUCP;
                        document.getElementById("totalAdministrador").innerText = response.data.totalAdministrador;

                        $('#modalInfoDocumentos').modal('show');
                    }
                    else {
                        toastr.error('Error al actualizar');
                    }
                })
                .catch((error) => {
                    toastr.error('Error al actualizar');
                    closeLoading();
                });
        }

        function vistaSolicitante(){
            var id = document.getElementById('id-filadocumentos').value;
            window.location.href="{{ url('/admin/prosolicitante/index') }}/" + id;
        }

        function vistaUcp(){
            var id = document.getElementById('id-filadocumentos').value;
            window.location.href="{{ url('/admin/proucp/index') }}/" + id;
        }

        function vistaAdministrador(){
            var id = document.getElementById('id-filadocumentos').value;
            window.location.href="{{ url('/admin/proadministrador/index') }}/" + id;
        }

        function infoBorrar(id){
            Swal.fire({
                title: 'Borrar Proceso',
                text: "",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Si'
            }).then((result) => {
                if (result.isConfirmed) {
                    borrarSolicitud(id)
                }
            })
        }

        function borrarSolicitud(id){
            openLoading();
            var formData = new FormData();
            formData.append('id', id);

            axios.post(url+'/procesos/borrar', formData, {
            })
                .then((response) => {
                    closeLoading();
                    if(response.data.success === 1){
                        toastr.success('Borrado');
                        recargar();
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


    </script>


@endsection
