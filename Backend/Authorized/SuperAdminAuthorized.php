<?php
  session_start();
  require '../../Backend/Authorized/GoogleIdRole.php';

  if (isset($_SESSION['tokenGoogle'])) {
    $role = getRole($_SESSION['tokenGoogle']);
    if ($role == "User") {
      header('Location: ../../Frontend/MainPage/Home.php');
      exit();
    } elseif ($role == "Admin") {
      header('Location: ../../Frontend/admin/Test.php');
      exit();
    }
  } else {
    header('Location: ../../Frontend/MainPage/Home.php');
  }
?>