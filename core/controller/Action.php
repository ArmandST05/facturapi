<?php


// 12 de Octubre del 2014
// Action.php
// @brief Un action corresponde a una rutina de un modulo.

class Action {
	/**
	* @function load
	* @brief la funcion load carga una vista correspondiente a un modulo
	**/	
	public static function load($action){
		// Module::$module;
		//verifica que el parametro action este en la URL
		if(!isset($_GET['action'])){
			include "core/app/action/".$action."-action.php";
		}else{


			if(Action::isValid()){
				//si es valido manda a llamar a la accion correspondiente 
				include "core/app/action/".$_GET['action']."-action.php";				
			}else{
				//si no, envia un mesaje de error
				Action::Error("<b>404 NOT FOUND</b> Action <b>".$_GET['action']."</b> folder  !! - <a href='http://evilnapsis.com/legobox/help/' target='_blank'>Help</a>");
			}



		}
	}

	/**
	* @function isValid
	* @brief valida la existencia de una vista
	**/	
	public static function isValid(){
		$valid=false;
		if(file_exists($file = "core/app/action/".$_GET['action']."-action.php")){
			$valid = true;
		}
		return $valid;
	}

	public static function Error($message){
		print $message;
	}

	public function execute($action,$params){
		$fullpath =  "core/app/action/".$action."-action.php";
		if(file_exists($fullpath)){
			include $fullpath;
		}else{
			assert("wtf");
		}
	}

}



?>