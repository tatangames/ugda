@extends('backend.menus.superior')

@section('content-admin-css')
    <link href="{{ asset('css/adminlte.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/dataTables.bootstrap4.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/toastr.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/buttons_estilo.css') }}" rel="stylesheet">
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

    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <button type="button" onclick="modalAgregar()" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus-square"></i>
                    Nuevo registro
                </button>
            </div>

            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">Empresas</li>
                    <li class="breadcrumb-item active">UCP</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-gray-dark">
                <div class="card-header">
                    <h3 class="card-title">Listado de Procesos UCP - Empresas</h3>
                </div>
                <div class="card-body">
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


    <div class="modal fade" id="modalAgregar">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Registro</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formulario-nuevo">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">

                                    <div class="form-group">
                                        <label>Empresa</label>
                                        <select id="select-empresas" class="form-control">
                                            @foreach($arrayEmpresas as $item)
                                                <option value="{{$item->id}}">{{$item->nombre}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary btn-sm" onclick="nuevoRegistro()">Guardar</button>
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

            let id = {{ $id }};
            var ruta = "{{ URL::to('/admin/proucp/empresas/tabla') }}/" + id;
            $('#tablaDatatable').load(ruta);


            $('#select-empresas').select2({
                theme: "bootstrap-5",
                "language": {
                    "noResults": function(){
                        return "Búsqueda no encontrada";
                    }
                },
            });


            document.getElementById("divcontenedor").style.display = "block";
        });
    </script>

    <script>

        function recargar(){
            let id = {{ $id }};
            var ruta = "{{ URL::to('/admin/proucp/empresas/tabla') }}/" + id;
            $('#tablaDatatable').load(ruta);
        }

        function modalAgregar(){
            document.getElementById("formulario-nuevo").reset();
            $('#modalAgregar').modal('show');
        }


        function nuevoRegistro(){

            var idempresa = document.getElementById('select-empresas').value;

            if(idempresa === ''){
                toastr.success('Empresa es requerida');
                return
            }

            let id = {{ $id }};

            openLoading();
            var formData = new FormData();
            formData.append('id', id);
            formData.append('idempresa', idempresa);

            axios.post(url+'/proucp/empresas/nuevo', formData, {
            })
                .then((response) => {

                    closeLoading();
                    if(response.data.success === 1){
                        toastr.success('Registrado');
                        $('#modalAgregar').modal('hide');
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


        function informacionBorrar(id){
            Swal.fire({
                title: 'Borrar Registro',
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

            axios.post(url+'/proucp/empresas/borrar', formData, {
            })
                .then((response) => {
                    closeLoading();
                    if(response.data.success === 1){
                        toastr.success('Registrado');
                        $('#modalAgregar').modal('hide');
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


        function infoEditar(id){

            document.getElementById("formulario-editar").reset();

            openLoading();
            var formData = new FormData();
            formData.append('id', id);

            axios.post(url+'/proucp/informacion', formData, {
            })
                .then((response) => {
                    closeLoading();
                    if(response.data.success === 1){

                        $('#id-editar').val(id);
                        $('#fecha-entrega-editar').val(response.data.info.fecha_entrega);
                        $('#nombre-documento-editar').val(response.data.info.nombre_documento);
                        $('#folio-editar').val(response.data.info.folio);
                        $('#texto-documento-editar').val(response.data.info.texto_documento);

                        if(response.data.info.estado === 0){
                            $('#select-estado-editar').prop('selectedIndex', 0).change();
                        }else{
                            $('#select-estado-editar').prop('selectedIndex', 1).change();
                        }

                        $('#modalEditar').modal('show');
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

        function actualizarDatos(){
            var id = document.getElementById('id-editar').value;
            var fecha = document.getElementById('fecha-entrega-editar').value;
            var nombreDocumento = document.getElementById('nombre-documento-editar').value;
            var folio = document.getElementById('folio-editar').value;
            var textoDocumento = document.getElementById('texto-documento-editar').value;
            var documento = document.getElementById('documento-pdf-editar');
            var estado = document.getElementById('select-estado-editar').value;

            if(fecha === ''){
                toastr.error('Fecha Entrega es requerido');
                return;
            }

            if(documento.files && documento.files[0]){ // si trae doc
                if (!documento.files[0].type.match('application/pdf')){
                    toastr.error('formato para Documento permitido: .pdf');
                    return;
                }
            }

            openLoading();
            var formData = new FormData();
            formData.append('id', id);
            formData.append('fecha', fecha);
            formData.append('nombreDocumento', nombreDocumento);
            formData.append('folio', folio);
            formData.append('textoDocumento', textoDocumento);
            formData.append('documento', documento.files[0]);
            formData.append('estado', estado);

            axios.post(url+'/proucp/editar', formData, {
            })
                .then((response) => {
                    closeLoading();
                    if(response.data.success === 1){
                        toastr.success('Actualizado');
                        $('#modalEditar').modal('hide');
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

        function infoEmpresas(id){
            window.location.href="{{ url('/admin/proucp/empresas/index') }}/" + id;
        }




    </script>


@endsection
