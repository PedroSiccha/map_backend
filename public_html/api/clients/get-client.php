<?php
$servername = "localhost";
$username = "root";
$password = "1234";
$database = "map_db";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

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

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $documento = $_GET['documento'];

    $cliente = buscarCliente($documento);

    header('Content-Type: application/json');
    echo json_encode($cliente);
} else {
    http_response_code(405);
    echo json_encode(array("message" => "Método no permitido"));
}

$conn->close();
?>
