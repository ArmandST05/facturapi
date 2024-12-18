<?php
require_once './vendor/autoload.php'; // Asegúrate de ajustar la ruta según tu configuración
use Mpdf\Mpdf;

// Obtener los datos enviados por POST
$legal_name = $_POST['legal_name'] ?? '';
$email = $_POST['email'] ?? '';
$tax_id = $_POST['tax_id'] ?? '';
$street = $_POST['street'] ?? '';
$exterior = $_POST['exterior'] ?? '';
$neighborhood = $_POST['neighborhood'] ?? '';
$municipality = $_POST['municipality'] ?? '';
$state = $_POST['state'] ?? '';
$zip = $_POST['zip'] ?? '';
$payment_form = $_POST['payment_form'] ?? '';
$folio_number = $_POST['folio_number'] ?? '';
$use = $_POST['use'] ?? '';
$date = $_POST['fecha'] ?? '';
$productsJson = $_POST['products'] ?? '[]';
$products = json_decode($productsJson, true);

// Inicializar mPDF
$mpdf = new Mpdf();

// Generar contenido HTML para el PDF
$html = '
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid #000;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <h1 class="text-center">Factura</h1>
    <p><strong>Nombre Legal:</strong> ' . htmlspecialchars($legal_name) . '</p>
    <p><strong>Email:</strong> ' . htmlspecialchars($email) . '</p>
    <p><strong>RFC:</strong> ' . htmlspecialchars($tax_id) . '</p>
    <p><strong>Dirección:</strong></p>
    <p>' . htmlspecialchars($street) . ' ' . htmlspecialchars($exterior) . '<br>
    ' . htmlspecialchars($neighborhood) . '<br>
    ' . htmlspecialchars($municipality) . ', ' . htmlspecialchars($state) . ' ' . htmlspecialchars($zip) . '</p>
    <p><strong>Forma de Pago:</strong> ' . htmlspecialchars($payment_form) . '</p>
    <p><strong>Folio:</strong> ' . htmlspecialchars($folio_number) . '</p>
    <p><strong>Uso del CFDI:</strong> ' . htmlspecialchars($use) . '</p>
    <p><strong>Fecha:</strong> ' . htmlspecialchars($date) . '</p>

    <h2>Productos</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Descripción</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>';

foreach ($products as $product) {
    $total = $product['quantity'] * $product['price'];
    $html .= '
            <tr>
                <td>' . htmlspecialchars($product['product_key']) . '</td>
                <td>' . htmlspecialchars($product['description']) . '</td>
                <td>' . htmlspecialchars(number_format($product['quantity'], 2)) . '</td>
                <td>' . htmlspecialchars(number_format($product['price'], 2)) . '</td>
                <td>' . htmlspecialchars(number_format($total, 2)) . '</td>
            </tr>';
}

$html .= '
        </tbody>
    </table>
</body>
</html>';

// Escribir el contenido HTML al PDF
$mpdf->WriteHTML($html);

// Guardar el PDF en un archivo
$pdfFilePath = 'factura.pdf';
$mpdf->Output($pdfFilePath, \Mpdf\Output\Destination::FILE);

// Mostrar un mensaje de éxito o un enlace para descargar el PDF
echo 'Factura generada con éxito. <a href="' . htmlspecialchars($pdfFilePath) . '" target="_blank">Descargar PDF</a>';
?>
