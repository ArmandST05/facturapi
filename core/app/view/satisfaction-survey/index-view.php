<?php
$questions = SatisfactionSurveyData::getAllByStatus(1);
$responseTypes = SatisfactionSurveyResponseData::getAll();
?>
<div class="row">
	<div class="col-md-12">
		<div class="btn-group  pull-right">
			<button class="btn btn-default" onclick="newQuestion()"><i class="fas fa-plus"></i> Nueva pregunta</button>

		</div>
		<script type="text/javascript">
			function confirmar() {
				var flag = confirm("¿Seguro que deseas eliminar la pregunta?");
				if (flag == true) {
					return true;
				} else {
					return false;
				}
			}
		</script>
		<h1>Encuesta de satisfacción</h1>
		<div class="clearfix"></div>

		<div id="sortable">
			<div class="row">
				<div class="col-md-1"><b>#</b></div>
				<div class="col-md-5"><b>Pregunta</b></div>
				<div class="col-md-4"><b>Tipo respuesta</b></div>
				<div class="col-md-2"></div>
			</div>
			<?php foreach ($questions as $index => $question) : ?>
				<div class="row" class="ui-state-default">
					<div class="col-md-1"><?php echo ($index + 1) ?></div>
					<div class="col-md-5"><?php echo $question->description ?></div>
					<div class="col-md-4"><?php echo $question->response_type_name ?></div>
					<div class="col-md-2"><button onclick="editQuestion('<?php echo $question->id ?>','<?php echo $question->description ?>',<?php echo $question->response_type_id ?>)" class="btn btn-warning btn-xs"><i class="fas fa-pencil-alt"></i> Editar</button>
						<button class="btn btn-danger btn-xs" onclick="deleteQuestion('<?php echo $question->id ?>')"><i class="fas fa-trash"></i> Eliminar</button>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>
</div>
</div>
<!-- Modal New Question-->
<div class="modal fade" id="modalNewQuestion" tabindex="-1" role="dialog" aria-labelledby="modalNewQuestionTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<form class="form-horizontal" method="post" enctype="multipart/form-data" action="index.php?action=satisfaction-survey/add-question" role="form">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLongTitle">Nueva pregunta</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label for="inputEmail1" class="col-lg-2 control-label">Pregunta*</label>
						<div class="col-md-12">
							<textarea name="description" class="form-control" id="description" placeholder="Escribe la pregunta" required></textarea>
						</div>
					</div>
					<div class="form-group">
						<label for="inputEmail1" class="col-lg-3 control-label">Tipo respuesta*</label>
						<div class="col-md-12">
							<select name="responseType" class="form-control" required>
								<?php foreach ($responseTypes as $responseType) : ?>
									<option value="<?php echo $responseType->id; ?>"><?php echo $responseType->name; ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
					<button type="submit" class="btn btn-primary">Guardar</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- Modal New Question-->

<!-- Modal Edit Question-->
<div class="modal fade" id="modalEditQuestion" tabindex="-1" role="dialog" aria-labelledby="modalEditQuestionTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<form class="form-horizontal" method="post" enctype="multipart/form-data" action="index.php?action=satisfaction-survey/update-question" role="form">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLongTitle">Editar pregunta</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label for="inputEmail1" class="col-lg-2 control-label">Pregunta*</label>
						<div class="col-md-12">
							<textarea name="description" class="form-control" id="descriptionEdit" placeholder="Escribe la pregunta" required></textarea>
						</div>
					</div>
					<div class="form-group">
						<label for="inputEmail1" class="col-lg-3 control-label">Tipo respuesta*</label>
						<div class="col-md-12">
							<select name="responseType" id="responseTypeEdit" class="form-control" required>
								<?php foreach ($responseTypes as $responseType) : ?>
									<option value="<?php echo $responseType->id; ?>"><?php echo $responseType->name; ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="id" id="editId">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
					<button type="submit" class="btn btn-primary">Guardar</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- Modal Edit Question-->
<script>
	function newQuestion() {
		$("#modalNewQuestion").modal("show");
	}

	function editQuestion(id, description, responseType) {
		$("#descriptionEdit").val(description);
		$("#responseTypeEdit").val(responseType);
		$("#editId").val(id);
		$("#modalEditQuestion").modal("show");
	}

	function deleteQuestion(id) {

		const swalWithBootstrapButtons = Swal.mixin({
			buttonsStyling: true
		})

		swalWithBootstrapButtons.fire({
			title: '¿Estás seguro de eliminar la pregunta?',
			text: "¡No podrás revertirlo!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonText: 'Sí, eliminarla',
			cancelButtonText: '¡No, cancelarla!',
			reverseButtons: true
		}).then((result) => {
			if (result.value === true) {
				window.location.href = "index.php?action=satisfaction-survey/update-status-question&id=" + id+"&isActive=0";
			}
		})
	}

	$(function() {
		$("#sortable").sortable();
		$("#sortable").disableSelection();
	});
</script>