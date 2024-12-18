<?php
// Incluir manualmente los archivos de la librería Facturapi
require_once './vendor/facturapi/facturapi-php/src/InvoiceRelation.php';
require_once './vendor/facturapi/facturapi-php/src/InvoiceType.php';
require_once './vendor/facturapi/facturapi-php/src/Facturapi.php';
require_once './vendor/facturapi/facturapi-php/src/PaymentForm.php';
require_once './vendor/facturapi/facturapi-php/src/TaxType.php';
use Facturapi\Facturapi;

// Obtener ID de la operación desde el parámetro GET
$id = $_GET['id'] ?? '';

// Crear instancia de Facturapi
$facturapi = new Facturapi("sk_test_3NEzMn7dW2xqmwalkp2z0LyvOq418boRLgyvQOZG56");

$response = '';
$invoiceId = ''; // Variable para almacenar el ID de la factura

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Procesar la solicitud POST para crear la factura
    $legal_name = $_POST['legal_name'] ?? '';
    $legal_name = strtoupper($legal_name);
    $trans = array(
        'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u', 'ü' => 'u',
        'Á' => 'A', 'É' => 'E', 'Í' => 'I', 'Ó' => 'O', 'Ú' => 'U', 'Ü' => 'U'
    );
    $legal_name = strtr($legal_name, $trans);
    $legal_name = str_replace(['S.A. DE C.V.', 'S.C.', 'S.R.L.'], '', $legal_name);
    $legal_name = trim($legal_name);
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

    $items = [];
    foreach ($products as $product) {
        $quantity = isset($product['quantity']) ? floatval($product['quantity']) : 0;
        $description = isset($product['description']) ? htmlspecialchars($product['description']) : '';
        $product_key = isset($product['product_key']) ? htmlspecialchars($product['product_key']) : '';
        $price = isset($product['price']) ? floatval($product['price']) : 0;

        $items[] = [
            'quantity' => $quantity,
            'discount' => 0,
            'product' => [
                'description' => $description,
                'product_key' => $product_key,
                'price' => $price,
                'tax_included' => true,
                'taxability' => '01',
                'taxes' => [],
                'local_taxes' => [],
                'sku' => 'default_sku',
            ],
            'parts' => [
                [
                    'description' => 'string',
                    'product_key' => $product_key,
                    'quantity' => $quantity,
                    'sku' => 'string',
                    'unit_price' => $price,
                    'unit_name' => 'string',
                ]
            ],
        ];
    }

    if (count($items) === 0) {
        $response = 'Error al crear la factura: No se han proporcionado ítems.';
    } else {
        try {
            $invoice = $facturapi->Invoices->create([
                'customer' => [
                    'legal_name' => $legal_name,
                    'email' => $email,
                    'tax_id' => $tax_id,
                    'address' => [
                        'street' => $street,
                        'exterior' => $exterior,
                        'neighborhood' => $neighborhood,
                        'municipality' => $municipality,
                        'state' => $state,
                        'zip' => $zip,
                    ],
                    'tax_system' => '606',
                ],
                'items' => $items,
                'payment_form' => $payment_form,
                'folio_number' => $folio_number,
                'use' => $use,
            ]);

            $invoiceId = $invoice->id;
            $response = 'Factura creada con éxito. ID: ' . $invoiceId;
        } catch (Exception $e) {
            $response = 'Error al crear la factura: ' . $e->getMessage();
        }
    }
} elseif (isset($_GET['download_pdf'])) {
    // Descargar el PDF de la factura
    $invoiceId = $_GET['download_pdf'];
    try {
        $pdf = $facturapi->Invoices->download_pdf($invoiceId); // Usar Invoices en lugar de Receipts
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="factura_' . $invoiceId . '.pdf"');
        echo $pdf;
        exit;
    } catch (Exception $e) {
        echo 'Error al descargar el PDF: ' . $e->getMessage();
        exit;
    }
} else {
    // Obtener datos de la operación
    $operation = OperationData::getById($id);
    $details = OperationDetailData::getByOperationId($operation->id) ?? []; 
    $patient = PatientData::getByIdForInvoice($operation->patient_id);

    // Obtener datos del producto para cada detalle
    $products = [];
    foreach ($details as $detail) {
        $product = ProductData::getByIdForInvoice($detail->product_id);
        $products[] = [
            'quantity' => $detail->quantity,
            'description' => $product->name,
            'product_key' => $product->product_key,
            'price' => $detail->price,
            'unit_key' => 'default_unit_key',
            'unit_name' => 'default_unit_name',
            'sku' => 'default_sku',
        ];
    }

    // Preparar datos para el formulario
    $legal_name = $patient->name;
    $email = $patient->email;
    $street = $patient->street;
    $exterior = $patient->number;
    $neighborhood = $patient->colony;
    $municipality = $patient->municipality ?? '';
    $state = $patient->state ?? '';
    $zip = $patient->zip ?? '';
    $folio_number = '';
    $use = '';
    $date = date('Y-m-d');
}
?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Factura</title>
    <!-- Estilos de Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <style>
        .form-group {
            margin-bottom: 15px;
        }
        .uppercase {
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Crear Factura</h2>
        <form action="" method="POST" class="form-horizontal">
            <div class="row">
                <div class="col-md-6">
                        
                    <div class="form-group">
                        <label for="legal_name" class="col-sm-3 control-label">Nombre Legal</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control uppercase" id="legal_name" name="legal_name" value="<?php echo htmlspecialchars($legal_name); ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email" class="col-sm-3 control-label">Email</label>
                        <div class="col-sm-9">
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="tax_id" class="col-sm-3 control-label">RFC</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control uppercase" id="tax_id" name="tax_id" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="street" class="col-sm-3 control-label">Calle</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control uppercase" id="street" name="street" value="<?php echo htmlspecialchars($street); ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="exterior" class="col-sm-3 control-label">Exterior</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" id="exterior" name="exterior" value="<?php echo htmlspecialchars($exterior); ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="neighborhood" class="col-sm-3 control-label">Colonia</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control uppercase" id="neighborhood" name="neighborhood" value="<?php echo htmlspecialchars($neighborhood); ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="municipality" class="col-sm-3 control-label">Municipio</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control uppercase" id="municipality" name="municipality" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="state" class="col-sm-3 control-label">Estado</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control uppercase" id="state" name="state"  required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="zip" class="col-sm-3 control-label">Código Postal</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" id="zip" name="zip" value="<?php echo htmlspecialchars($zip); ?>" required>
                        </div>
                    </div>
                    
                </div>
                <div class="col-md-6">
                <div class="form-group">
                        <label class="col-sm-3 control-label">Productos</label>
                        <div class="col-sm-9">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Producto</th>
                                        <th>Cantidad</th>
                                        <th>Precio</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if (!empty($details) && is_array($details)): ?>
                                <?php foreach ($details as $detail): ?>
                                    <?php
                                        // Obtener el producto para mostrar el nombre
                                        $product = ProductData::getById($detail->product_id);
                                    ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($detail->product_id); ?></td>
                                        <td><?php echo htmlspecialchars($product->name); ?></td>
                                        <td><?php echo htmlspecialchars($detail->quantity); ?></td>
                                        <td><?php echo htmlspecialchars(number_format($detail->price, 2)); ?></td>
                                        <td><?php echo htmlspecialchars(number_format($detail->quantity * $detail->price, 2)); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5">No se encontraron detalles de productos.</td>
                                </tr>
                            <?php endif; ?>
                                </tbody>
                            </table>
                            
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="payment_form" class="col-sm-3 control-label">Forma de Pago</label>
                        <div class="col-sm-9">
                            <select name="payment_form" id="payment_form" class="form-control uppercase" required>
                                <option value="01">Efectivo</option>
                                <option value="02">Cheque Nominativo</option>
                                <option value="03">Transferencia Electrónica</option>
                                <option value="04">Tarjeta de Crédito</option>
                                <option value="05">Monedero Electrónico</option>
                                <option value="06">Dinero Electrónico</option>
                                <option value="08">Vales de Despensa</option>
                                <option value="12">Dación en Pago</option>
                                <option value="13">Subrogación</option>
                                <option value="14">Consignación</option>
                                <option value="15">Condonación</option>
                                <option value="17">Compensación</option>
                                <option value="23">Novación</option>
                                <option value="24">Confusión</option>
                                <option value="25">Remisión de Deuda</option>
                                <option value="26">Prescripción o Caducidad</option>
                                <option value="27">A Satisfacción del Acreedor</option>
                                <option value="28">Tarjeta de Débito</option>
                                <option value="29">Tarjeta de Servicios</option>
                                <option value="99">Por Definir</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="folio_number" class="col-sm-3 control-label">Folio</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" id="folio_number" name="folio_number" value="<?php echo htmlspecialchars($folio_number); ?>" required>
                        </div>
                    </div>
                   
                    <div class="form-group">
                        <label for="use" class="col-sm-3 control-label">Uso del CFDI</label>
                        <div class="col-sm-9">
                            <select class="form-control uppercase" id="use" name="use" required>
                                <option value="G01">Adquisición de mercancías</option>
                                <option value="G02">Devoluciones, descuentos o bonificaciones</option>
                                <option value="G03">Gastos en general</option>
                                <option value="I01">Construcciones</option>
                                <option value="I02">Mobiliario y equipo de oficina por inversiones</option>
                                <option value="I03">Equipo de transporte</option>
                                <option value="I04">Equipo de cómputo y accesorios</option>
                                <option value="I05">Dados, troqueles, moldes, matrices y herramental</option>
                                <option value="I06">Comunicaciones telefónicas</option>
                                <option value="I07">Comunicaciones satelitales</option>
                                <option value="I08">Otra maquinaria y equipo</option>
                                <option value="D01">Honorarios médicos, dentales y gastos hospitalarios</option>
                                <option value="D02">Gastos médicos por incapacidad o discapacidad</option>
                                <option value="D03">Gastos funerales</option>
                                <option value="D04">Donativos</option>
                                <option value="D05">Intereses reales efectivamente pagados por créditos hipotecarios (casa habitación)</option>
                                <option value="D06">Aportaciones voluntarias al SAR</option>
                                <option value="D07">Primas por seguros de gastos médicos</option>
                                <option value="D08">Gastos de transportación escolar obligatoria</option>
                                <option value="D09">Depósitos en cuentas para el ahorro, primas que tengan como base planes de pensiones</option>
                                <option value="D10">Pagos por servicios educativos (colegiaturas)</option>
                                <option value="P01">Por definir</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                    <label for="fecha" class="col-sm-3 control-label">Fecha:</label>
                    <div class="col-sm-9">
                        <input class="form-control" type="date" id="fecha" name="fecha" value="<?php echo htmlspecialchars(date('Y-m-d')); ?>">
                    </div>
                </div>

                </div>
            </div>
            <!-- Campo oculto para los productos -->
            <input type="hidden" name="products" id="products" value='<?php echo htmlspecialchars(json_encode($products)); ?>'>

            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-9">
                    <button type="submit" class="btn btn-primary">Crear Factura</button>
                </div>
            </div>
        </form>
        <?php if (!empty($response)) { ?>
        <div class="alert alert-info">
            <?php echo htmlspecialchars($response); ?>
        </div>
        <?php if ($invoiceId): ?>
        <div class="alert alert-info">
        <a href="https://www.facturapi.io/v2/invoices/<?php echo htmlspecialchars($invoiceId); ?>/pdf" class="btn btn-success" target="_blank">Imprimir Factura</a>
        </div>
        <?php endif; ?>
        <?php } ?>
    </div>
</body>

    </div>

<script>
   document.querySelector('form').addEventListener('submit', function() {
        // Convertir todos los campos de texto a mayúsculas
        document.querySelectorAll('input[type="text"], input[type="number"]').forEach(function(input) {
            input.value = input.value.toUpperCase();
        });
    });
  
</script>
</html>
