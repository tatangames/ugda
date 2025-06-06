<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <table id="tabla" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th style="width: 6%"># Proceso</th>
                                <th style="width: 6%">Nom. Proyecto</th>
                                <th style="width: 6%">Cod. Proyecto</th>
                                <th style="width: 6%"># Expe.</th>
                                <th style="width: 6%">Ampo</th>
                                <th style="width: 6%">Nom. Proceso</th>
                                <th style="width: 6%">Consolidado</th>
                                <th style="width: 6%">Opciones</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($listado as $dato)
                                <tr>

                                    <td>{{ $dato->numero_proceso }}</td>
                                    <td>{{ $dato->nombre_proyecto }}</td>
                                    <td>{{ $dato->codigo_proyecto }}</td>
                                    <td>{{ $dato->numero_expediente }}</td>
                                    <td>{{ $dato->ampo }}</td>
                                    <td>{{ $dato->nombre_proceso }}</td>
                                    <td>
                                        @if($dato->consolidado == 1)
                                            <span class="badge bg-success">SI</span>
                                        @else
                                            <span class="badge bg-danger">NO</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-success btn-xs" onclick="informacion({{ $dato->id }})">
                                            <i class="fas fa-eye" title="Editar"></i>&nbsp; Editar
                                        </button>

                                        <button type="button" style="margin: 4px" class="btn btn-info btn-xs" onclick="infoDocumentos({{ $dato->id }})">
                                            <i class="fas fa-search" title="Registros"></i>&nbsp; Registros
                                        </button>


                                        <a href="{{ url('/admin/procesos/docpdf/consolidado/'.$dato->id) }}" target="_blank" class="btn btn-dark btn-xs">
                                            <i class="fa fa-file-pdf"></i> PDF Consolidar
                                        </a>


                                        <button type="button" style="margin: 4px" class="btn btn-danger btn-xs" onclick="infoBorrar({{ $dato->id }})">
                                            <i class="fas fa-trash" title="Borrar"></i>&nbsp; Borrar
                                        </button>

                                    </td>

                                </tr>
                            @endforeach

                            <script>
                                setTimeout(function () {
                                    closeLoading();
                                }, 500);
                            </script>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<script>
    $(function () {
        $("#tabla").DataTable({
            "paging": true,
            "lengthChange": true,
            "order": [[0, 'asc']],
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "pagingType": "full_numbers",
            "lengthMenu": [[500, -1], [500, "Todo"]],
            "language": {

                "sProcessing": "Procesando...",
                "sLengthMenu": "Mostrar _MENU_ registros",
                "sZeroRecords": "No se encontraron resultados",
                "sEmptyTable": "Ningún dato disponible en esta tabla",
                "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix": "",
                "sSearch": "Buscar:",
                "sUrl": "",
                "sInfoThousands": ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst": "Primero",
                    "sLast": "Último",
                    "sNext": "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }

            },
            "responsive": true, "lengthChange": true, "autoWidth": false,
        });
    });


</script>
