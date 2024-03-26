<?php
  require '../../Components/ConnectDB.php';
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  function getRole($googleId) {
    global $connectDB;
    $smtp = $connectDB->prepare("SELECT ca.role FROM CUSTOMER_ACCOUNT ca WHERE ca.GoogleId = ?");
    $smtp->bind_param("s", $googleId);
    $smtp->execute();
    $result = $smtp->get_result();
    $role = $result->fetch_assoc();
    return $role;
  }
?>