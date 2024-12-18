<?php
// Clase Core que gestiona las configuraciones y la carga de contenido
// 14 de Abril del 2014
// Actualizado [11-Ago-2016]
class Core {
    // Propiedades estáticas para el tema, la raíz, el usuario y la depuración SQL
    public static $theme = "";
    public static $root = "";
    public static $user = null;
    public static $debug_sql = false;

    // Método estático para incluir archivos CSS
    public static function includeCSS(){
        // Ruta donde se encuentran los archivos CSS
        $path = "res/css/";
        // Abrir el directorio
        $handle = opendir($path);
        if($handle){
            // Recorrer cada archivo del directorio
            while (false !== ($entry = readdir($handle)))  {
                // Verificar que no sea el directorio actual ni el directorio padre
                if($entry != "." && $entry != ".."){
                    // Construir la ruta completa del archivo
                    $fullpath = $path.$entry;
                    // Verificar que no sea un directorio
                    if(!is_dir($fullpath)){
                        // Incluir la etiqueta <link> para el archivo CSS
                        echo "<link rel='stylesheet' type='text/css' href='".$fullpath."' />";
                    }
                }
            }
            // Cerrar el directorio
            closedir($handle);
        }
    }

    // Método estático para mostrar una alerta en JavaScript
    public static function alert($text){
        // Mostrar una alerta con el texto proporcionado
        echo "<script>alert('".$text."');</script>";
    }

    // Método estático para redirigir a una URL
    public static function redir($url){
        // Redirigir a la URL especificada
        echo "<script>window.location='".$url."';</script>";
    }

    // Método estático para incluir archivos JavaScript
    public static function includeJS(){
        // Ruta donde se encuentran los archivos JavaScript
        $path = "res/js/";
        // Abrir el directorio
        $handle = opendir($path);
        if($handle){
            // Recorrer cada archivo del directorio
            while (false !== ($entry = readdir($handle)))  {
                // Verificar que no sea el directorio actual ni el directorio padre
                if($entry != "." && $entry != ".."){
                    // Construir la ruta completa del archivo
                    $fullpath = $path.$entry;
                    // Verificar que no sea un directorio
                    if(!is_dir($fullpath)){
                        // Incluir la etiqueta <script> para el archivo JavaScript
                        echo "<script type='text/javascript' src='".$fullpath."'></script>";
                    }
                }
            }
            // Cerrar el directorio
            closedir($handle);
        }
    }
}
?>
