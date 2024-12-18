<?php
if(count($_POST)>0){
	$categoryId = $_POST["id"];
	$category = CategoryMedicData::getById($categoryId);
	$category->name = $_POST["name"];
	//$category->is_active = (isset($_POST["isActive"]))? $_POST["isActive"]:0;
	$totalLaboratories = (isset($_POST["totalLaboratories"])) ? $_POST["totalLaboratories"]:0;

	$actualLaboratories = LaboratoryData::getByCategoryMedic($categoryId);
	$totalActualLaboratories = count($actualLaboratories);

	LaboratoryData::updateStatusByCategoryMedic($categoryId, 1);//Validar que estén todos activos

	if($totalLaboratories > $totalActualLaboratories){//Agregar nuevos

		for($i = $totalActualLaboratories;$i< $totalLaboratories;$i++){
			$laboratory = new LaboratoryData();
			$laboratory->name = "CAMILLA ".$i+1;
			$laboratory->medic_category_id = $categoryId;
			$laboratory->add();
		}

	}else if($totalLaboratories < $totalActualLaboratories){//Desactivar laboratorios que no se utilizarán
		for($i = $totalLaboratories;$i< $totalActualLaboratories;$i++){

			$laboratory = $actualLaboratories[$i];
			$laboratory->is_active = 0;
			$laboratory->update();

		}
	}
		
	if(!$category->update()) Core::alert("Ocurrió un error al actualizar.");
	print "<script>window.location='index.php?view=medic-categories/index';</script>";
}
