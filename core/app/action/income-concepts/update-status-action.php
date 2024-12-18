<?php
	if(count($_GET)>0){
		$concept = ProductData::getById($_GET["id"]);
		$concept->is_active = $_GET["isActive"];
		$concept->updateStatus();

		print "<script>window.location='index.php?view=income-concepts/index';</script>";
	}
