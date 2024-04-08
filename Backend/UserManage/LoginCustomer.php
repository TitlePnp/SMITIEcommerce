<?php
require "../../Components/connectDB.php";
require '../../vendor/autoload.php';
require '../../Backend/Profile/GetInfo.php';
require_once "../Log/LogManage.php";

date_default_timezone_set('Asia/Bangkok');

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../Components', 'config.env');
$dotenv->load();

use Firebase\JWT\Key;
use \Firebase\JWT\jwt;

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

        $key = $_ENV['JWT_KEY'];
        $payload = array(
            "cusid" => $CusID,
            "user" => $username,
            "role" => $row['Role'],
            "iat" => time(),
            "exp" => time() + (60 * 240)
        );

        $jwt = JWT::encode($payload, $key, 'HS256');

        $_SESSION['tokenJWT'] = $jwt;
        $noData = getInfo($CusID);
        while ($row = $noData->fetch_assoc()) {
            if ($row['CusFName'] == null || $row['CusLName'] == null) {
                insertLog("Login with JWT", date("Y-m-d H:i:s"));;
                header('Location: ../../Frontend/Profile/Information.php');
                exit();
            } else {
                insertLog("Login with JWT", date("Y-m-d H:i:s"));;
                header('Location: ../../Frontend/MainPage/Home.php'); 
                exit(); 
            }
        }
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
