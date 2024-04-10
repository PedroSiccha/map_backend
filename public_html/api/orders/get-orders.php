<?php
$servername = "localhost";
$username = "root";
$password = "1234";
$database = "map_db";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$sql = "SELECT o.id, o.order_date, o.status, o.total_amount, c.name 
        FROM orders o
        INNER JOIN clients c ON o.client_id = c.id
        ORDER BY o.order_date DESC";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $orders = array();

    while($row = $result->fetch_assoc()) {
        $order = array(
            "id" => $row["id"],
            "order_date" => $row["order_date"],
            "status" => $row["status"],
            "total_amount" => $row["total_amount"],
            "name" => $row["name"],
            "details" => array()
        );

        $details_sql = "SELECT product_id, quantity, price 
                        FROM order_details 
                        WHERE order_id = " . $row["id"];

        $details_result = $conn->query($details_sql);

        while($detail_row = $details_result->fetch_assoc()) {
            $order["details"][] = array(
                "product_id" => $detail_row["product_id"],
                "quantity" => $detail_row["quantity"],
                "price" => $detail_row["price"]
            );
        }

        $orders[] = $order;
    }

    echo json_encode($orders);
} else {
    echo "No se encontraron pedidos.";
}

$conn->close();
?>
