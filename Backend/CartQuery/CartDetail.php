<?php
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  require '../../Components/ConnectDB.php';
  $_SESSION['productOnCart'] = 0;
  function showCartSession($proID) {
    global $connectDB;
    $stmt = $connectDB->prepare(
      "SELECT p.ProName, p.Author, p.Description, p.PricePerUnit, p.StockQty, p.ImageSource, pt.TypeName  
      FROM PRODUCT p
      JOIN PRODUCT_TYPE pt ON p.TypeID = pt.TypeID
      WHERE p.ProID = ?");
    $stmt->bind_param("i", $proID);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
  }

  function showCartDB($id) {
    global $connectDB;
    $statusDone = 'Ordered';
    $statusCancel = 'Cancel';
    $stmt = $connectDB->prepare(
      "SELECT cl.ProID, cl.Qty, p.ProName, p.Author, p.Description, p.PricePerUnit, p.StockQty, p.ImageSource, pt.TypeName
      FROM CART_LIST cl
      JOIN PRODUCT p ON cl.ProID = p.ProID
      JOIN PRODUCT_TYPE pt ON p.TypeID = pt.TypeID
      WHERE cl.CusID = ? AND cl.Status != ? AND cl.Status != ?");
    $stmt->bind_param("iss", $id, $statusDone, $statusCancel);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
  }

  function getQtyFromCart($CustomerID, $productID) {
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT Qty FROM CART_LIST WHERE ProID = ? AND CusID = ?");
    $stmt->bind_param("is", $productID, $CustomerID);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
  }
?>