<?php
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  require '../../Components/ConnectDB.php';
  function showProductType() {
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT * FROM PRODUCT_TYPE");
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
  }

  function showProductSplitPage($type, $offset, $limit, $status) {
    global $connectDB;
    if ($type == '') {
      $stmt = $connectDB->prepare(
        "SELECT *
        FROM PRODUCT p 
        JOIN PRODUCT_TYPE pt ON p.TypeID = pt.TypeID 
        WHERE p.Status = ? 
        ORDER BY p.ProID 
        LIMIT ?, ?");
      $stmt->bind_param("sii", $status, $offset, $limit);
    } else {
      $stmt = $connectDB->prepare(
        "SELECT *
        FROM PRODUCT p 
        JOIN PRODUCT_TYPE pt ON p.TypeID = pt.TypeID 
        WHERE pt.TypeName = ? AND p.Status = ? 
        ORDER BY p.ProID 
        LIMIT ?, ?");
      $stmt->bind_param("ssii", $type, $status, $offset, $limit);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
  }

  function countProduct($type, $status) {
    global $connectDB;
    if ($type == "") {
      $stmt = $connectDB->prepare(
        "SELECT COUNT(p.ProID) AS total
        FROM PRODUCT p WHERE p.Status = ?");
      $stmt->bind_param("s", $status);
      $stmt->execute();
      $result = $stmt->get_result();
      $result = $result->fetch_assoc()['total'];
      $stmt->close();
      return $result;
    } else {
      $stmt = $connectDB->prepare(
        "SELECT COUNT(p.ProID) AS total
        FROM PRODUCT p JOIN PRODUCT_TYPE pt ON p.TypeID = pt.TypeID 
        WHERE pt.TypeName = ? AND p.Status = ?");
      $stmt->bind_param("ss", $type, $status);
      $stmt->execute();
      $result = $stmt->get_result();
      $result = $result->fetch_assoc()['total'];
      $stmt->close();
      return $result;
    }
  }

  function searchProduct($search, $status) {
    global $connectDB;
    $stmt = $connectDB->prepare(
      "SELECT * 
      FROM PRODUCT p 
      JOIN PRODUCT_TYPE pt ON p.TypeID = pt.TypeID 
      WHERE p.ProName LIKE ? AND p.Status = ? 
      ORDER BY p.ProID");
    $search = "%$search%";
    $stmt->bind_param("ss", $search, $status);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->num_rows;
    if ($count == 0) {
      $result = searchByAuthor($search, $status);
    }
    $stmt->close();
    return $result;
  }

  function searchByAuthor($search, $status) {
    global $connectDB;
    $stmt = $connectDB->prepare(
      "SELECT * 
      FROM PRODUCT p 
      JOIN PRODUCT_TYPE pt ON p.TypeID = pt.TypeID 
      WHERE p.Author LIKE ? AND p.Status = ? 
      ORDER BY p.ProID");
    $search = "%$search%";
    $stmt->bind_param("ss", $search, $status);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
  }
?>