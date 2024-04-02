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

    // Obtener el ID del producto del frontend
    $product_id = $_GET['product_id']; // Asegúrate de validar y limpiar este valor para prevenir inyección de SQL

    // Preparar la consulta SQL para obtener las unidades del producto
    $stmt = $pdo->prepare("SELECT u.* FROM units u INNER JOIN product_units pu ON u.id = pu.unit_id WHERE pu.product_id = :product_id");
    $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
    
    // Ejecutar la consulta
    $stmt->execute();

    // Obtener los resultados como un array asociativo
    $units = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Mostrar los resultados
    echo json_encode($units);
    
} catch(PDOException $e) {
    // Manejar la excepción
    echo "Error: " . $e->getMessage();
}
?>
