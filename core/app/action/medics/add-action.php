<?php
/**
* BookMedik
* @author evilnapsis
* @url http://evilnapsis.com/about/
**/

if(count($_POST)>0){
	$medic = new MedicData();
	$medic->name = trim($_POST["name"]);
	$medic->professional_license = trim($_POST["professional_license"]);
	$medic->study_center = trim($_POST["study_center"]);
	$medic->email = trim($_POST["email"]);
	$medic->phone = trim($_POST["phone"]);
	$medic->category_id = $_POST["category_id"];
	$medic->user_id = $_POST["user_id"];
	$medic->calendar_color = $_POST["calendar_color"];
	$medic->other_specialties = trim($_POST["otherSpecialties"]);
	$medic->is_study_center_prescription = (isset($_POST["isStudyCenterPrescription"])) ? $_POST["isStudyCenterPrescription"]:0;
	$newMedic = $medic->add();

	//Crear carpeta del médico si no existe
	$path = "storage_data/medics/" . $newMedic[1];
	if (!file_exists($path)) {
		mkdir($path, 0777, true);
	}
	
	if ($medic->is_study_center_prescription == 1 && isset($_FILES["studyCenterLogo"]) && ($_FILES["studyCenterLogo"]["size"] > 0)) {
		$originalFileName = $_FILES["studyCenterLogo"]["name"];
		$fileName = "studyCenterLogo.png";
		// File temp source 
		$fileTemp = $_FILES["studyCenterLogo"]["tmp_name"];
		$path = pathinfo($originalFileName);
		$ext = $path['extension'];
		$targetFilePath = "storage_data/medics/" . $newMedic[1] . "/" . $fileName;
		$pathFilenameExtension = $targetFilePath . "." . $ext;

		//Si hay un archivo del mismo nombre, eliminar
		if (file_exists($pathFilenameExtension)) {
			unlink($pathFilenameExtension);
		}
		//Guardar archivo
		if (move_uploaded_file($fileTemp, $targetFilePath)) {
			$medic = MedicData::getById($newMedic[1]);
			$medic->study_center_logo = $fileName;
			$medic->updateStudyCenterLogo();
		}
	}

 print "<script>window.location='index.php?view=medics/index';</script>";
}

?>