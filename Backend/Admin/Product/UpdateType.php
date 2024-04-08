<?php
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  require '../../../Components/ConnectDB.php';
  if ($_POST['check'] == 'both') {
    $typeName = $_POST['typeName'];
    $thaiType = $_POST['thaiType'];
    addNewType($typeName, $thaiType);
  } else if ($_POST['check'] == 'eng') {
    $typeName = $_POST['typeName'];
    $typeID = $_POST['typeID'];
    updateType($typeName, $typeID);
  } else if ($_POST['check'] == 'thai') {
    $thaiType = $_POST['thaiType'];
    $typeID = $_POST['typeID'];
    updateThaiType($thaiType, $typeID);
  } else if ($_POST['check'] == 'delete') {
    $typeID = $_POST['typeID'];
    deleteType($typeID);
  }

  function addNewType($typeName, $thaiType) {
    global $connectDB;
    $stmt = $connectDB->prepare("INSERT INTO PRODUCT_TYPE (TypeName, ThaiType) VALUES (?, ?)");
    $stmt->bind_param("ss", $typeName, $thaiType);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
  }

  function updateThaiType($thaiType, $typeID) {
    global $connectDB;
    $stmt = $connectDB->prepare("UPDATE PRODUCT_TYPE pt SET pt.ThaiType = ? WHERE pt.TypeID = ?"); 
    $stmt->bind_param("si", $thaiType, $typeID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
  }

  function updateType($typeName, $typeID) {
    global $connectDB;
    $stmt = $connectDB->prepare("UPDATE PRODUCT_TYPE pt SET pt.TypeName = ? WHERE pt.TypeID = ?"); 
    $stmt->bind_param("si", $typeName, $typeID);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
  }

  function deleteType($typeID) {
    global $connectDB;
    $stmt = $connectDB->prepare("DELETE FROM PRODUCT_TYPE WHERE TypeID = ?");
    $stmt->bind_param("i", $typeID);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
  }
?>