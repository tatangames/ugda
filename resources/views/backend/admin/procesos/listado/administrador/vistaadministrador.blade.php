@extends('backend.menus.superior')

@section('content-admin-css')
    <link href="{{ asset('css/adminlte.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/dataTables.bootstrap4.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/toastr.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/buttons_estilo.css') }}" rel="stylesheet">
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
                    <li class="breadcrumb-item">Procesos</li>
                    <li class="breadcrumb-item active">Administrador</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-gray-dark">
                <div class="card-header">
                    <h3 class="card-title">Listado de Administrador</h3>
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

                                    <div class="form-group col-md-3">
                                        <label>Fecha Entrega *</label>
                                        <input type="date" id="fecha-entrega" class="form-control"/>
                                    </div>

                                    <div class="form-group">
                                        <label>Nombre Documento</label>
                                        <input type="text" id="nombre-documento" autocomplete="off" class="form-control" maxlength="300" />
                                    </div>

                                    <div class="form-group">
                                        <label>Folio</label>
                                        <input type="text" id="folio" autocomplete="off" class="form-control" maxlength="50" />
                                    </div>

                                    <div class="form-group">
                                        <label>Texto Documento (Intermedio)</label>
                                        <input type="text" id="texto-documento" autocomplete="off" class="form-control" maxlength="100" />
                                    </div>

                                    <div class="form-group">
                                        <label>Documento PDF *</label>
                                        <input type="file" id="documento-pdf" class="form-control" accept="application/pdf"/>
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

                                    <div class="form-group col-md-3">
                                        <label>Fecha Entrega *</label>
                                        <input type="date" id="fecha-entrega-editar" class="form-control"/>
                                    </div>

                                    <div class="form-group">
                                        <label>Nombre Documento</label>
                                        <input type="text" id="nombre-documento-editar" autocomplete="off" class="form-control" maxlength="300" />
                                    </div>

                                    <div class="form-group">
                                        <label>Folio</label>
                                        <input type="text" id="folio-editar" autocomplete="off" class="form-control" maxlength="50" />
                                    </div>

                                    <div class="form-group">
                                        <label>Texto Documento (Intermedio)</label>
                                        <input type="text" id="texto-documento-editar" autocomplete="off" class="form-control" maxlength="100" />
                                    </div>

                                    <div class="form-group">
                                        <label>Documento PDF *</label>
                                        <input type="file" id="documento-pdf-editar" class="form-control" accept="application/pdf"/>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary btn-sm" onclick="actualizarDatos()">Guardar</button>
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

    <script type="text/javascript">
        $(document).ready(function(){

            let id = {{ $id }};
            var ruta = "{{ URL::to('/admin/proadministrador/tabla') }}/" + id;
            $('#tablaDatatable').load(ruta);

            document.getElementById("divcontenedor").style.display = "block";
        });
    </script>

    <script>

        function recargar(){
            let id = {{ $id }};
            var ruta = "{{ URL::to('/admin/proadministrador/tabla') }}/" + id;
            $('#tablaDatatable').load(ruta);
        }

        function modalAgregar(){
            document.getElementById("formulario-nuevo").reset();
            $('#modalAgregar').modal('show');
        }


        function nuevoRegistro(){

            var fecha = document.getElementById('fecha-entrega').value;
            var nombreDocumento = document.getElementById('nombre-documento').value;
            var folio = document.getElementById('folio').value;
            var textoDocumento = document.getElementById('texto-documento').value;
            var documento = document.getElementById('documento-pdf');

            if(fecha === ''){
                toastr.error('Fecha Entrega es requerido');
                return;
            }

            if(documento.files && documento.files[0]){ // si trae doc
                if (!documento.files[0].type.match('application/pdf')){
                    toastr.error('formato para Documento permitido: .pdf');
                    return;
                }
            }else{
                toastr.error('Documento es requerido');
                return;
            }

            let id = {{ $id }};

            openLoading();
            var formData = new FormData();
            formData.append('id', id);
            formData.append('fecha', fecha);
            formData.append('nombreDocumento', nombreDocumento);
            formData.append('folio', folio);
            formData.append('textoDocumento', textoDocumento);
            formData.append('documento', documento.files[0]);

            axios.post(url+'/proadministrador/nuevo', formData, {
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

            axios.post(url+'/proadministrador/borrar', formData, {
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

            axios.post(url+'/proadministrador/informacion', formData, {
            })
                .then((response) => {
                    closeLoading();
                    if(response.data.success === 1){

                        $('#id-editar').val(id);
                        $('#fecha-entrega-editar').val(response.data.info.fecha_entrega);
                        $('#nombre-documento-editar').val(response.data.info.nombre_documento);
                        $('#folio-editar').val(response.data.info.folio);
                        $('#texto-documento-editar').val(response.data.info.texto_documento);

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

            axios.post(url+'/proadministrador/editar', formData, {
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



    </script>


@endsection
