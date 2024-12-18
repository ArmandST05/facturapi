<?php
class CategoryMedicData {
	public static $tablename = "medic_categories";

	public $name;
	public $is_active;
	public $created_at;

	public function __construct(){
		$this->name = "";
		$this->created_at = "NOW()";
	}

	public function add(){
		$sql = "INSERT into ".self::$tablename." (name) ";
		$sql .= "value (\"$this->name\")";
		return Executor::doit($sql);
	}

	public function delete(){
		$sql = "DELETE FROM ".self::$tablename." where id=$this->id";
		return Executor::doit($sql);
	}

	public function update(){
		$sql = "UPDATE ".self::$tablename." set name=\"$this->name\",is_active=\"$this->is_active\" where id=$this->id";
		return Executor::doit($sql);
	}

	public function updateStatus(){
		$sql = "UPDATE ".self::$tablename." SET is_active=$this->is_active WHERE id = $this->id";
		return Executor::doit($sql);
	}

	public static function getById($id){
		$sql = "SELECT cm.*,(SELECT count(l.id) FROM laboratories l WHERE medic_category_id = cm.id AND is_active = 1) AS total_laboratories
		FROM ".self::$tablename." cm
		WHERE id = $id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new CategoryMedicData());
	}

	public static function getAll(){
		$sql = "SELECT cm.*,(SELECT count(l.id) FROM laboratories l WHERE medic_category_id = cm.id AND is_active = 1) AS total_laboratories
		FROM ".self::$tablename." cm
		WHERE cm.is_active = 1
		ORDER BY cm.is_active DESC,cm.name ASC";
		$query = Executor::doit($sql);
		return Model::many($query[0],new CategoryMedicData());
	}
	
	public static function getLike($q){
		$sql = "SELECT * FROM ".self::$tablename." where name like '%$q%'";
		$query = Executor::doit($sql);
		return Model::many($query[0],new CategoryMedicData());
	}
}

?>