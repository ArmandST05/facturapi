<?php
// Clase Api para cargar archivos de API
// 15 de Diciembre del 2021
class Api {
    /**
    * @function load
    * @brief La función load carga una API correspondiente a un módulo
    **/	
    public static function load($api){
        // checar si no ha agregadouna API especifica
        if(!isset($_GET['api'])){
            // Incluir el archivo de API predeterminado del módulo
            include "core/app/api/".$api."-api.php";
        } else {
            // Verificar si la API agregada es válida
            if(Api::isValid()){
                // Incluir el archivo de API correspondiente a la API agregada
                include "core/app/api/".$_GET['api']."-api.php";				
            } else {
                // Configurar la respuesta de error si la API no se encuentra
                $response["error"] = true;
                $response["message"] = "Este recurso no existe";
                // Devolver el código de respuesta HTTP 404
                return http_response_code(404);
            }
        }
    }

    /**
    * @function isValid
    * @brief Valida la existencia de una API
    **/	
    public static function isValid(){
        // Inicializar la variable de validación
        $valid = false;
        // Verificar si el archivo de API existe
        if(file_exists($file = "core/app/api/".$_GET['api']."-api.php")){
           
            $valid = true;
        }
        
        return $valid;
    }

    // Método estático para imprimir un mensaje de error
    public static function Error($message){
        // Imprimir el mensaje de error
        print $message;
    }

    // Método para ejecutar una API con parámetros
    public function execute($api,$params){
        // Construir la ruta completa del archivo de API
        $fullpath =  "core/app/api/".$api."-api.php";
        // Verificar si el archivo de API existe y ejecutarlo
        if(file_exists($fullpath)){
            include $fullpath;
        } else {
            
            assert("wtf");
        }
    }
}
?>
