<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
  require_once "../../Components/ConnectDB.php";

  function selectProduct($name) {
    global $connectDB;
    $qty = 0;
    $stmt = $connectDB->prepare("SELECT * FROM product p, product_type pt WHERE p.Proname = ? AND p.StockQty != ?");
    $stmt->bind_param("si", $name, $qty);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
  }

  function selectProductByID($id) {
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT * FROM product p, product_type pt WHERE p.ProID = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
  }

  function selectShowProduct($limit) {
    global $connectDB;
    $qty = 0;
    $stmt = $connectDB->prepare("SELECT p.ProID, p.ProName, p.Description, p.PricePerUnit, p.ImageSource, pt.TypeName FROM product p JOIN product_type pt ON p.TypeID = pt.TypeID WHERE p.StockQty != ? ORDER BY RAND() LIMIT ?");
    $stmt->bind_param("ii", $qty, $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
  }

  function countProduct($type) {
    global $connectDB;
    $qty = 0;
    $stmt = $connectDB->prepare(
      "SELECT COUNT(p.ProID) AS total
      FROM PRODUCT p JOIN PRODUCT_TYPE pt ON p.TypeID = pt.TypeID 
      WHERE pt.TypeName = ? AND p.StockQty != ?");
    $stmt->bind_param("si", $type, $qty);
    $stmt->execute();
    $result = $stmt->get_result();
    $result = $result->fetch_assoc()['total'];
    $stmt->close();
    return $result;
  }

  function showProductSplitPage($type, $offset, $limit) {
    global $connectDB;
    $qty = 0;
    $stmt = $connectDB->prepare(
      "SELECT p.ProID, p.ProName, p.Author, p.PricePerUnit, p.ImageSource 
      FROM PRODUCT p 
      JOIN PRODUCT_TYPE pt ON p.TypeID = pt.TypeID 
      WHERE pt.TypeName = ? AND p.StockQty != ?
      ORDER BY p.ProID 
      LIMIT ?, ?");
    $stmt->bind_param("siii", $type, $qty, $offset, $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
  }

  function searchProduct($search) {
    global $connectDB;
    $status = "Active";
    $qty = 0;
    $stmt = $connectDB->prepare(
      "SELECT p.ProID, p.ProName, p.Author, p.PricePerUnit, p.ImageSource 
      FROM PRODUCT p 
      JOIN PRODUCT_TYPE pt ON p.TypeID = pt.TypeID 
      WHERE p.ProName LIKE ? AND p.StockQty != ?
      ORDER BY p.ProID");
    $search = "%$search%";
    $stmt->bind_param("si", $search, $qty);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->num_rows;
    if ($count == 0) {
      $result = searchByAuthor($search);
    }
    $stmt->close();
    return $result;
  }

  function searchByAuthor($search) {
    global $connectDB;
    $qty = 0;
    $stmt = $connectDB->prepare(
      "SELECT p.ProID, p.ProName, p.Author, p.PricePerUnit, p.ImageSource 
      FROM PRODUCT p 
      JOIN PRODUCT_TYPE pt ON p.TypeID = pt.TypeID 
      WHERE p.Author LIKE ? AND p.StockQty != ?
      ORDER BY p.ProID");
    $search = "%$search%";
    $stmt->bind_param("si", $search, $qty);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->num_rows;
    if ($count == 0) {
      $result = null;
    }
    $stmt->close();
    return $result;
  }
?>