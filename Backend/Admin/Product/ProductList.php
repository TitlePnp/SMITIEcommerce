<?php
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  require '../../Components/ConnectDB.php';
  function productList() {
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT p.ProID, p.ProName, p.Author, p.Description, p.PricePerUnit, 
                                FROM PRODUCT p");
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
  
  function receiptDetail($status, $status2) {
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT r.RecID, r.PayTime, r.CusID, r.PayerID, r.InvoiceID, r.TotalPrice, r.Vat, r.Payment, r.Channel 
                                FROM RECEIPT r WHERE r.Status = ? OR r.Status = ?
                                ORDER BY r.PayTime");
    $stmt->bind_param("ss", $status, $status2);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
  }

  function cusDetail($cusID) {
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT c.CusFName, CusLName, c.Tel
                                FROM CUSTOMER c WHERE c.CusID = ?");
    $stmt->bind_param("i", $cusID);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
  }

  function payerDetail($payerID) {
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT p.PayerFName, p.PayerLName, p.PayerTel
                                FROM PAYER p WHERE p.PayerID = ?");
    $stmt->bind_param("i", $payerID);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
  }

  function getRecvID($invoiceID) {
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT i.RecvID FROM INVOICE_ORDER i WHERE i.InvoiceID = ?");
    $stmt->bind_param("s", $invoiceID);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
  }

  function getPayment($recID) {
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT r.PayerID, r.TotalPrice, r.Vat, r.Payment
                                FROM RECEIPT r WHERE r.RecID = ?");
    $stmt->bind_param("s", $recID);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
  }
?>