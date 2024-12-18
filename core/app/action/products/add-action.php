<?php
if(count($_POST)>0){
  $product = new ProductData();
  $product->barcode = trim($_POST["barcode"]);
  $product->name = trim($_POST["name"]);
  $product->price_in = $_POST["priceIn"];
  $product->price_out = $_POST["priceOut"];
  $product->fraction = $_POST["fraction"];
  $product->minimum_inventory = (($_POST["minimumInventory"] != "") ? $_POST["minimumInventory"]:0);
  $product->user_id = $_SESSION["user_id"];
  $addedProduct = $product->add();


if($_POST["initialInventory"] != "" || $_POST["initialInventory"] != "0"){

  $date = date("d-m-Y (H:i:s)");
  $operation = new OperationDetailData();
  $operation->product_id = $addedProduct[1];
  $operation->operation_type_id = 1;
  $operation->operation_id = 0;
  $operation->quantity = $_POST["initialInventory"];
  $operation->price = $_POST["priceIn"];
  $operation->expiration_date = $_POST["expirationDate"];
  $operation->lot = $_POST["lot"];
  $operation->date = $date;
  $add = $operation->addInput();  

}

print "<script>window.location='index.php?view=products/index';</script>";
}
?>
