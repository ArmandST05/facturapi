<?php
	if(count($_GET)>0){
		$medic = ExpenseCategoryData::getById($_GET["id"]);
		$medic->is_active = $_GET["isActive"];
		$medic->updateStatus();

		print "<script>window.location='index.php?view=expense-categories/index';</script>";
	}
