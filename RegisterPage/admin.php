<?php 
session_start();

require '../vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$key = "SECRETKEY_SMITIECOM_CLIENT";
$token = $_SESSION['Token'];

if (isset($_SESSION['Token'])) {
    try {
        $decoded = JWT::decode($token , new Key($key, 'HS256'));
        if ($decoded->role === "Admin") {
            echo "access";
        } else {
            header('Location: ./SuccessLogin.php');
        }
    } catch (Exception $e) {
        http_response_code(401);
        echo 'Caught exception: ',  $e->getMessage(), "\n";
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Admin</title>
</head>

<body>
    <h1>Admin</h1>
</body>

</html>