<?php
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  require '../../Components/ConnectDB.php';
  $_SESSION['productOnCart'] = 0;
  function showCartSession($proID) {
    global $connectDB;
    $qty = 0;
    $stmt = $connectDB->prepare(
      "SELECT p.ProName, p.Author, p.Description, p.PricePerUnit, p.StockQty, p.ImageSource, pt.TypeName  
      FROM PRODUCT p
      JOIN PRODUCT_TYPE pt ON p.TypeID = pt.TypeID
      WHERE p.ProID = ? AND p.StockQty != 0");
    $stmt->bind_param("ii", $proID, $qty);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
  }

  function showCartDB($id) {
    global $connectDB;
    $statusDone = 'Ordered';
    $statusCancel = 'Cancel';
    $qty = 0;
    $stmt = $connectDB->prepare(
      "SELECT cl.ProID, cl.Qty, p.ProName, p.Author, p.Description, p.PricePerUnit, p.StockQty, p.ImageSource, pt.TypeName
      FROM CART_LIST cl
      JOIN PRODUCT p ON cl.ProID = p.ProID
      JOIN PRODUCT_TYPE pt ON p.TypeID = pt.TypeID
      WHERE cl.CusID = ? AND cl.Status != ? AND cl.Status != ? AND p.StockQty != ?");
    $stmt->bind_param("issi", $id, $statusDone, $statusCancel, $qty);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
  }

  function getQtyFromCart($CustomerID) {
    global $connectDB;
    //query last NumID form receiver_list where CusID = ?
    $stmt = $connectDB->prepare("SELECT NumID FROM cart_list WHERE CusID = ? ORDER BY NumID DESC LIMIT 1;");
    $stmt->bind_param("i", $CustomerID);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($NumID);
    $stmt->fetch();
    if ($NumID == null) {
      $NumID = 1;
    }     
    $stmt = $connectDB->prepare("SELECT Qty FROM CART_LIST WHERE NumID = ? AND CusID = ?");
    $stmt->bind_param("is", $NumID, $CustomerID);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
  }
?>