<?php
// Clase Cookie para acceder a las cookies
// 13 de Octubre del 2014
class Cookie {
    // Método mágico __get para obtener el valor de una cookie
    function __get($value){
        // Verificar si la cookie existe
        if(!$this->exist($value)){
            // Mostrar un mensaje de error si la cookie no existe
            print "<b>COOKIE ERROR</b> El parámetro <b>$value</b> que intentas llamar no existe!";
            die();
        }
        // Devolver el valor de la cookie
        return $_COOKIE[$value];
    }
	
	
    // Método para verificar si una cookie existe
    function exist($value){
        // Inicializar la variable que indica si se encontró la cookie
        $found = false;
        // Verificar si la cookie está definida en $_COOKIE
        if(isset($_COOKIE[$value])){
            // Si la cookie está definida, establecer $found en true
            $found = true;
        }
        // Devolver el valor de $found
        return $found;
    }
}
?>
