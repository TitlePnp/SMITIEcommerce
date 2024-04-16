<?php
// session_start();
require_once "../../Components/ConnectDB.php";
require_once '../../vendor/autoload.php';
require_once "../UserManage/UserInfo.php";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../Components', 'config.env');
$dotenv->load();

$key = $_ENV['JWT_KEY'];

use Firebase\JWT\Key;
use \Firebase\JWT\JWT;

if (isset($_SESSION["tokenJWT"])) {
    $jwt = $_SESSION["tokenJWT"];
    $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
    $CusID = $decoded->cusid;
    $userInfo = getUserInfoFromCusID($CusID);
} else if (isset($_SESSION["tokenGoogle"])) {
    $userInfo = getGoogleUserInfo($_SESSION["tokenGoogle"]);
    $CusID = $userInfo['CusID'];
}

$userIP = get_client_ip();

function insertLog($Action, $Period)
{
    global $connectDB;
    global $CusID;
    $userIP = get_client_ip();
    if ($userIP == "::1") {
        $userIP = "127.0.0.1";
    }
    $NumID = findNumIDLog($CusID);
    $stmt = $connectDB->prepare("INSERT INTO ACCESS_LOG(CusID, IPAddr, NumID, Action, Period) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isiss", $CusID, $userIP, $NumID, $Action, $Period);
    $stmt->execute();
    $stmt->close();
}

function findNumIDLog($CusID)
{
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT NumID FROM ACCESS_LOG WHERE CusID = ? ORDER BY NumID DESC LIMIT 1");
    $stmt->bind_param("i", $CusID);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $numID = $row['NumID'];
        $numID++;
        return $numID;
    } else {
        return 1;
    }
}

function get_client_ip() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if(isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}
