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
                    <h3 class="card-title">Filtro</h3>
                </div>
                <div class="card-body">

                    <label>Procesos Listos para Consolidar</label>

                    <div class="form-group col-md-3">
                        <label>Año</label>
                        <select id="select-anio" class="form-control">
                            @foreach($arrayAnio as $item)
                                <option value="{{$item->id}}">{{$item->nombre}}</option>
                            @endforeach
                        </select>
                    </div>


                    <div class="col-auto">
                        <button type="button" onclick="buscarFiltro1()" class="btn btn-success btn-sm">
                            <i class="fas fa-search"></i>
                            Buscar
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </section>


    <section class="content" style="margin-top: 20px">
        <div class="container-fluid">
            <div class="card card-gray-dark">
                <div class="card-header">
                    <h3 class="card-title">Expedientes Falta Expedientes</h3>
                </div>
                <div class="card-body">

                    <div class="col-auto">
                        <button type="button" onclick="buscarFiltro2()" class="btn btn-success btn-sm">
                            <i class="fas fa-search"></i>
                            Buscar
                        </button>
                    </div>


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


        function buscarFiltro1(){
            var idanio = document.getElementById('select-anio').value;
            window.location.href="{{ url('/admin/filtro/busqueda/noconsolidado') }}/" + idanio;
        }

        function buscarFiltro2(){

        }

    </script>

@endsection






