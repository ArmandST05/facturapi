<?php
	if(count($_GET)>0){
		$user = UserData::getById($_GET["id"]);
		$user->is_active = $_GET["isActive"];
		$user->updateStatus();

		print "<script>window.location='index.php?view=users/index';</script>";
	}
