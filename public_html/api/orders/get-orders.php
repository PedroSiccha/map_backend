<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "1234";
$database = "map_db";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consulta para obtener la lista de pedidos con sus detalles
$sql = "SELECT o.id, o.order_date, o.status, o.total_amount, c.name 
        FROM orders o
        INNER JOIN clients c ON o.client_id = c.id
        ORDER BY o.order_date DESC"; // Puedes cambiar el orden según tus necesidades

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Array para almacenar los resultados
    $orders = array();

    // Recorre cada fila de resultados
    while($row = $result->fetch_assoc()) {
        // Crea un nuevo array para cada pedido
        $order = array(
            "id" => $row["id"],
            "order_date" => $row["order_date"],
            "status" => $row["status"],
            "total_amount" => $row["total_amount"],
            "name" => $row["name"],
            "details" => array()
        );

        // Consulta para obtener los detalles de cada pedido
        $details_sql = "SELECT product_id, quantity, price 
                        FROM order_details 
                        WHERE order_id = " . $row["id"];

        $details_result = $conn->query($details_sql);

        // Recorre los detalles y los agrega al array de detalles del pedido actual
        while($detail_row = $details_result->fetch_assoc()) {
            $order["details"][] = array(
                "product_id" => $detail_row["product_id"],
                "quantity" => $detail_row["quantity"],
                "price" => $detail_row["price"]
            );
        }

        // Agrega el pedido con sus detalles al array de pedidos
        $orders[] = $order;
    }

    // Convierte el array de pedidos a formato JSON y lo muestra
    echo json_encode($orders);
} else {
    echo "No se encontraron pedidos.";
}

// Cierra la conexión
$conn->close();
?>
