<?php
require '../vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$key = "SECRETKEY_SMITIECOM_CLIENT";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['action'] === 'get_token') {
        $payload = array(
            "user" => $_POST['user'],
            "role" => "admin",
            "iat" => time(),
            "exp" => time() + (60*60) // expires in 1 hour
        );

        $jwt = JWT::encode($payload, $key, 'HS256');
        echo $jwt;
    } else if ($_POST['action'] === 'get_user' && isset($_POST['token'])) {
        try {
            $decoded = JWT::decode($_POST['token'], new Key($key, 'HS256'));
            echo json_encode($decoded);
        } catch (Exception $e) {
            http_response_code(401);
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }
}
?>