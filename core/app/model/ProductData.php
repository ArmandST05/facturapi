<?php

class ProductData {
    public static $tablename = "products";

    public function __construct() {
        $this->name = "";
        $this->price_in = "";
        $this->price_out = "";
        $this->unit = "";
        $this->user_id = "";
        $this->presentation = "0";
        $this->product_key = "";
        $this->created_at = "NOW()";
        $this->minimum_inventory = 0;
        $this->initial_inventory = 0;
        $this->expiration_date = "";
        $this->lot = "";
        $this->fraction = "";
    }

    public function getExpenseCategory(){ 
        return ExpenseCategoryData::getById($this->expense_category_id);
    }
    
    public function getType(){ 
        return ProductTypeData::getById($this->type_id);
    }

    // Método para agregar un producto
    public function add() {
        try {
            // Datos del producto desde la clase
            $productData = [
                "name" => $this->name,
                "price_in" => $this->price_in,
                "price_out" => $this->price_out,
                "unit" => $this->unit,
                "user_id" => $this->user_id,
                "presentation" => $this->presentation,
                "product_key" => $this->product_key,
                "created_at" => $this->created_at,
                "minimum_inventory" => $this->minimum_inventory,
                "initial_inventory" => $this->initial_inventory,
                "expiration_date" => $this->expiration_date,
                "lot" => $this->lot,
                "fraction" => $this->fraction
            ];

            // Insertar los datos del producto en la base de datos
            $sql = "INSERT INTO " . self::$tablename . " 
                    (name, price_in, price_out, unit, user_id, presentation, product_key, created_at, minimum_inventory, initial_inventory, expiration_date, lot, fraction) 
                    VALUES 
                    (
                        \"{$this->name}\", 
                        \"{$this->price_in}\", 
                        \"{$this->price_out}\", 
                        \"{$this->unit}\", 
                        \"{$this->user_id}\", 
                        \"{$this->presentation}\", 
                        \"{$this->product_key}\", 
                        NOW(), 
                        \"{$this->minimum_inventory}\", 
                        \"{$this->initial_inventory}\", 
                        \"{$this->expiration_date}\", 
                        \"{$this->lot}\", 
                        \"{$this->fraction}\"
                    )";

            // Ejecutar la inserción en la base de datos
            return Executor::doit($sql);
        } catch (Exception $e) {
            // Manejo de excepciones
            echo "Error al agregar el producto: " . $e->getMessage();
            return false;
        }
    }


	

	

	public function update(){
        $sql = "UPDATE ".self::$tablename." 
            SET barcode=\"$this->barcode\", 
                name=\"$this->name\", 
                price_in=\"$this->price_in\", 
                price_out=\"$this->price_out\", 
                minimum_inventory=\"$this->minimum_inventory\", 
                is_active_user=\"$this->is_active_user\", 
                product_key=\"$this->product_key\" 
            WHERE id=$this->id";
        Executor::doit($sql);
    }

	public function updateStatus(){
		$sql = "UPDATE ".self::$tablename." SET is_active=$this->is_active WHERE id = $this->id";
		return Executor::doit($sql);
	}

	public function delete(){
		$sql = "DELETE FROM ".self::$tablename." WHERE id = $this->id";
		Executor::doit($sql);
	}

	public function deactivate(){
		$sql = "UPDATE ".self::$tablename." SET is_active = 0 WHERE id=$this->id";
		Executor::doit($sql);
	}

	public function updateCategory(){
		//Actualiza la categoría de un producto.
		$sql = "UPDATE ".self::$tablename." SET expense_category_id = $this->expense_category_id WHERE id=$this->id";
		Executor::doit($sql);
	}

	public function update_image(){
		$sql = "UPDATE ".self::$tablename." SET image=\"$this->image\" WHERE id=$this->id";
		Executor::doit($sql);
	}

	public static function getById($id){
		$sql = "SELECT * FROM ".self::$tablename." WHERE id = $id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new ProductData());
	}
	
	public static function getAll($name){
		$sql = "SELECT * FROM ".self::$tablename." WHERE name like '%$name%'  AND (type_id='4' OR type_id='3') AND is_active = 1 
		ORDER BY name DESC";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}

	public static function getByBarcode($barcode){
		$sql = "SELECT * FROM ".self::$tablename." WHERE barcode = '$barcode' AND is_active = 1 ";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}

	public static function getByName($name){
		$sql = "SELECT * FROM ".self::$tablename." WHERE name='$name' AND is_active = 1 ";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}

	public static function getAllByPage($start_from,$limit){
		$sql = "SELECT * FROM ".self::$tablename." WHERE id >= $start_from AND type_id='4' AND is_active = 1 limit $limit";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}
	//Obtiene los insumos o medicamentos cuyo nombre coincida con el parámetro de búsqueda
	public static function getLike($p){
		$sql = "SELECT * FROM ".self::$tablename." WHERE is_active = 1 AND (type_id='4' OR type_id='1') OR (name like '%$p%')";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}

	//Obtiene los productos de un tipo específico
	public static function getAllByTypeId($typeId){
		$sql = "SELECT * FROM ".self::$tablename." WHERE type_id='$typeId' AND is_active = 1 ORDER BY name";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}

	public static function getAllByUserId($user_id){
		$sql = "SELECT * FROM ".self::$tablename." WHERE is_active = 1 AND user_id=$user_id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}

    public static function getTypePayId($id){
		$sql = "SELECT * FROM pay  WHERE typePay='EGRESOS' AND idSell='$id'";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}
	
	/*-----------CONCEPTOS INGRESOS---------- */
	public function addIncomeConcept(){
		$sql = "INSERT INTO ".self::$tablename." (name,type_id,description,price_in,price_out) value (\"$this->name\",'1',\"$this->description\",\"$this->price_in\",\"$this->price_out\")";
		return Executor::doit($sql);
	}

	public function updateIncomeConcept(){
		$sql = "UPDATE ".self::$tablename." set name=\"$this->name\",description=\"$this->description\",price_in=\"$this->price_in\",price_out=\"$this->price_out\" WHERE id = $this->id";
		return Executor::doit($sql);
	}

	/*----------SUPPLIES------------ */
	public function addSupply(){
		$sql = "INSERT INTO ".self::$tablename." (name,minimum_inventory,user_id,type_id,expense_category_id) 
		VALUES ('$this->name','$this->minimum_inventory','$this->user_id','3',9)";
		return Executor::doit($sql);
	}

	public function updateSupply(){
		//Actualiza un producto de tipo supplies
		$sql = "UPDATE ".self::$tablename." SET name=\"$this->name\",minimum_inventory=\"$this->minimum_inventory\" WHERE id = $this->id";
		Executor::doit($sql);
	}
	
	/*----------EXPENSE CONCEPTS------------ */
	public function addExpenseConcept(){
		$sql = "INSERT INTO ".self::$tablename." (name,expense_category_id,type_id) 
		VALUES ('$this->name','$this->expense_category_id','$this->type_id')";
		return Executor::doit($sql);
	}

	public function updateExpenseConcept(){
		$sql = "UPDATE ".self::$tablename." SET name=\"$this->name\",expense_category_id=\"$this->expense_category_id\" WHERE id = $this->id";
		Executor::doit($sql);
	}



	public static function getByIdForInvoice($id)
	{
		$sql = "SELECT id, name, price_in, product_key FROM " . self::$tablename . " WHERE id = '$id'";
		$query = Executor::doit($sql);
		return Model::one($query[0], new ProductData()); // Ajusta si es necesario
	}
	


}
