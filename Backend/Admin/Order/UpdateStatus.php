<?php
  session_start();
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  require '../../../Components/ConnectDB.php';
  $_SESSION['status'] = $_POST['status'];
  updateStatus($_POST['recID'], $_POST['status']);
  function updateStatus($recID, $status) {
    global $connectDB;
    $stmt = $connectDB->prepare("UPDATE RECEIPT SET Status = ? WHERE RecID = ?");
    $stmt->bind_param("ss", $status, $recID);
    $stmt->execute();
    $stmt->close();
  }
?>