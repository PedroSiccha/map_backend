<?php
// Configuración de la conexión a la base de datos
$host = "localhost";
$dbname = "map_db";
$username = "root";
$password = "1234";

try {
    // Crear una nueva conexión PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    
    // Establecer el modo de error de PDO a excepción
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtener el ID del usuario del frontend o de la sesión
    $user_id = $_GET['user_id']; // Asegúrate de validar y limpiar este valor para prevenir inyección de SQL

    // Preparar la consulta SQL para obtener los pedidos y su detalle completo
    $stmt = $pdo->prepare("SELECT o.id AS order_id, o.order_date, o.status, o.total_amount,
        od.quantity, p.name AS product_name, p.description AS product_description,
        u.username, c.name AS client_name, c.phone AS client_phone, c.email AS client_email, c.num_doc AS client_doc
        FROM orders o
        INNER JOIN order_details od ON o.id = od.order_id
        INNER JOIN products p ON od.product_id = p.id
        INNER JOIN users u ON o.user_id = u.id
        INNER JOIN clients c ON o.client_id = c.id
        WHERE o.user_id = :user_id");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    
    // Ejecutar la consulta
    $stmt->execute();

    // Inicializar un array para almacenar los resultados agrupados por ID de pedido
    $response = array();

    // Recorrer los resultados y estructurar la respuesta
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Extraer el ID del pedido
        $order_id = $row['order_id'];

        // Si el ID del pedido no existe en el array de respuesta, inicializar un array vacío para ese ID
        if (!isset($response[$order_id])) {
            $response[$order_id] = array(
                "order_id" => $order_id,
                "order_date" => $row['order_date'],
                "status" => $row['status'],
                "total_amount" => $row['total_amount'],
                "username" => $row['username'],
                "client_name" => $row['client_name'],
                "client_phone" => $row['client_phone'],
                "client_email" => $row['client_email'],
                "client_doc" => $row['client_doc'],
                "items" => array()
            );
        }

        // Agregar los detalles del producto al array de detalles para este pedido
        $response[$order_id]["items"][] = array(
            "quantity" => $row['quantity'],
            "product_name" => $row['product_name'],
            "product_description" => $row['product_description']
        );
    }

    // Convertir el array asociativo en un array numérico para que la respuesta sea un array de objetos
    $response = array_values($response);

    // Mostrar los resultados en formato JSON
    echo json_encode($response);
    
} catch(PDOException $e) {
    // Manejar la excepción
    echo "Error: " . $e->getMessage();
}
?>