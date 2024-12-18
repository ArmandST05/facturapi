<?php
$question = SatisfactionSurveyData::getById($_GET["id"]);
$question->delete();

Core::redir("./index.php?view=satisfaction-survey/index");

?>