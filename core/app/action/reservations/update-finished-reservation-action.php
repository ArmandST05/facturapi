<?php
if(count($_POST)>0){
      $reservation = ReservationData::getById($_POST["reservationId"]);

      if($reservation){
         $reservation->reservation_id = $_POST["reservationId"];
         $reservation->is_finished = $_POST["isFinished"];

         if($reservation->updateFinishedStatus()){

            //Generar la entrevista
            $questions = SatisfactionSurveyData::getAllByStatus(1);

            foreach($questions as $index=>$question){
               $newQuestion = new SatisfactionSurveyData();
               $newQuestion->reservation_id = $_POST["reservationId"];
               $newQuestion->satisfaction_survey_question_id = $question->id;
               $newQuestion->description = $question->description;
               $newQuestion->response_type_id = $question->response_type_id;
               $newQuestion->response = null;
               $newQuestion->ordering = ($index+1);
               $newQuestion->addReservation();
            }
            return http_response_code(200);
         }
         else return http_response_code(500);
      } 
}
else{
   return http_response_code(500);
}
?>