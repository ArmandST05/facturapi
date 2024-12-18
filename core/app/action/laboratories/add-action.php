<?php
if(count($_POST)>0){
	$laboratory = new LaboratoryData();
	$laboratory->name = $_POST["name"];
	$laboratory->medic_category_id = $_POST["medic_category_id"];

	if(!$laboratory->add()) Core::alert("Ocurrió un error al agregar.");
	print "<script>window.location='index.php?view=laboratories/index&id=".$laboratory->medic_category_id."';</script>";
}
?>