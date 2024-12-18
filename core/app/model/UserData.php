<?php
class UserData {
	public static $tablename = "users";

	public function __construct(){
		$this->name = "";
		$this->is_active = "";
		$this->lastname = "";
		$this->email = "";
		$this->image = "";
		$this->password = "";
		$this->created_at = "NOW()";
	}

	public function getUserType(){ 
		return UserTypeData::getByName($this->user_type); 
	}

	public function add(){
		$sql = "INSERT INTO ".self::$tablename." (name,username,password,created_at,user_type) ";
		$sql .= "value (\"$this->name\",\"$this->username\",\"$this->password\",$this->created_at,\"$this->user_type\")";
		Executor::doit($sql);
	}

	public function update(){
		$sql = "update ".self::$tablename." set name=\"$this->name\",username=\"$this->username\",user_type=\"$this->user_type\" WHERE id=$this->id";
		Executor::doit($sql);
	}

	public function updateStatus(){
		$sql = "UPDATE ".self::$tablename." SET is_active=$this->is_active WHERE id = $this->id";
		return Executor::doit($sql);
	}

	public function updatePassword(){
		$sql = "update ".self::$tablename." SET password=\"$this->password\" WHERE id = $this->id";
		Executor::doit($sql);
	}

	public static function getById($id){
		$sql = "select * from ".self::$tablename." WHERE id = '$id'";
		$query = Executor::doit($sql);
		return Model::one($query[0],new UserData());
	}

	public static function getLoggedIn(){
		//Obtener datos del usuario logueado en el sistema
		$id = isset($_SESSION["user_id"])  ? $_SESSION["user_id"] : null;
		$sql = "SELECT * FROM ".self::$tablename." WHERE id = '$id'";
		$query = Executor::doit($sql);
		return Model::one($query[0],new UserData());
	}

	public static function getByMail($mail){
		$sql = "select * from ".self::$tablename." WHERE is_active= 1 AND user_type != 'api' AND email = \"$mail\"";
		$query = Executor::doit($sql);
		return Model::one($query[0],new UserData());
	}

	public static function validateApi($username,$authenticationToken){
		$sql = "SELECT * FROM ".self::$tablename." 
		WHERE is_active= 1 AND username = '$username' AND authentication_token = '$authenticationToken'
		LIMIT 1";
		$query = Executor::doit($sql);
		return Model::one($query[0],new UserData());
	}

	public static function getAll(){
		$sql = "SELECT * FROM ".self::$tablename ." WHERE is_active= 1 AND user_type != 'api' order by name ASC";
		$query = Executor::doit($sql);
		return Model::many($query[0],new UserData());
	}

	public static function getUnassigned(){
		//Obtiene los usuarios que no se han asignado a ningún médico.
		$sql = "SELECT u.* FROM ".self::$tablename ." u
		left join medics m on u.id = m.user_id 
		WHERE u.is_active = 1 AND user_type != 'api' AND m.id is null 
		order by u.name ASC";
		$query = Executor::doit($sql);
		return Model::many($query[0],new UserData());
	}

	public static function getLike($q){
		$sql = "select * from ".self::$tablename." WHERE is_active= 1 AND user_type != 'api' AND name like '%$q%'";
		$query = Executor::doit($sql);
		return Model::many($query[0],new UserData());
	}

	public static function deleteById($id){
		$sql = "delete from ".self::$tablename." WHERE id=$id";
		Executor::doit($sql);
	}
	public function changeStatusById(){
		$sql = "update ".self::$tablename." set is_active=\"$this->status_id\" where id=$this->id";
		Executor::doit($sql);
	}
}
?>