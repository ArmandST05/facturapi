<?php
if(count($_POST)>0){
	$category = new CategoryMedicData();
	$category->name = $_POST["name"];
	$totalLaboratories = $_POST["totalLaboratories"];

	$categoryId = $category->add();
	if($categoryId){
		//Agregar cantidad de laboratorios especificada
		for($i = 1;$i<= $totalLaboratories;$i++){
			$laboratory = new LaboratoryData();
			$laboratory->name = "CAMILLA ".$i;
			$laboratory->medic_category_id = $categoryId;
			$laboratory->add();
		}

	}else Core::alert("Ocurrió un error al agregar los datos.");
	print "<script>window.location='index.php?view=medic-categories/index';</script>";
}
?>