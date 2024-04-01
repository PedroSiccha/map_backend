<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "1234";
$database = "map_db";

$conn = new mysqli($servername, $username, $password, $database);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Función para buscar cliente por número de documento
function buscarCliente($documento) {
    global $conn;
    
    $sql = "SELECT * FROM clients WHERE num_doc = '$documento'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $cliente = $result->fetch_assoc();
        return $cliente;
    } else {
        return null;
    }
}

// Manejo de la solicitud
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Obtener el número de documento del cliente desde la solicitud GET
    $documento = $_GET['documento'];

    // Buscar cliente por número de documento
    $cliente = buscarCliente($documento);

    // Devolver respuesta JSON
    header('Content-Type: application/json');
    echo json_encode($cliente);
} else {
    // Método de solicitud no admitido
    http_response_code(405);
    echo json_encode(array("message" => "Método no permitido"));
}

// Cerrar conexión a la base de datos
$conn->close();
?>
