<?php
// 1. Conexión a la base de datos
$servername = "localhost"; // Cambia esto si tu servidor de MySQL está en otro lugar
$username = "root";
$password = "1234";
$database = "map_db";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Validación de datos recibidos
$data = json_decode(file_get_contents("php://input"));

if(isset($data->user_id, $data->client_id, $data->total_amount, $data->order_details)) {
    $user_id = $data->user_id;
    $client_id = $data->client_id;
    $total_amount = $data->total_amount;
    $order_details = $data->order_details;

    // Continúa con el proceso de inserción de la orden...
} else {
    // Si falta alguno de los datos necesarios, responde con un error
    echo "Error: Faltan datos necesarios para generar la orden.";
}


// 2. Validación de datos recibidos
$order_date = date('Y-m-d H:i:s'); // Fecha y hora actual

// Los detalles de la orden deben ser proporcionados desde el frontend en un formato adecuado

// 3. Insertar datos en la tabla de órdenes (orders)
$status = 'pending'; // Estado inicial de la orden

$sql_insert_order = "INSERT INTO orders (user_id, order_date, status, total_amount, client_id) 
                    VALUES ('$user_id', '$order_date', '$status', '$total_amount', '$client_id')";

if ($conn->query($sql_insert_order) === TRUE) {
    // 4. Obtener el ID de la orden recién creada
    $order_id = $conn->insert_id;

    // 5. Insertar detalles de la orden en la tabla de detalles de órdenes (order_details)
    foreach ($order_details as $detail) {
        $product_id = $detail->product_id;
        $quantity = $detail->quantity;
        $price = $detail->price;

        $sql_insert_detail = "INSERT INTO order_details (order_id, product_id, quantity, price) 
                              VALUES ('$order_id', '$product_id', '$quantity', '$price')";

        $conn->query($sql_insert_detail);
    }

    $response = array(
        "success" => true,
        "message" => "Orden generada con éxito.",
        "order_id" => $order_id
    );

    echo json_encode($response);


} else {
    echo json_encode(array("success" => false, "message" => "Error al generar la orden: " . $conn->error));
}

// Cerrar conexión
$conn->close();
?>
