<?php
$login_api_url = 'http://localhost/proyecto/api/login.php';
$login_data = array(
    'username' => 'usuario',
    'password' => 'contraseña'
);

$login_response = json_decode(file_get_contents($login_api_url, false, stream_context_create(array(
    'http' => array(
        'method' => 'POST',
        'header' => 'Content-Type: application/json',
        'content' => json_encode($login_data)
    )
))));

if(isset($login_response->token)) {
    $token = $login_response->token;
    $locations_api_url = 'http://localhost/proyecto/api/locations.php';

    $locations_response = json_decode(file_get_contents($locations_api_url, false, stream_context_create(array(
        'http' => array(
            'method' => 'GET',
            'header' => 'Authorization: Bearer ' . $token
        )
    ))));
    if(isset($locations_response->message)) {
        echo $locations_response->message;
    } else {
        foreach ($locations_response as $location) {
            echo "ID: " . $location->id . ", Latitud: " . $location->latitude . ", Longitud: " . $location->longitude . ", Creado en: " . $location->created_at . "<br>";
        }
    }
} else {
    echo "Error al iniciar sesión.";
}

?>
