<?php
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  require '../../../Components/ConnectDB.php';
  if ($_POST['action'] == 'search') {
    $search = $_POST['search'];
    $id = $_POST['id'];
    searchProName($search, $id);
  } else if ($_POST['action'] == 'add') {
    $proName = $_POST['proName'];
    $author = $_POST['author'];
    $des = $_POST['des'];
    $price = $_POST['price'];
    $cost = $_POST['cost'];
    $stock = $_POST['stock'];
    $image = $_POST['image'];
    $type = $_POST['type'];
    $typeID = findTypeID($type);
    addProduct($proName, $author, $des, $price, $cost, $stock, $image, $typeID);
  } else if ($_POST['action'] == 'update') {
    $proName = $_POST['proName'];
    $author = $_POST['author'];
    $des = $_POST['des'];
    $price = $_POST['price'];
    $cost = $_POST['cost'];
    $stock = $_POST['stock'];
    $image = $_POST['image'];
    $status = $_POST['status'];
    $id = $_POST['id'];
    updateProduct($proName, $author, $des, $price, $cost, $stock, $image, $status, $id);
  }

  function searchProName($search, $id) {
    global $connectDB;
    $stmt = $connectDB->prepare(
      "SELECT * 
      FROM PRODUCT p 
      JOIN PRODUCT_TYPE pt ON p.TypeID = pt.TypeID 
      WHERE p.ProName = ? AND p.ProID != ?
      ORDER BY p.ProID");
    $stmt->bind_param("si", $search, $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    if ($row) {
      echo "have product";
      exit();
    }
    $stmt->close();
  }

  function addProduct($proName, $author, $des, $price, $cost, $stock, $image, $type) {
    global $connectDB;
    $currentDateTime = date('Y-m-d H:i:s');
    $stmt = $connectDB->prepare(
      "INSERT INTO PRODUCT (ProName, Author, Description, PricePerUnit, CostPerUnit, StockQTY, ImageSource, TypeID, First_Day) 
      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssisss", $proName, $author, $des, $price, $cost, $stock, $image, $type, $currentDateTime);
    $stmt->execute();
    $stmt->close();
  }

  function updateProduct($proName, $author, $des, $price, $cost, $stock, $image, $status, $id) {
    global $connectDB;
    $currentDateTime = date('Y-m-d H:i:s');
    if ($status != "Active") {
      $stock = 0;
    } 
    $stmt = $connectDB->prepare(
      "UPDATE PRODUCT 
      SET ProName = ?, Author = ?, Description = ?, PricePerUnit = ?, CostPerUnit = ?, StockQTY = ?, ImageSource = ?, Status = ?, Update_Day = ?
      WHERE ProID = ?");
    $stmt->bind_param("sssssisssi", $proName, $author, $des, $price, $cost, $stock, $image, $status, $currentDateTime, $id);
    $stmt->execute();
    $stmt->close();
  }

  function findTypeID($type) {
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT pt.TypeID FROM PRODUCT_TYPE pt WHERE pt.TypeName = ?");
    $stmt->bind_param("s", $type);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return $row['TypeID'];
  }
?>