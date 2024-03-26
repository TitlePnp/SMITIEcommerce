<?php
session_start();
require '../../Backend/Authorized/SuperAdminAuthorized.php';
require '../../Backend/Authorized/ManageHeader.php';
require '../../vendor/autoload.php';
use Firebase\JWT\Key;
use \Firebase\JWT\JWT;
?>

<!DOCTYPE html>
<html>

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kodchasan:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">

    <title>SMITI SHOP: HOME</title>

    <style>
        * {
            font-family: Kodchasan;
        }
    </style>

<body>
    <div class="flex flex-col justify-center items-center h-screen">
        <h1 class="text-3xl font-bold">This page is under maintenance!!🔨</h1>
        <?php
        $key = "SECRETKEY_SMITIECOM_CLIENT";
        if (isset($_SESSION['tokenJWT'])) {
            echo "Token is set by JWT";
            $jwt = $_SESSION['tokenJWT'];
            $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
            echo "<br>";
            echo "Username: " . $decoded->user;
            echo "<br>";
            echo "Role: " . $decoded->role;
        }
        else if (isset($_SESSION['tokenGoogle'])) {
            echo "Token is set by Google";
            echo "<br>";
            echo "Username: " . $_SESSION['tokenGoogle'];
            echo "<br>";
            echo "Name: " . $_SESSION['name'];
        }
        else {
            echo "Token is not set";
        }
        ?>
    </div>
</body>

</html>