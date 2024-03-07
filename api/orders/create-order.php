<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents("php://input"));

    if (isset($data->user_id) && isset($data->total_amount) && isset($data->products)) {
        $db_host = 'localhost';
        $db_username = 'root';
        $db_password = '1234';
        $db_name = 'map_db';
        $conn = new mysqli($db_host, $db_username, $db_password, $db_name);
        
        if ($conn->connect_error) {
            die("Error de conexión a la base de datos: " . $conn->connect_error);
        }
        
        $stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount) VALUES (?, ?)");
        $stmt->bind_param("id", $data->user_id, $data->total_amount);
        
        if ($stmt->execute()) {
            $order_id = $conn->insert_id;

            foreach ($data->products as $product) {
                $stmt = $conn->prepare("INSERT INTO order_details (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("iiid", $order_id, $product->id, $product->quantity, $product->price);
                $stmt->execute();
            }

            http_response_code(201);
            echo json_encode(array("message" => "Orden creada correctamente."));
        } else {
            http_response_code(500);
            echo json_encode(array("message" => "No se pudo crear la orden."));
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
