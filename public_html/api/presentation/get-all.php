<?php
$host = "localhost";
$dbname = "map_db";
$username = "root";
$password = "1234";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("SELECT * FROM units");
    
    $stmt->execute();

    $units = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($units);
    
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
