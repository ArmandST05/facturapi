<?php
	if(count($_POST)>0){
		foreach($_POST["questions"] as $index=>$value){
			$question = SatisfactionSurveyData::getByIdReservation($index);
			$question->response = trim($value);
			$question->updateReservation();
		}

		print "<script>window.location='index.php?view=reservations/details&id=".$_POST["reservationId"]."';</script>";
	}
