<?php
	if(count($_GET)>0){
		$medic = MedicData::getById($_GET["id"]);
		$medic->is_active = trim($_GET["isActive"]);
		$medic->updateStatus();

		print "<script>window.location='index.php?view=medics/index';</script>";
	}
