<?php
require_once "../../Components/ConnectDB.php";

function selectProduct($name)
{
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT * FROM product, product_type WHERE Proname = ?");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
}

function selectShowProduct()
{
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT p.ProID, p.ProName, p.Description, p.PricePerUnit, p.ImageSource, pt.TypeName FROM product p JOIN product_type pt ON p.TypeID = pt.TypeID ORDER BY RAND() LIMIT 7");
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
}

function countProduct($type) {
  global $connectDB;
  $status = "Active";
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

function showProductSplitPage($type, $offset, $limit) {
  global $connectDB;
  $status = "Active";
  $stmt = $connectDB->prepare(
    "SELECT p.ProID, p.ProName, p.Author, p.PricePerUnit, p.ImageSource 
    FROM PRODUCT p 
    JOIN PRODUCT_TYPE pt ON p.TypeID = pt.TypeID 
    WHERE pt.TypeName = ? AND p.Status = ? 
    ORDER BY p.ProID 
    LIMIT ?, ?");
  $stmt->bind_param("ssii", $type, $status, $offset, $limit);
  $stmt->execute();
  $result = $stmt->get_result();
  $stmt->close();
  return $result;
}