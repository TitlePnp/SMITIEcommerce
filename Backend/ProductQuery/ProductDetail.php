<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require '../../Components/ConnectDB.php';
function mainProduct($proID)
{
  global $connectDB;
  $stmt = $connectDB->prepare(
    "SELECT p.ProName, p.Author, p.Description, p.PricePerUnit, p.StockQty, p.ImageSource, pt.TypeName  
      FROM PRODUCT p
      JOIN PRODUCT_TYPE pt ON p.TypeID = pt.TypeID
      WHERE p.ProID = ?"
  );
  $stmt->bind_param("i", $proID);
  $stmt->execute();
  $result = $stmt->get_result();
  $stmt->close();
  return $result;
}

function recommendProduct($proID, $type)
{
  global $connectDB;
  $stmt = $connectDB->prepare(
    "SELECT p.ProID ,p.ProName, p.Author, p.Description, p.PricePerUnit, p.StockQty, p.ImageSource, p.Status, pt.TypeName 
      FROM PRODUCT p
      JOIN PRODUCT_TYPE pt ON p.TypeID = pt.TypeID
      WHERE p.ProID != ? AND p.TypeID = pt.TypeID AND pt.TypeName = ?
      ORDER BY RAND()"
  );
  $stmt->bind_param("is", $proID, $type);
  $stmt->execute();
  $result = $stmt->get_result();
  $stmt->close();
  return $result;
}

function sumProductOnOrder($proID)
{
  global $connectDB;
  $stmt = $connectDB->prepare("SELECT SUM(Qty) FROM invoice_list il JOIN invoice_order iv ON il.InvoiceID = iv.InvoiceID WHERE il.ProID = ? AND iv.Status = 'Ordered'");
  $stmt->bind_param("i", $proID);
  $stmt->execute();
  $result = $stmt->get_result();
  $result = $result->fetch_assoc();
  $stmt->close();
  return $result['SUM(Qty)'];
}
