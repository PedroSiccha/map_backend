<?php
// Configuración de la conexión a la base de datos
$servername = "localhost"; // Cambia esto si tu servidor de MySQL está en otro lugar
$username = "root";
$password = "1234";
$database = "map_db";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $database);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener el nombre del producto enviado desde el frontend
$product_name = $_GET['nombre']; // Asegúrate de validar y limpiar este valor para prevenir inyección de SQL

// Consulta SQL para obtener los productos
$sql = "SELECT * FROM products WHERE name LIKE '%$product_name%'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Crear un array para almacenar los resultados
    $products_array = array();

    // Iterar sobre los resultados y almacenarlos en el array
    while($row = $result->fetch_assoc()) {
        $products_array[] = $row;
    }

    // Convertir el array a formato JSON y mostrarlo
    echo json_encode($products_array);
} else {
    // Si no se encontraron productos, enviar un mensaje de error
    echo "No se encontraron productos con ese nombre.";
}

// Cerrar conexión
$conn->close();
?>
