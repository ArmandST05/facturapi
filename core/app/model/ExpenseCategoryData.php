<?php
class ExpenseCategoryData
{
	public static $tablename = "expense_categories";
	public $id;
	public $name;
	public $is_active;
	public $created_at;

	public function __construct()
	{
		$this->created_at = "NOW()";
	}

	public function add($name)
	{
		$sql = "INSERT INTO " . self::$tablename . " (name) value ('$name')";
		Executor::doit($sql);
	}

	public function update()
	{
		$sql = "UPDATE " . self::$tablename . " SET name=\"$this->name\" WHERE id = $this->id";
		Executor::doit($sql);
	}

	public function updateStatus(){
		$sql = "UPDATE ".self::$tablename." SET is_active=$this->is_active WHERE id = $this->id";
		return Executor::doit($sql);
	}
	
	public static function getById($id)
	{
		$sql = "SELECT * FROM " . self::$tablename . " WHERE id = $id";
		$query = Executor::doit($sql);
		return Model::one($query[0], new ExpenseCategoryData());
	}

	public static function getByName($name)
	{
		$sql = "SELECT * FROM " . self::$tablename . " WHERE is_active = 1 AND name = '$name'";
		$query = Executor::doit($sql);
		return Model::many($query[0], new ExpenseCategoryData());
	}

	public static function getAll()
	{
		$sql = "SELECT * FROM " . self::$tablename . " WHERE is_active = 1 ORDER BY name";
		$query = Executor::doit($sql);
		return Model::many($query[0], new ExpenseCategoryData());
	}
}