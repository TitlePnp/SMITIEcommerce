<?php
  session_start();
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  require '../../Components/ConnectDB.php';
  require '../../Backend/Authorized/GetID.php';
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

        //   $recID = newRecID();
        //   $stmt = $connectDB->prepare("INSERT INTO RECEIPT (RecID, PayTime, CusID, TaxID, InvoiceID, Payment, Status, Channel) 
        //                                 VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        //   $stmt->bind_param("ssisssss", $recID, date("Y-m-d H:i:s"), $id, 0, $invoiceID, $imgContent, 'Pending', 'Upload');
        //   $stmt->execute();
          $_SESSION['success'] = "อัปโหลดรูปภาพการชำระเงินสำเร็จ";
          header("Location: ../../Frontend/UploadPage/Upload.php");
        } else {
          $_SESSION['error'] = "เกิดข้อผิดพลาด โปรดเลือกอัปโหลดใหม่อีกครั้ง";
          header("Location: ../../Frontend/UploadPage/Upload.php");
        }
    } else {
      $_SESSION['error'] = "ไม่พบหมายเลขคำสั่งซื้อของคุณ";
      header("Location: ../../Frontend/UploadPage/Upload.php");
    }

  function findInvoice($invoiceID, $id) {
    echo $invoiceID;
    global $connectDB;
    $status = 'Ordered';
    $stmt = $connectDB->prepare("SELECT COUNT(ir.InvoiceID) AS HaveInvoice FROM INVOICE_ORDER ir 
            WHERE ir.InvoiceID = ? AND ir.Status = ? AND ir.CusID = ?");
    $stmt->bind_param("ssi", $invoiceID, $status, $id);
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
    $result = $result->fetch_assoc()['RecID'];
    $stmt->close();
    $lastID = (int) substr($result, 1);
    $lastID++;
    $newID = "R" . $lastID;
    return $newID;
  }

  function findReceipt($invoiceID) {
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT COUNT(r.InvoiceID) AS HaveReceipt FROM RECEIPT r WHERE r.InvoiceID = ?");
    $stmt->bind_param("s", $invoiceID);
    $stmt->execute();
    $result = $stmt->get_result();
    $result = $result->fetch_assoc()['HaveReceipt'];
    $stmt->close();
    return $result;
  }
?>