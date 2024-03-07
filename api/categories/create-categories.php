<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents("php://input"));

    if (isset($data->name) && !empty($data->name)) {
        $db_host = 'localhost';
        $db_username = 'root';
        $db_password = '1234';
        $db_name = 'map_db';
        $conn = new mysqli($db_host, $db_username, $db_password, $db_name);
        
        if ($conn->connect_error) {
            die("Error de conexión a la base de datos: " . $conn->connect_error);
        }
        
        $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->bind_param("s", $data->name);
        
        if ($stmt->execute()) {
            http_response_code(201);
            echo json_encode(array("message" => "Categoría creada correctamente."));
        } else {
            http_response_code(500);
            echo json_encode(array("message" => "No se pudo crear la categoría."));
        }
        
        $conn->close();
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Nombre de categoría no proporcionado."));
    }
} else {
    http_response_code(405);
    echo json_encode(array("message" => "Método no permitido."));
}
?>
