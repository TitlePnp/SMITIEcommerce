<?php
require "../Components/ConnectDB.php";
require '../vendor/autoload.php';

use Firebase\JWT\JWT;

$username = $_POST['username'];
$password = $_POST['password'];

$stmt = $connectDB->prepare("SELECT `Password`, `Role` FROM customer_account WHERE UserName = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc(); 
    $hashed_password = $row['Password']; 

    if (password_verify($password, $hashed_password)) {
        session_start();
        
        $key = "SECRETKEY_SMITIECOM_CLIENT";
        $payload = array(
            "user" => $username, 
            "role" => $row['Role'], 
            "iat" => time(),
            "exp" => time() + (60*60)
        );

        $jwt = JWT::encode($payload, $key, 'HS256');

        $_SESSION['Token'] = $jwt;
        header('Location: ../RegisterPage/SuccessLogin.php'); 
        exit(); 
    } else {
        header('Location: ./LoginCustomer.html');
        exit();
    }
} else {
    // กรณีไม่พบผู้ใช้ในฐานข้อมูล
    header('Location: ./LoginCustomer.html');
    exit();
}
?>
