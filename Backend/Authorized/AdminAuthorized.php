<?php
  session_start();
  require '../../Backend/Authorized/GoogleIdRole.php';
  require '../../vendor/autoload.php';
  use Firebase\JWT\Key;
  use \Firebase\JWT\JWT;

  $key = "SECRETKEY_SMITIECOM_CLIENT";
  if (isset($_SESSION['tokenJWT'])) {
    $jwt = $_SESSION['tokenJWT'];
    $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
    $role = $decoded->role;
    if ($role == "User") {
      header('Location: ../../Frontend/MainPage/Home.php');
      exit();
    } elseif ($role == "SuperAdmin") {
      header('Location: ../../Frontend/admin/Test.php');
      exit();
    }
  } elseif (isset($_SESSION['tokenGoogle'])) {
    $role = getRole($_SESSION['tokenGoogle']);
    if ($role == "User") {
      header('Location: ../../Frontend/MainPage/Home.php');
      exit();
    } elseif ($role == "SuperAdmin") {
      header('Location: ../../Frontend/admin/Test.php');
      exit();
    }
  } else {
    header('Location: ../../Frontend/MainPage/Home.php');
  }
?>