<?php
sessionstart();
require 'GoogleIdRole.php';
requireonce '../../Backend/UserManage/UserInfo.php';
require '../../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR . '/../../Components', 'config.env');
$dotenv->load();

use Firebase\JWT\Key;
use \Firebase\JWT\jwt;

$key = $_ENV['JWT_KEY'];

if (isset($_SESSION['tokenJWT'])) {
  $jwt = $_SESSION['tokenJWT'];
  $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
  $role = $decoded->role;
  if ($role == "Admin") {
    header('Location: ../../Frontend/admin/admin.php');
    exit();
  } elseif ($role == "SuperAdmin") {
    header('Location: ../../Frontend/admin/Test.php');
    exit();
  }
} elseif (isset($_SESSION['tokenGoogle'])) {
  $role = getRole($_SESSION['tokenGoogle']);
  if ($role == "Admin") {
    header('Location: ../../Frontend/Admin/Dashboard.php');
    exit();
  } elseif ($role == "SuperAdmin") {
    header('Location: ../../Frontend/SuperAdmin/SuperAdminDashBoard.php');
    exit();
  }
} else {
  header('Location: ../../Frontend/MainPage/Home.php');
  exit();
}

function getUserName()
{
  $user = null;
  if (isset($_SESSION['tokenJWT'])) {
    $jwt = $_SESSION['tokenJWT'];
    $key = "SECRETKEY_SMITIECOM_CLIENT";
    $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
    $user = $decoded->user;
  } elseif (isset($_SESSION['tokenGoogle'])) {
    $result = getGoogleUserInfo($_SESSION['tokenGoogle']);
    $user = $result['UserName'];
  }
  return $user;
}