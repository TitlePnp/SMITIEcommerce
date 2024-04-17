<?php
  session_start();
  require '../../Backend/Authorized/GoogleIdRole.php';

  if (isset($_SESSION['tokenJWT'])) {
    $jwt = $_SESSION['tokenJWT'];
    $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
    $role = $decoded->role;
    if ($role == "User") {
      header('Location: ../../Frontend/MainPage/Home.php');
      exit();
    } elseif ($role == "SuperAdmin") {
      header('Location: ../../Frontend/SuperAdmin/SuperAdminDashboard.php');
      exit();
    }
  } else if (isset($_SESSION['tokenGoogle'])) {
    $role = getRole($_SESSION['tokenGoogle']);
    if ($role == "User") {
      header('Location: ../../Frontend/MainPage/Home.php');
      exit();
    } elseif ($role == "SuperAdmin") {
      header('Location: ../../Frontend/SuperAdmin/SuperAdminDashboard.php');
      exit();
    }
  }
?>