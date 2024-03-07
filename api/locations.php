<?php
$db_host = 'localhost';
$db_username = 'root';
$db_password = '1234';
$db_name = 'map_db';
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);
if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}
$query = "SELECT * FROM locations";
$result = $conn->query($query);
if ($result->num_rows > 0) {
    $locations = array();
    while ($row = $result->fetch_assoc()) {
        $location = array(
            "id" => $row['id'],
            "localname" => $row['localname'],
            "latitude" => $row['latitude'],
            "longitude" => $row['longitude'],
            "description" => $row['description'],
            "email" => $row['email'],
            "phone" => $row['phone'],
            "category" => $row['category'],
            "created_at" => $row['created_at']
        );
        $locations[] = $location;
    }
    echo json_encode($locations);
} else {
    echo json_encode(array("message" => "No se encontraron ubicaciones."));
}
$conn->close();

?>
