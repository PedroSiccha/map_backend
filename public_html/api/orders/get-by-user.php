<?php
$host = "localhost";
$dbname = "map_db";
$username = "root";
$password = "1234";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $user_id = $_GET['user_id'];

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
    
    $stmt->execute();

    $response = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $order_id = $row['order_id'];

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

        $response[$order_id]["items"][] = array(
            "quantity" => $row['quantity'],
            "product_name" => $row['product_name'],
            "product_description" => $row['product_description']
        );
    }

    $response = array_values($response);
    echo json_encode($response);
    
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>