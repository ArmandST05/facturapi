<?php
include './core/app/view/reservations/new-patient-quick.php';
$absenceType = (isset($_GET["absenceType"])) ? $_GET["absenceType"] : "all";
?>
<div class="row">
	<div class="col-md-12">
		<div class="btn-group  pull-right">
			<a href="index.php?view=patients/new" class="btn btn-default"><i class="fas fa-user"></i><i class="fas fa-plus"></i> Agregar Paciente</a>
		</div>
		<h1>Lista de Pacientes</h1>
		<form method="GET" action="index.php">
			<input type="hidden" name="view" value="patients/index">
			<div class="row">
				<div class="col-md-3">
					<label class="control-label">Tipo asistencia:</label>
					<div class="form-group">
						<select name="absenceType" class="form-control" required>
							<option value="all" <?php echo ($absenceType == "all") ? "selected" : "" ?>>-- TODOS --</option>
							<option value="absences" <?php echo ($absenceType == "absences") ? "selected" : "" ?>>INASTENCIAS</option>
						</select>
					</div>
				</div>
				<div class="col-md-2">
					<br>
					<input type="submit" class="btn btn-sm btn-primary btn-block" value="Buscar">
				</div>
			</div>
		</form>
		<hr>
		<?php if ($absenceType == "absences") : ?>
			<table class="table table-bordered" style="width:20%">
				<tr>
					<th>1 inasistencia</th>
					<td style="background:yellow"></td>
				</tr>
				<tr>
					<th>Más de 2 inasistencias</th>
					<td style="background:red"></td>
				</tr>
			</table>
		<?php endif; ?>
		<hr>
		<table id="lookup" class="table table-bordered table-hover" class="display" style="width:100%">
			<thead bgcolor="#eeeeee" align="center">
				<tr>
					<th>Clave</th>
					<th>Nombre completo</th>
					<th>Dirección</th>
					<th>Teléfonos</th>
					<th>Email</th>
					<th>Familiar</th>
					<th>Referido por</th>
					<th>Categoría</th>
					<th class="text-center"> Acciones </th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>

	</div>
</div>

</div>
<!--/.content-->
</div>
<!--/.span9-->
</div>


<!--/.wrapper-->

<script>
	$(document).ready(function() {
		var dataTable = $('#lookup').DataTable({
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
			"ordering": false,
			"processing": true,
			"serverSide": true,
			"ajax": {

				url: "./?action=patients/get-all", // json datasource
				data: {
					"absenceType": "<?php echo $absenceType ?>",
				},
				type: "post", // method  , by default get
				error: function() { // error handling
					$(".lookup-error").html("");
					$("#lookup").append('<tbody class="employee-grid-error"><tr><th colspan="3">No se han encontrado datos.</th></tr></tbody>');
					$("#lookup_processing").css("display", "none");

				}
			},
			"responsive": true,
			"scrollX": true
		});
	});

	function deletePatient(patientId, patientName) {

		const swalWithBootstrapButtons = Swal.mixin({
			buttonsStyling: true
		})

		swalWithBootstrapButtons.fire({
			title: '¿Estás seguro de eliminar al paciente ' + patientName + '?',
			text: "¡No podrás revertirlo!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonText: 'Sí, eliminarlo',
			cancelButtonText: '¡No, cancelarlo!',
			reverseButtons: true
		}).then((result) => {
			if (result.value === true) {
				window.location.href = "index.php?action=patients/delete&id=" + patientId;
			}
		})
	}
</script>