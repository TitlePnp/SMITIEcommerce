<?php
  session_start();
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  require '../../Components/ConnectDB.php';
  require '../../Backend/Authorized/GetID.php';
  date_default_timezone_set('Asia/Bangkok');
  $invoiceID = "";
  $id = 1;
  if (isset($_POST['invoiceID'])) {
    $invoiceID = $_POST['invoiceID'];
  }

  if (isset($_SESSION['tokenJWT']) || isset($_SESSION['tokenGoogle'])) {
    $id = getID();
  }

  if (findInvoice($invoiceID, $id) == 1) {
    if (isset($_FILES['receipt']) && $_FILES['receipt']['error'] === 0) {
      $image = $_FILES['receipt']['tmp_name'];
      $imgContent = file_get_contents($image);
      $recID = newRecID();
      $payerID = getTaxID($invoiceID);
      $channel = 'Transfer';
      $date = date('Y-m-d H:i:s');
      $stmt = $connectDB->prepare("INSERT INTO RECEIPT (RecID, PayTime, CusID, PayerID, InvoiceID, TotalPrice, Vat, Payment, Channel) 
                                    SELECT ?, ?, ?, ?, ?, i.TotalPrice, i.Vat, ?, ? FROM INVOICE_ORDER i 
                                    WHERE i.InvoiceID = ?");
      $stmt->bind_param("ssisssss", $recID, $date, $id, $payerID, $invoiceID, $imgContent, $channel, $invoiceID);
      $stmt->execute();
      insertReceiptList($invoiceID, $recID);
      $_SESSION['success'] = "อัปโหลดรูปภาพการชำระเงินสำเร็จ รอการตรวจสอบจากทางร้าน";
      header("Location: ../../Frontend/UploadPage/Upload.php");
    } else {
      $_SESSION['error'] = "เกิดข้อผิดพลาด โปรดอัปโหลดใหม่อีกครั้ง";
      header("Location: ../../Frontend/UploadPage/Upload.php");
    }
  } else {
    $_SESSION['error'] = "ไม่พบหมายเลขคำสั่งซื้อของคุณ โปรดตรวจสอบอีกครั้ง";
    header("Location: ../../Frontend/UploadPage/Upload.php");
  }

  function findInvoice($invoiceID, $id) {
    global $connectDB;
    $status = 'Ordered';
    $channel = 'Transfer';
    $stmt = $connectDB->prepare("SELECT COUNT(ir.InvoiceID) AS HaveInvoice FROM INVOICE_ORDER ir 
            WHERE ir.InvoiceID = ? AND ir.Status = ? AND ir. Channel = ? AND ir.CusID = ?");
    $stmt->bind_param("sssi", $invoiceID, $status, $channel, $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $result = $result->fetch_assoc()['HaveInvoice'];
    $stmt->close();
    return $result;
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
    while (checkRecIDExists($newID)) {
      $lastID++;
      $newID = "R" . $lastID;
    }
    return $newID;
  }

  function checkRecIDExists($recID) {
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT COUNT(*) AS RecIDExists FROM RECEIPT WHERE RecID = ?");
    $stmt->bind_param("s", $recID);
    $stmt->execute();
    $result = $stmt->get_result();
    $fetchResult = $result->fetch_assoc();
    $stmt->close();
    return $fetchResult['RecIDExists'] > 0;
  }

  function getTaxID($invoiceID) {
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT ir.PayerID FROM INVOICE_ORDER ir WHERE ir.InvoiceID = ?");
    $stmt->bind_param("s", $invoiceID);
    $stmt->execute();
    $result = $stmt->get_result();
    $fetchResult = $result->fetch_assoc();
    $result = $fetchResult == NULL || !isset($fetchResult['PayerID']) ? NULL : $fetchResult['PayerID'];
    $stmt->close();
    return $result;
  }

  function insertReceiptList($invoiceID, $recID) {
    global $connectDB;
    $stmt = $connectDB->prepare("INSERT INTO RECEIPT_LIST (RecID, NumID, ProID, Qty)
                                SELECT ?, il.NumID, il.ProID, il.Qty FROM INVOICE_LIST il 
                                WHERE il.InvoiceID = ?");
    $stmt->bind_param("ss", $recID, $invoiceID);
    $stmt->execute();
    $stmt->close();
    updateInvoice($invoiceID);
  }

  function updateInvoice($invoiceID) {
    global $connectDB;
    $status = 'Cpmpleted';
    $stmt = $connectDB->prepare("UPDATE INVOICE_ORDER SET Status = ? WHERE InvoiceID = ?");
    $stmt->bind_param("ss", $status, $invoiceID);
    $stmt->execute();
    $stmt->close();
  }
?>