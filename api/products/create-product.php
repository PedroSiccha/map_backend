<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents("php://input"));

    if (isset($data->name) && isset($data->description) && isset($data->price) && isset($data->stock) && isset($data->category_id)) {
        $db_host = 'localhost';
        $db_username = 'root';
        $db_password = '1234';
        $db_name = 'map_db';
        $conn = new mysqli($db_host, $db_username, $db_password, $db_name);
        
        if ($conn->connect_error) {
            die("Error de conexión a la base de datos: " . $conn->connect_error);
        }
        
        $stmt = $conn->prepare("INSERT INTO products (name, description, price, stock, category_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdii", $data->name, $data->description, $data->price, $data->stock, $data->category_id);
        
        if ($stmt->execute()) {
            http_response_code(201);
            echo json_encode(array("message" => "Producto creado correctamente."));
        } else {
            http_response_code(500);
            echo json_encode(array("message" => "No se pudo crear el producto."));
        }
        
        $conn->close();
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Falta uno o más campos obligatorios."));
    }
} else {
    http_response_code(405);
    echo json_encode(array("message" => "Método no permitido."));
}
?>
