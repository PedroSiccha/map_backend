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

    // Preparar la consulta SQL para obtener todas las unidades
    $stmt = $pdo->prepare("SELECT * FROM units");
    
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
