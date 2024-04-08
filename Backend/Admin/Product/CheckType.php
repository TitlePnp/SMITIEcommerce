<?php
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  require '../../../Components/ConnectDB.php';
  if ($_POST['check'] == 'both') {
    $typeName = $_POST['typeName'];
    $thaiType = $_POST['thaiType'];
    checkType($typeName, $thaiType);
  } else if ($_POST['check'] == 'eng') {
    $typeName = $_POST['typeName'];
    $typeID = $_POST['typeID'];
    checkEngType($typeName, $typeID);
  } else if ($_POST['check'] == 'thai') {
    $thaiType = $_POST['thaiType'];
    $typeID = $_POST['typeID'];
    checkThaiType($thaiType, $typeID);
  }

  function checkType($typeName, $thaiType) {
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT pt.TypeID FROM PRODUCT_TYPE pt WHERE pt.TypeName = ?");
    $stmt->bind_param("s", $typeName);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    if ($row) {
      echo "have type";
      exit();
    } else {
      $stmt = $connectDB->prepare("SELECT pt.TypeID FROM PRODUCT_TYPE pt WHERE pt.ThaiType = ?");
      $stmt->bind_param("s", $thaiType);
      $stmt->execute();
      $result = $stmt->get_result();
      $row = $result->fetch_assoc();
      if ($row) {
        echo "have type";
        exit();
      }
    }
    $stmt->close();
  }

  function checkThaiType($thaiType, $typeID) {
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT pt.TypeID 
                                  FROM PRODUCT_TYPE pt 
                                  WHERE pt.ThaiType = ? AND pt.TypeID != ?");
    $stmt->bind_param("si", $thaiType, $typeID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    if ($row) {
      echo "have type";
      exit();
    }
    $stmt->close();
  }

  function checkEngType($typeName, $typeID) {
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT pt.TypeID 
                                  FROM PRODUCT_TYPE pt 
                                  WHERE pt.TypeName = ? AND pt.TypeID != ?");
    $stmt->bind_param("si", $typeName, $typeID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    if ($row) {
      echo "have type";
      exit();
    }
    $stmt->close();
  }
?>