<?php 
if(count($_GET)>0){
	$question = SatisfactionSurveyData::getById($_GET["id"]);
	$question->is_active = $_GET["isActive"];
	$question->updateActive();

	print "<script>window.location='index.php?view=satisfaction-survey/index';</script>";
}