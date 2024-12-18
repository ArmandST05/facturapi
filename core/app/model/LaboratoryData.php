<?php
class LaboratoryData
{
	public static $tablename = "laboratories";

	public $name;
	public $id;
	public $created_at;
	public $is_active;
	public $medic_category_id;

	public function __construct()
	{
		$this->id = "";
		$this->name = "";
		$this->created_at = "NOW()";
	}

	public function add()
	{
		$sql = "INSERT INTO " . self::$tablename . " (name,medic_category_id) ";
		$sql .= "VALUE (\"$this->name\",\"$this->medic_category_id\")";
		return Executor::doit($sql);
	}

	public function update()
	{
		$sql = "UPDATE " . self::$tablename . " set name=\"$this->name\",is_active=\"$this->is_active\" WHERE id=$this->id";
		return Executor::doit($sql);
	}

	public function delete()
	{
		$sql = "DELETE FROM " . self::$tablename . " WHERE id=$this->id";
		return Executor::doit($sql);
	}

	public static function getById($id)
	{
		$sql = "SELECT * FROM " . self::$tablename . " WHERE id = '$id'";
		$query = Executor::doit($sql);
		return Model::one($query[0], new LaboratoryData());
	}

	public static function getAll()
	{
		$sql = "SELECT * FROM " . self::$tablename . " l 
		INNER JOIN  " . CategoryMedicData::$tablename . " cm ON cm.id = l.medic_category_id
		AND cm.is_active = 1
		ORDER BY l.is_active,l.name ASC";
		$query = Executor::doit($sql);
		return Model::many($query[0], new LaboratoryData());
	}

	public static function getByCategoryMedic($categoryId)
	{
		$sql = "SELECT * FROM " . self::$tablename . " 
			WHERE medic_category_id = '$categoryId' 
			ORDER BY id ASC";
		$query = Executor::doit($sql);
		return Model::many($query[0], new LaboratoryData());
	}

	public static function updateStatusByCategoryMedic($categoryId, $statusId)
	{
		$sql = "UPDATE " . self::$tablename . " set is_active=\"$statusId\" WHERE medic_category_id=$categoryId";
		return Executor::doit($sql);
	}

	public static function getByStatus($statusId)
	{
		$sql = "SELECT * FROM " . self::$tablename . " WHERE is_active = '$statusId'
		ORDER BY name ASC";
		$query = Executor::doit($sql);
		return Model::many($query[0], new LaboratoryData());
	}

	public static function getLike($q)
	{
		$sql = "SELECT * FROM " . self::$tablename . " WHERE name like '%$q%'";
		$query = Executor::doit($sql);
		return Model::many($query[0], new LaboratoryData());
	}

	public static function getAvailableByDate($startDateTime, $endDateTime, $reservationId,$medicId)
	{
		//Obtiene los consultorios que no estén vinculados a una cita en el mismo horario
		$sql = "SELECT l.* 
			FROM " . self::$tablename . " l
			WHERE l.is_active = 1
			AND l.medic_category_id = (SELECT m.category_id FROM " . MedicData::$tablename . " m
			WHERE m.id = '$medicId')
			AND (SELECT r.laboratory_id 
				FROM " . ReservationData::$tablename . " r
				WHERE r.laboratory_id = l.id
				AND (DATE_FORMAT('$startDateTime', '%Y-%m-%d %H:%i:%s') = r.date_at OR DATE_FORMAT('$endDateTime', '%Y-%m-%d %H:%i:%s') = r.date_at_final OR
				(DATE_FORMAT('$startDateTime', '%Y-%m-%d %H:%i:%s') < r.date_at && DATE_FORMAT('$endDateTime', '%Y-%m-%d %H:%i:%s') > r.date_at) OR 
				(DATE_FORMAT('$startDateTime', '%Y-%m-%d %H:%i:%s') > r.date_at && DATE_FORMAT('$endDateTime', '%Y-%m-%d %H:%i:%s') <= r.date_at_final)
				)
				AND r.id != '$reservationId' LIMIT 1) IS NULL";
		$query = Executor::doit($sql);
		return Model::many($query[0], new LaboratoryData());
	}
}
