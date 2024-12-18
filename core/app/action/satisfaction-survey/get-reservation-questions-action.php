<?php
$questions = SatisfactionSurveyData::getAllByReservation($_GET["reservationId"]);
$html = '<div class="row">';
$html .= '<div class="col-md-1"><b>#</b></div>';
$html .= '<div class="col-md-5"><b>PREGUNTA</b></div>';
$html .= '<div class="col-md-6"><b>RESPUESTA</b></div>';
$html .= '</div><hr>';

foreach ($questions as $index => $question) {
	$html .= '<div class="row">';
	$html .= '<div class="col-md-1">' . ($index + 1) . '</div>';
	$html .= '<div class="col-md-5">' . $question->description . '</div>';
	$html .= '<div class="col-md-6">';
	if ($question->response_type_id == 1) { //Sí NO
		$html .= '<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="questions[' . $question->id . ']" value="1" ' . (($question->response == 1) ? "checked" : "") . '>
						<label class="form-check-label">Sí</label>
					</div>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="questions[' . $question->id . ']" value="0" ' . (($question->response == 0) ? "checked" : "") . '>
						<label class="form-check-label">No</label>
					</div>';
	} else if ($question->response_type_id == 2) { //ABIERTA
		$html .= '<input class="form-control" type="text" name="questions[' . $question->id . ']" value="' . $question->response . '"></input>';
	}
	$html .= '</div>';
	$html .= '</div><hr>';
}
if (count($questions) > 0) {
	$html .= '<div class="row">';
	$html .= '<div class="col-md-12"><div class="pull-right"><button type="submit" class="btn btn-primary btn-sm">Guardar encuesta</button>
	<input type="hidden" name="reservationId" value="'.$_GET["reservationId"].'">
	</div></div>';
	$html .= '</div>';
}

echo $html;
