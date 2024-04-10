<?php
$host = "localhost";
$dbname = "map_db";
$username = "root";
$password = "1234";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $product_id = $_GET['product_id'];

    $stmt = $pdo->prepare("SELECT u.* FROM units u INNER JOIN product_units pu ON u.id = pu.unit_id WHERE pu.product_id = :product_id");
    $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
    
    $stmt->execute();

    $units = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($units);
    
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
