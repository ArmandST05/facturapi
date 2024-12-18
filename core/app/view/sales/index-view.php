<h1>Lista de Ventas</h1>
<div class="clearfix"></div>
<hr>
<table id="lookup1" class="table table-bordered table-hover">
    <thead align="center">
        <th></th>
        <th></th>
        <th></th>
        <th>Folio</th>
        <th>Día</th>
        <th>Fecha</th>
        <th>Nombre del paciente</th>
        <th>Total</th>
        <th>Comentarios</th>
        <th>Pagado</th>
        <th>Facturado</th>
        <th>No de Factura</th>
        <th>Banco</th>
        <th>Estatus</th>
    </thead>
    <tbody>
    </tbody>
</table>

<script>
    $(document).ready(function() {
        var dataTable = $('#lookup1').DataTable({
            "language": {
                "sProcessing": "Procesando...",
                "sLengthMenu": "Mostrar _MENU_ registros",
                "sZeroRecords": "No se encontraron resultados",
                "sEmptyTable": "Ningún dato disponible en esta tabla",
                "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sSearch": "Buscar:",
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
            "ordering": false,
            "processing": true,
            "serverSide": true,
            "ajax": {
                url: "./?action=sales/get-all",
                type: "POST",
                error: function(xhr, error, code) {
                    console.log(xhr.responseText);
                    $(".lookup1-error").html("");
                    $("#lookup_processing").css("display", "none");
                }
            }
        });
    });

    function confirmDelete() {
        return confirm("¿Seguro que deseas eliminar la venta?");
    }

   
    function obtenerDatosFactura(id) {
    // Construye la URL amigable con el ID
    const url = `index.php?view=sales/factura&id=${id}`;
    
    // Redirige a la nueva URL
    window.location.href = url;
}

</script>


