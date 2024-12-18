
<?php
$conn = Database::getCon();
/* Database connection end */
$user = UserData::getLoggedIn();
$userType = $user->user_type;



// storing  request (ie, get/post) global array to a variable  
$requestData = $_REQUEST;

$columns = array(
    // datatable column index  => database column name
    0 => 'id',
    1 => 'day_name',
    2 => 'date',
    3 => 'name',
    4 => 'total',
    5 => 'description',
    6 => 'pag',
    7 => 'fac',
    8 => 'invoice_number',
    9 => 'bank',
    10 => 'status_id'
);

// getting total number records without any search
$sql = "SELECT o.status_id, o.description, o.bank, o.invoice_number, o.id, o.total, o.created_at, p.name, 
        CONCAT(ELT(WEEKDAY(o.created_at) + 1, 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo')) AS day_name, 
        DATE_FORMAT(o.created_at, '%d/%m/%Y') as date 
        FROM operations o, patients p 
        WHERE p.id = o.patient_id AND o.operation_type_id = '2' AND o.operation_category_id = '1'";
$query = mysqli_query($conn, $sql) or die("Error in the database query: " . mysqli_error($conn));
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows

if (!empty($requestData['search']['value'])) {
    // if there is a search parameter
    $sql = "SELECT o.status_id, o.description, o.bank, o.invoice_number, o.id, o.total, o.created_at, o.is_invoice, p.name, 
            CONCAT(ELT(WEEKDAY(o.created_at) + 1, 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo')) AS day_name, 
            DATE_FORMAT(o.created_at, '%d/%m/%Y') as date 
            FROM operations o, patients p 
            WHERE p.id = o.patient_id AND o.operation_type_id = '2' AND o.operation_category_id = '1' 
            AND (p.name LIKE '" . $requestData['search']['value'] . "%' 
            OR o.invoice_number LIKE '" . $requestData['search']['value'] . "%' 
            OR o.id LIKE '" . $requestData['search']['value'] . "%')";
    $query = mysqli_query($conn, $sql) or die("Error in the database query: " . mysqli_error($conn));
    $totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result without limit in the query

    $sql .= " ORDER BY o.created_at DESC, " . $columns[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . " 
              LIMIT " . $requestData['start'] . " ," . $requestData['length'];
    $query = mysqli_query($conn, $sql) or die("Error in the database query: " . mysqli_error($conn)); // again run query with limit

} else {

    $sql = "SELECT o.status_id, o.description, o.bank, o.invoice_number, o.id, o.total, o.created_at, o.is_invoice, p.name, 
            CONCAT(ELT(WEEKDAY(o.created_at) + 1, 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo')) AS day_name, 
            DATE_FORMAT(o.created_at, '%d/%m/%Y') as date 
            FROM operations o, patients p 
            WHERE p.id = o.patient_id AND o.operation_type_id = '2' AND o.operation_category_id = '1' 
            ORDER BY o.created_at DESC, " . $columns[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . " 
            LIMIT " . $requestData['start'] . " ," . $requestData['length'];
    $query = mysqli_query($conn, $sql) or die("Error in the database query: " . mysqli_error($conn));
}

$data = array();
while ($row = mysqli_fetch_array($query)) {
    $nestedData = array();

    $totalColor = ($row["total"] == 0) ? "red" : "";

    $class = ($row["status_id"] == 1) ? "success" : "danger";

    if ($userType == "su") {
        $nestedData[] = '<td><a href="index.php?view=reports/sale-ticket-word&id=' . $row["id"] . '" class="btn btn-xs btn-success"><i class="fas fa-print"></i></a></td>';
        $nestedData[] = '<td><a href="index.php?action=sales/delete&id=' . $row["id"] . '" class="btn btn-xs btn-danger" onClick="return confirmDelete()"><i class="glyphicon glyphicon-trash"></i></a></td>';
        $nestedData[] = '<td><a href="index.php?view=sales/edit&id=' . $row["id"] . '" class="btn btn-xs btn-warning"><i class="glyphicon glyphicon-pencil"></i></a></td>';
    } else {
        $nestedData[] = '<td></td>';
        $nestedData[] = '<td></td>';
        $nestedData[] = '<td><a href="index.php?view=sales/edit&id=' . $row["id"] . '" class="btn btn-xs btn-warning"><i class="glyphicon glyphicon-pencil"></i></a></td>';
    }

    $nestedData[] = '<td>' . $row["id"] . '</td>';
    $nestedData[] = '<td>' . $row["day_name"] . '</td>';
    $nestedData[] = '<td>' . $row["date"] . '</td>';
    $nestedData[] = '<td>' . $row["name"] . '</td>';
    $nestedData[] = '<td><span style="background-color:' . $totalColor . '">$' . number_format($row["total"], 2) . '</span></td>';
    $nestedData[] = '<td>' . $row["description"] . '</td>';

    $totalPayment = OperationPaymentData::getTotalByOperationId($row["id"]);
    $nestedData[] = '<td>$' . number_format($totalPayment->total ?? 0.00, 2) . '</td>';

    if ($row["is_invoice"] == 0) {
        $nestedData[] = '<td>
    <button type="button" class="btn btn-xs btn-success" 
        onclick="obtenerDatosFactura(' . $row["id"] . ', \'' . htmlspecialchars($row["description"], ENT_QUOTES) . '\', \'' . htmlspecialchars($row["bank"], ENT_QUOTES) . '\', \'' . htmlspecialchars($row["invoice_number"], ENT_QUOTES) . '\', \'' . htmlspecialchars($row["name"], ENT_QUOTES) . '\', \'' . number_format($totalPayment->total ?? 0.00, 2, ".", ",") . '\', \'' . $row["created_at"] . '\')">
        Facturar
    </button>
</td>';
        $nestedData[] = '<td></td>';
    } else {
        $nestedData[] = '<td><label>No aplica</label></td>';
    }

    $nestedData[] = "<td><label></label></td>";

    $nestedData[] = ($row["status_id"] == 1) ? '<td><b class="success">PAGADA</b></td>' : '<td><b>PENDIENTE</b></td>';

    $data[] = $nestedData;
}

$json_data = array(
    "draw"            => intval($requestData['draw']),   // for every request/draw by clientside, they send a number as a parameter, when they receive a response/data they first check the draw number, so we are sending the same number in draw.
    "recordsTotal"    => intval($totalData),  // total number of records
    "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
    "data"            => $data   // total data array
);

echo json_encode($json_data);  // send data as json format
?>
