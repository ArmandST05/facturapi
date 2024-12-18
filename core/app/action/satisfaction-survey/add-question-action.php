<?php
if(count($_POST)>0){
	$question = new SatisfactionSurveyData();
	$question->description = strtoupper(trim($_POST["description"]));
	$question->response_type_id = trim($_POST["responseType"]);
	$question->ordering = 1;
	$question->is_active = 1;
	$newQuestion = $question->add();

 print "<script>window.location='index.php?view=satisfaction-survey/index';</script>";
}

?>