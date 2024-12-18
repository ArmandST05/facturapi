<?php
class SatisfactionSurveyResponseData {
	public static $tablename = "satisfaction_survey_response_types";
	public $id;
	public $name;
	public $options;
	public $created_at;

	public function __construct(){
		$this->created_at = "NOW()";
	}

	public static function getById($id){
		$sql = "SELECT * FROM ".self::$tablename." WHERE id='$id'";
		$query = Executor::doit($sql);
		return Model::one($query[0],new SatisfactionSurveyResponseData());
	}

	public static function getAll(){
		$sql = "SELECT * FROM ".self::$tablename." order by name";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SatisfactionSurveyResponseData());
	}
}

?>