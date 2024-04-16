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
  $qty = 0;
  $stmt = $connectDB->prepare(
    "SELECT p.ProID ,p.ProName, p.Author, p.Description, p.PricePerUnit, p.StockQty, p.ImageSource, p.Status, pt.TypeName 
      FROM PRODUCT p
      JOIN PRODUCT_TYPE pt ON p.TypeID = pt.TypeID
      WHERE p.ProID != ? AND p.TypeID = pt.TypeID AND pt.TypeName = ? AND p.StockQty != ?
      ORDER BY RAND()"
  );
  $stmt->bind_param("isi", $proID, $type, $qty);
  $stmt->execute();
  $result = $stmt->get_result();
  $stmt->close();
  return $result;
}

function sumProductOnOrder($proID)
{
  global $connectDB;
  $stmt = $connectDB->prepare("SELECT SUM(Qty) FROM INVOICE_LIST il JOIN INVOICE_ORDER iv ON il.InvoiceID = iv.InvoiceID WHERE il.ProID = ? AND iv.Status = 'Ordered'");
  $stmt->bind_param("i", $proID);
  $stmt->execute();
  $result = $stmt->get_result();
  $result = $result->fetch_assoc();
  $stmt->close();
  return $result['SUM(Qty)'];
}

function getQtyWarningProduct() {
  //get Qty of each product orderby less qty
  global $connectDB;
  $stmt = $connectDB->prepare("SELECT ProID, ProName, StockQty FROM product WHERE StockQty < 5  ORDER BY StockQty ASC LIMIT 10");
  $stmt->execute();
  $result = $stmt->get_result();
  $stmt->close();
  return $result;
}

function getQtyPaidStatusCompareStockQty(){
  global $connectDB;
  $stmt = $connectDB->prepare("SELECT p.ProID, p.ProName, p.StockQty, r.InvoiceID, SUM(il.Qty) as Qty 
  FROM product p 
  JOIN invoice_list il ON p.ProID = il.ProID 
  JOIN receipt r ON r.InvoiceID = il.InvoiceID 
  WHERE r.Status = 'COD' OR r.Status = 'Paid' 
  GROUP BY p.ProID, p.ProName, p.StockQty, r.InvoiceID -- เพิ่ม r.InvoiceID เข้าไปใน GROUP BY
  ORDER BY r.InvoiceID
  ");
  $stmt->execute();
  $result = $stmt->get_result();
  $stmt->close();
  return $result;
}
