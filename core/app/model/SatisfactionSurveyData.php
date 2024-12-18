<?php
class SatisfactionSurveyData
{
	public static $tablename = "satisfaction_survey_questions";
	public static $tablenameReservation = "reservation_satisfaction_survey";
	public $id;
	public $description;
	public $response_type_id;
	public $ordering;
	public $is_active;
	public $satisfaction_survey_question_id;
	public $response;
	public $reservation_id;
	public $created_at;

	public function __construct()
	{
		$this->created_at = "NOW()";
	}

	public function add()
	{
		$sql = "INSERT INTO " . self::$tablename . " (description,response_type_id,ordering,is_active) ";
		$sql .= "VALUE (\"$this->description\",\"$this->response_type_id\",\"$this->ordering\",\"$this->is_active\")";
		return Executor::doit($sql);
	}

	public function updateActive()
	{
		$sql = "UPDATE " . self::$tablename . " SET is_active = $this->is_active WHERE id=$this->id";
		return Executor::doit($sql);
	}

	public function update()
	{
		$sql = "update " . self::$tablename . " set description=\"$this->description\",response_type_id=\"$this->response_type_id\",ordering=\"$this->ordering\",is_active=\"$this->is_active\" WHERE id = $this->id";
		Executor::doit($sql);
	}

	public static function getById($id)
	{
		$sql = "SELECT * FROM " . self::$tablename . " WHERE id='$id'";
		$query = Executor::doit($sql);
		return Model::one($query[0], new SatisfactionSurveyData());
	}

	public static function getAllByStatus($isActive)
	{
		$sql = "SELECT q.*,rt.name AS response_type_name FROM " . self::$tablename . " q
		INNER JOIN satisfaction_survey_response_types rt ON q.response_type_id = rt.id
		WHERE q.is_active = $isActive 
		ORDER by q.ordering,id ASC";
		$query = Executor::doit($sql);
		return Model::many($query[0], new SatisfactionSurveyData());
	}

	public static function deleteById($id)
	{
		$sql = "DELETE FROM " . self::$tablename . " WHERE id=$id";
		return Executor::doit($sql);
	}

	public function delete()
	{
		$sql = "DELETE FROM " . self::$tablename . " WHERE id=$this->id";
		return Executor::doit($sql);
	}
	/*-----------------------RESERVATIONS----------------------- */

	public function addReservation()
	{
		$sql = "INSERT INTO " . self::$tablenameReservation . " (reservation_id,satisfaction_survey_question_id,description,response_type_id,ordering,response) ";
		$sql .= "VALUE (\"$this->reservation_id\",\"$this->satisfaction_survey_question_id\",\"$this->description\",\"$this->response_type_id\",\"$this->ordering\",\"$this->response\")";
		return Executor::doit($sql);
	}

	public function updateReservation()
	{
		$sql = "UPDATE " . self::$tablenameReservation . " SET response=\"$this->response\" WHERE id = $this->id";
		return Executor::doit($sql);
	}

	public static function getByIdReservation($id)
	{
		$sql = "SELECT * FROM " . self::$tablenameReservation . " WHERE id='$id'";
		$query = Executor::doit($sql);
		return Model::one($query[0], new SatisfactionSurveyData());
	}
	
	public static function getAllByReservation($reservationId)
	{
		$sql = "SELECT rq.* 
		FROM " . self::$tablenameReservation . " rq
		INNER JOIN satisfaction_survey_response_types rt ON rq.response_type_id = rt.id
		WHERE rq.reservation_id = $reservationId 
		ORDER by rq.ordering,id ASC";
		$query = Executor::doit($sql);
		return Model::many($query[0], new SatisfactionSurveyData());
	}
}
