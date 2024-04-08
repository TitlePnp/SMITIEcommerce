<?php
  session_start();
  require '../../Backend/Authorized/GoogleIdRole.php';

  if (isset($_SESSION['tokenGoogle'])) {
    $role = getRole($_SESSION['tokenGoogle']);
    if ($role == "User") {
      header('Location: ../../Frontend/MainPage/Home.php');
      exit();
    } elseif ($role == "SuperAdmin") {
      header('Location: ../../Frontend/SuperAdmin/SuperAdminDashBoard.php');
      exit();
    }
  } else {
    header('Location: ../../Frontend/MainPage/Home.php');
  }
?>