<?php
    $dateAt = $_POST["date"]." ".$_POST["timeAt"].":00";
    //$dateAtFinalData = $_POST["date"]." ".$_POST["timeAtFinal"].":00";
    //A la fecha final se le quitará un segundo para evitar conflicto en la consulta con cita siguiente
    //$dateAtFinal = date("Y-m-d H:i:s",strtotime($dateAtFinalData." -1 seconds"));
    $dateAtFinal = $_POST["date"]." ".$_POST["timeAtFinal"].":00";
    $reservationId = isset($_POST["reservationId"]) ? $_POST["reservationId"]:0;
    $medicId = 0;
    if(isset($_POST["medicId"]) && $_POST["medicId"] != 0){
        $medicId = $_POST["medicId"];
    }else{
        $user = UserData::getLoggedIn();
        $medic = MedicData::getByUserId($user->id);
        if($medic){
            $medicId = $medic->id;
        }
    }

    $laboratories = LaboratoryData::getAvailableByDate($dateAt,$dateAtFinal,$reservationId,$medicId);  

    echo json_encode($laboratories);
  
?>