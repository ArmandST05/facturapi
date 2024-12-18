<?php
	if(count($_POST)>0){
		$question = SatisfactionSurveyData::getById($_POST["id"]);
		$question->description = trim($_POST["description"]);
		$question->response_type_id = trim($_POST["responseType"]);
		$question->update();

		print "<script>window.location='index.php?view=satisfaction-survey/index';</script>";
	}
