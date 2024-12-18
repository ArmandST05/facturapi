<?php
// Clase Executor para ejecutar consultas SQL
class Executor {

    // Método estático para ejecutar una consulta SQL
    public static function doit($sql){
        // Obtener la conexión a la base de datos
        $con = Database::getCon();
        // Verificar si se está ejecutando SQL
        if(Core::$debug_sql){
            // Imprimir la consulta SQL entre etiquetas <pre> para depuración
            print "<pre>".$sql."</pre>";
        }
        // Ejecutar la consulta SQL y obtener el resultado y el ID de inserción
        return array($con->query($sql),$con->insert_id);
    }
}
?>
