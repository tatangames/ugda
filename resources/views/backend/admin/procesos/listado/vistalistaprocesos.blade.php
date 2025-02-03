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
                    </form>
                </div>

                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary btn-sm" onclick="editarRegistro()">Actualizar</button>
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
            var nombre = document.getElementById('nombre-editar').value;

            if(nombre === ''){
                toastr.error('Nombre equipo es requerido');
                return;
            }

            openLoading();
            var formData = new FormData();
            formData.append('id', id);
            formData.append('nombre', nombre);

            axios.post(url+'/empresa/editar', formData, {
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



    </script>


@endsection
