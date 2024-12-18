<?php
	if(count($_GET)>0){
		$category = CategoryMedicData::getById($_GET["id"]);
		$category->is_active = $_GET["isActive"];
		$category->updateStatus();

		LaboratoryData::updateStatusByCategoryMedic($_GET["id"], 0);

		print "<script>window.location='index.php?view=medic-categories/index';</script>";
	}
