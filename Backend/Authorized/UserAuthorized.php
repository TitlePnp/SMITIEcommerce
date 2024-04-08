<?php
session_start();
require '../../Backend/Authorized/GoogleIdRole.php';
require '../../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../Components', 'config.env');
$dotenv->load();

use Firebase\JWT\Key;
use \Firebase\JWT\jwt;

$key = $_ENV['JWT_KEY'];

if (isset($_SESSION['tokenJWT'])) {
  $jwt = $_SESSION['tokenJWT'];
  $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
  $role = $decoded->role;
  if ($role == "Admin") {
    header('Location: ../../Frontend/Admin/DashBoard.php');
    exit();
  } elseif ($role == "SuperAdmin") {
    header('Location: ../../Frontend/SuperAdmin/SuperAdminDashBoard.php');
    exit();
  }
} elseif (isset($_SESSION['tokenGoogle'])) {  
  $role = getRole($_SESSION['tokenGoogle']);
  if ($role == "Admin") {
    header('Location: ../../Frontend/Admin/DashBoard.php');
    exit();
  } elseif ($role == "SuperAdmin") {
    header('Location: ../../Frontend/SuperAdmin/SuperAdminDashBoard.php');
    exit();
  }
}
