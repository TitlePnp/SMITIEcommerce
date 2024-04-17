<?php
  session_start();
  require '../../Backend/Authorized/GoogleIdRole.php';
  require 'CheckTimeout.php';
  require '../../vendor/autoload.php';
  $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../Components', 'config.env');
  $dotenv->load();

  use Firebase\JWT\Key;
  use \Firebase\JWT\JWT;

  $key = $_ENV['JWT_KEY'];

  if (isset($_SESSION['tokenJWT'])) {
    $jwt = $_SESSION['tokenJWT'];
    $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
    $role = $decoded->role;
    if ($role == "User") {
      header('Location: ../../Frontend/MainPage/Home.php');
      exit();
    } elseif ($role == "Admin") {
      header('Location: ../../Frontend/Admin/Dashboard.php');
      exit();
    }
  } else if (isset($_SESSION['tokenGoogle'])) {
    $role = getRole($_SESSION['tokenGoogle']);
    if ($role == "User") {
      header('Location: ../../Frontend/MainPage/Home.php');
      exit();
    } elseif ($role == "Admin") {
      header('Location: ../../Frontend/Admin/Dashboard.php');
      exit();
    }
  }
?>