<?php
session_start();
require_once "../../Components/ConnectDB.php";
require_once '../../vendor/autoload.php';
require_once "../UserManage/UserInfo.php";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../Components', 'config.env');
$dotenv->load();

$key = $_ENV['JWT_KEY'];

use Firebase\JWT\Key;
use \Firebase\JWT\JWT;

// var_dump($_SESSION["tokenGoogle"]);

if (isset($_SESSION["tokenJWT"])) {
    $jwt = $_SESSION["tokenJWT"];
    $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
    $CusID = $decoded->cusid;
    $userInfo = getUserInfoFromCusID($CusID);
    $isMember = true;
    var_dump($CusID);
} else if (isset($_SESSION["tokenGoogle"])) {
    $userInfo = getGoogleUserInfo($_SESSION["tokenGoogle"]);
    $CusID = $userInfo['CusID'];
    $isMember = true;
    var_dump($CusID);
}

var_dump(findNumIDLog($CusID));


function insertLog($Action, $Period)
{
    global $connectDB;
    global $CusID;
    $NumID = findNumIDLog($CusID);
    $stmt = $connectDB->prepare("INSERT INTO access_log(CusID, NumID, Action, Period) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $CusID, $NumID, $Action, $Period);
}

function findNumIDLog($CusID)
{
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT NumID FROM access_log WHERE CusID = ? ORDER BY NumID DESC LIMIT 1");
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
