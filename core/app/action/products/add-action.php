<?php
if (count($_POST) > 0) {
    // Crear instancia del modelo
    $product = new ProductData();

    // Asignar valores del formulario al modelo
   // $product->barcode = trim($_POST["barcode"]);
    $product->description = trim($_POST["description"]);
    $product->product_key = trim($_POST["product_key"]);
    //$product->price_in = $_POST["priceIn"];
    $product->price_out = $_POST["priceOut"];
    $product->fraction = $_POST["fraction"];
    $product->minimum_inventory = (!empty($_POST["minimumInventory"]) ? $_POST["minimumInventory"] : 0);
    $product->initial_inventory = (!empty($_POST["initialInventory"]) ? $_POST["initialInventory"] : 0);
    $product->expiration_date = $_POST["expirationDate"];
    $product->lot = $_POST["lot"];
    $product->user_id = $_SESSION["user_id"];

    // Llamar al método `add` del modelo
    $result = $product->add();

    if ($result) {
        // Redireccionar al índice de productos si todo fue exitoso
        print "<script>window.location='index.php?view=products/index';</script>";
    } else {
        // Mostrar mensaje de error en caso de fallo
        echo "Error al agregar el producto. Verifica los datos.";
    }
}
