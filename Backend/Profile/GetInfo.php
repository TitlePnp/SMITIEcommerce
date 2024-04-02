<?php
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  require '../../Components/ConnectDB.php';
  
  function getInfo($id) {
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT * FROM CUSTOMER WHERE CusID = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
  }
?>