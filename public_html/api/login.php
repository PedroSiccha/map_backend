<?php
$db_host = 'localhost';
$db_username = 'root';
$db_password = '1234';
$db_name = 'map_db';
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);
if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}
$data = json_decode(file_get_contents("php://input"));
if(isset($data->username) && isset($data->password)) {
    $username = $data->username;
    $password = $data->password;
    $query = "SELECT id, username FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($query);
    
    if($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $secret_key = "tu_clave_secreta";
        $payload = array(
            "user_id" => $user['id'],
            "username" => $user['username']
        );
        $token = jwt_encode($payload, $secret_key);
        echo json_encode(array("token" => $token));
    } else {
        http_response_code(401);
        echo json_encode(array("message" => "Credenciales incorrectas."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Faltan datos de inicio de sesión."));
}
function jwt_encode($payload, $key) {
    $header = json_encode(array("typ" => "JWT", "alg" => "HS256"));
    $payload = json_encode($payload);
    $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
    $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
    $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $key, true);
    $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
    $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    return $jwt;
}

?>
