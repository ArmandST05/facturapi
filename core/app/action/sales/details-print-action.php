<?php
$configuration = ConfigurationData::getAll();
$sale = OperationData::getById($_GET["id"]);
$details = OperationDetailData::getAllProductsByOperationId($_GET["id"]);
$operationReservations = [];
foreach ($details as $detail) {
	$operationReservations[$detail->reservation_id][] = $detail;
}

$payments = OperationPaymentData::getAllByOperationId($_GET["id"]);
$total = 0;
$totalPay = 0;

$userName = "";
if ($sale->user_id != "") {
	$user = $sale->getUser();
	$userName = $user->name . " " . $user->lastname;
}

$TOPE = 0;

date_default_timezone_set('America/Mexico_City');

$clinicContact = $configuration['address']->value . " TEL." . $configuration['phone']->value
	. "<br>----------------------------------------------------------------------------";

$fecha = date("d") . "-" . date("m") . "-" . date("Y") . "      HORA: " . date("h:i:s");
?>

<script type="text/javascript">
	function printPage() {
		if (confirm("¿Imprimir página?")) {

			window.print();
		}
		// La redirección ocurre incluso cuando la página no se ha imprimido
		// si quieres hacer la redirección sólo si la página se ha imprimido
		// inserta la siguiente frase arriba 
		goNow();
	}

	function IMPRIME() {
		window.print();

		//window.close();
	}
</script>

<?php
echo "<html style='width: 370px;height: 500px;'><head>
<link rel='preconnect' href='https://fonts.googleapis.com'>
<link rel='preconnect' href='https://fonts.gstatic.com' crossorigin>
<link href='https://fonts.googleapis.com/css2?family=Roboto&display=swap' rel='stylesheet'>
<style>
body {
  font-family: 'Roboto', sans-serif;
}
</style>
</head>
<body><div id='test'><font size=4><p align='center'><img src='../../assets/clinic-logo.png' width='130px' height='120px'><br><b>" . $configuration['name']->value . "</b></p></font><font size=3><p align='center'><br> ";
echo "" . $clinicContact . "</p>";
echo "<p align='left' style='font-size:14px;'> NO. TICKET <b>" . $sale->id . "</p></b>";
echo "<p align='left' style='font-size:14px;'>FECHA: " . $sale->date_format ." ".$sale->hour_format. "</p>";
echo "<p align='left' style='font-size:14px;'>ATENDIO: " . $userName . "<br></p>";
echo "<p align='center'>---------------------------------------------------------------------------</p>";
echo "<p><table style='font-size:14px; text-align:left;'>";
echo "<tr><th>DESCRIPCION</th>";
echo "<th>CANT</th>";
echo "<th>P.UNIT</th>";
echo "<th>IMPORTE</th></tr>";
foreach ($operationReservations as $indexReservation => $operationReservation) {
	$reservation = ReservationData::getById($indexReservation);
	foreach ($operationReservation as $detail) {
		$product  = $detail->getProduct();
		$TOPE = $TOPE + 1;
		echo "<tr>
		<td align='right'> " . $product->name . "</td>
		<td align='right'> " . number_format($detail->quantity, 2) . "</td>
		<td align='right'> " . number_format($detail->price, 2, ".", ",") . "</td>
		<td align='right'>$ " . number_format($detail->quantity * $detail->price, 2, ".", ",") . "</td></tr>";
		$total += $detail->quantity * $detail->price;
	}
}

echo "</table></p>";
echo "<p align='center'>---------------------------------------------------------------------------</p>";
echo "<p><table style='font-size:14px; text-align:left;'>";
echo "<tr><th colspan='2'>FORMA DE PAGO</th></tr><tr><th>TIPO</th>";
echo "<th>CANT</th></tr>";
foreach ($payments as $payment) {
    echo "<tr align='right'>
        <td align='right'> " . $payment->getType()->name . "</td>
        <td align='right'> " . number_format($payment->total, 2) . "</td></tr>";
    $totalPay += $payment->total;
}
echo "</p><br>";

// Calcular el total real después del descuento
$realTotal = ($total - $sale->discount);

// Calcular el IVA y el subtotal
$subtotal =  $realTotal / 1.16;
$iva = $realTotal - $subtotal;

echo "<p align='right'>" . 'SUBTOTAL:  ' . number_format($subtotal, 2, '.', ',') . "<br>";
echo 'IVA:  ' . number_format($iva, 2, '.', ',') . "<br>";
echo 'TOTAL:  ' . number_format($realTotal, 2, '.', ',') . "<br>";
echo 'PAGO:  ' . number_format($totalPay, 2, '.', ',') . "<br>";
echo 'SALDO:  ' . number_format($realTotal - $totalPay, 2, '.', ',') . "</p>";


echo "<p align='center'>****************************************<br>";
echo "****************************************</p>";
echo "<p align='center'>GRACIAS POR SU COMPRA</p>";
echo "</font></div></body></html>";

if ($TOPE > 0) {
?>
	<script type="text/javascript">
		window.print();
		setTimeout(function() {
			window.close();
		}, 500);

		function confirmar() {
			var flag = confirm("Reimprimir ticket");
			if (flag == true) {
				location.reload(true);

			} else {
				window.close();
			}
		}
	</script>
<?php
} else {
?>
	<script type="text/javascript">
	</script>
<?php
}

?>