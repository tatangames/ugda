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
                                <th style="width: 6%">Falta</th>
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

                                        @if($dato->docSolitante)
                                            <span class="badge bg-success">Solicitante: SI</span><br><br>
                                        @else
                                            <span class="badge bg-danger">Solicitante: NO</span><br><br>
                                        @endif

                                        @if($dato->docUcp)
                                            <span class="badge bg-success">UCP: SI</span><br><br>
                                        @else
                                            <span class="badge bg-danger">UCP: NO</span><br><br>
                                        @endif

                                        @if($dato->docAdministrador)
                                            <span class="badge bg-success">Administrador: SI</span>
                                        @else
                                            <span class="badge bg-danger">Administrador: NO</span>
                                        @endif

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
