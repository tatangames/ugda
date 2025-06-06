<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <table id="tabla" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th style="width: 8%">Fecha Entrega</th>
                                <th style="width: 8%">Folio</th>
                                <th style="width: 8%">Nombre Documento</th>
                                <th style="width: 8%">Texto</th>
                                <th style="width: 8%">Estado</th>
                                <th style="width: 8%">Empresa</th>
                                <th style="width: 8%">Opciones</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($listado as $dato)
                                <tr>
                                    <td>{{ $dato->fechaFormat }}</td>
                                    <td>{{ $dato->folio }}</td>
                                    <td>{{ $dato->nombre_documento }}</td>
                                    <td>{{ $dato->texto_documento }}</td>
                                    <td>{{ $dato->nombreEstado }}</td>
                                    <td><pre>{{ $dato->nombreEmpresas }}</pre></td>
                                    <td>
                                        <a href="{{ url('/admin/proucp/visualizar/documento/'.$dato->id) }}" target="_blank" class="btn btn-info btn-xs">
                                            <i class="fa fa-file-pdf"></i> Ver PDF
                                        </a>

                                        <a href="{{ url('/admin/proucp/descargar/documento/'.$dato->id) }}" class="btn btn-info btn-xs">
                                            <i class="fa fa-file-pdf"></i> Descargar PDF
                                        </a>

                                        <button type="button" style="margin: 5px" class="btn btn-warning btn-xs" onclick="infoEditar({{ $dato->id }})">
                                            <i class="fas fa-edit" title="Editar"></i>&nbsp; Editar
                                        </button>

                                        <button type="button" style="margin: 5px" class="btn btn-dark btn-xs" onclick="infoEmpresas({{ $dato->id }})">
                                            <i class="fas fa-edit" title="Empresas"></i>&nbsp; Empresas
                                        </button>

                                        <button type="button" style="margin: 5px" class="btn btn-danger btn-xs" onclick="informacionBorrar({{ $dato->id }})">
                                            <i class="fas fa-trash" title="Borrar"></i>&nbsp; Borrar
                                        </button>
                                    </td>
                                </tr>
                            @endforeach

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
            "order": [[0, 'desc']],
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
