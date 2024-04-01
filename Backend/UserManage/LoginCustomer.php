<?php
require "../../Components/connectDB.php";
require '../../vendor/autoload.php';

use Firebase\JWT\JWT;

$username = $_POST['username'];
$password = $_POST['userpassword'];

$stmt = $connectDB->prepare("SELECT `Password`, `Role`, `CusID` FROM customer_account WHERE UserName = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc(); 
    $hashed_password = $row['Password']; 
    $CusID = $row['CusID'];

    if (password_verify($password, $hashed_password)) {
        session_start();
        
        $key = "SECRETKEY_SMITIECOM_CLIENT";
        $payload = array(
            "cusid" => $CusID,
            "user" => $username, 
            "role" => $row['Role'], 
            "iat" => time(),
            "exp" => time() + (60*240)
        );

        $jwt = JWT::encode($payload, $key, 'HS256');

        $_SESSION['tokenJWT'] = $jwt;
        header('Location: ../../Frontend/MainPage/Home.php'); 
        exit(); 
    } else {
        // var_dump("Wrong password");
        header('Location: ../../Frontend/SignIn_Page/SignIn.php');
        exit();
    }
} else {
    // var_dump("Don't have this username in database");
    header('Location: ../../Frontend/SignIn_Page/SignIn.php');
    exit();
}
?>
