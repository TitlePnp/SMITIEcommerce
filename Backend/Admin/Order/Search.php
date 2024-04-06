<?php
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  require '../../Components/ConnectDB.php';
  function searchReceipt($search) {
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT r.RecID, r.PayTime, r.CusID, r.PayerID, r.InvoiceID, r.TotalPrice, r.Vat, r.Payment, r.Status, r.Channel 
                                FROM RECEIPT r WHERE r.RecID LIKE ?");
    $search = "%$search%";
    $stmt->bind_param("s", $search);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->num_rows;
    if ($count == 0) {
      $cusID = findCusID($search);
      $result = searchByCus($cusID);
      $count = $result->num_rows;
    }

    if ($count == 0) {
      $payerID = findPayerID($search);
      $result = searchByPayer($payerID);
      $count = $result->num_rows;
    }

    if ($count == 0) {
      $recvID = findRecvID($search);
      $invoiceID = getInvoiceID($recvID);
      $result = searchByRecv($invoiceID);
    }
    $stmt->close();
    return $result;
  }

  function findCusID($name) {
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT c.CusID FROM CUSTOMER c WHERE c.CusFName LIKE ? OR c.CusLName LIKE ?");
    $name = "%$name%";
    $stmt->bind_param("ss", $name, $name);
    $stmt->execute();
    $result = $stmt->get_result();
    $result = $result->fetch_assoc();
    $stmt->close();
    return $result;
  }

  function searchByCus($cusID) {
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT r.RecID, r.PayTime, r.CusID, r.PayerID, r.InvoiceID, r.TotalPrice, r.Vat, r.Payment, r.Channel 
                                FROM RECEIPT r WHERE r.CusID = ?");
    $stmt->bind_param("i", $cusID);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
  }

  function findPayerID($name) {
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT p.PayerID FROM PAYER p WHERE p.PayerFName LIKE ? OR p.PayerLName LIKE ?");
    $name = "%$name%";
    $stmt->bind_param("ss", $name, $name);
    $stmt->execute();
    $result = $stmt->get_result();
    $result = $result->fetch_assoc();
    $stmt->close();
    return $result;
  }

  function searchByPayer($payerID) {
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT r.RecID, r.PayTime, r.CusID, r.PayerID, r.InvoiceID, r.TotalPrice, r.Vat, r.Payment, r.Status, r.Channel 
                                FROM RECEIPT r WHERE r.PayerID = ?");
    $stmt->bind_param("i", $payerID);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
  }

  function findRecvID($search) {
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT r.RecvID FROM RECEIVER r WHERE r.RecvFName LIKE ? OR r.RecvLName LIKE ?");
    $search = "%$search%";
    $stmt->bind_param("ss", $search, $search);
    $stmt->execute();
    $result = $stmt->get_result();
    $result = $result->fetch_assoc();
    $stmt->close();
    return $result;
  }


  function getInvoiceID($recvID) {
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT i.InvoiceID FROM INVOICE_ORDER i WHERE i.RecvID = ?");
    $stmt->bind_param("i", $recvID);
    $stmt->execute();
    $result = $stmt->get_result();
    $result = $result->fetch_assoc();
    $stmt->close();
    return $result;
  }

  function searchByRecv($invoiceID) {
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT r.RecID, r.PayTime, r.CusID, r.PayerID, r.InvoiceID, r.TotalPrice, r.Vat, r.Payment, r.Status, r.Channel 
                                FROM RECEIPT r WHERE r.InvoiceID = ?");
    $stmt->bind_param("i", $invoiceID);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->num_rows;
    $stmt->close();
    return $result;
  }

  function searchByDate($startDate, $endDate, $status, $status2) {
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT r.RecID, r.PayTime, r.CusID, r.PayerID, r.InvoiceID, r.TotalPrice, r.Vat, r.Payment, r.Channel 
                                FROM RECEIPT r 
                                WHERE r.Status = ? OR r.Status = ? AND r.PayTime BETWEEN ? AND ?");
    $stmt->bind_param("ssss", $status, $status2, $startDate, $endDate);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
  }
?>