<?php
$servername = "localhost";
$username = "root";
$password = "1234";
$database = "map_db";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$product_name = $_GET['nombre'];

$sql = "SELECT * FROM products WHERE name LIKE '%$product_name%'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $products_array = array();
    while($row = $result->fetch_assoc()) {
        $products_array[] = $row;
    }
    echo json_encode($products_array);
} else {
    echo "No se encontraron productos con ese nombre.";
}
$conn->close();
?>
