<?php
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  require '../../Components/ConnectDB.php';
  date_default_timezone_set('Asia/Bangkok');
  function insertReceipt($InvoiceID, $CusID, $RecvID, $PayerID, $TotalPrice, $Vat,) {
    global $connectDB;
    $channel = 'COD';
    $date = date('Y-m-d H:i:s');
    $status = 'COD';
    $recID = newRecID();
    $stmt = $connectDB->prepare("INSERT INTO RECEIPT (RecID, PayTime, CusID, PayerID, InvoiceID, TotalPrice, Vat, Status, Channel) 
                                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiisssss", $recID, $date, $CusID, $PayerID, $InvoiceID, $TotalPrice, $Vat, $status, $channel);
    $stmt->execute();
    $stmt->close();
    insertReceiptList($InvoiceID, $recID);
  }

  function insertReceiptList($invoiceID, $recID) {
    global $connectDB;
    $stmt = $connectDB->prepare("INSERT INTO RECEIPT_LIST (RecID, NumID, ProID, Qty)
                                SELECT ?, il.NumID, il.ProID, il.Qty FROM INVOICE_LIST il 
                                WHERE il.InvoiceID = ?");
    $stmt->bind_param("ss", $recID, $invoiceID);
    $stmt->execute();
    $stmt->close();
  }

  function newRecID() {
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT r.RecID FROM RECEIPT r ORDER BY r.RecID DESC LIMIT 1");
    $stmt->execute();
    $result = $stmt->get_result();
    $fetchResult = $result->fetch_assoc();
    $result = $fetchResult == NULL || !isset($fetchResult['RecID']) ? "R0" : $fetchResult['RecID'];
    $stmt->close();
    $lastID = (int) substr($result, 1);
    $lastID++;
    $newID = "R" . $lastID;
    return $newID;
  }
?>