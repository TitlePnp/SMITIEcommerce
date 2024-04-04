<?php
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  require '../../Components/ConnectDB.php';
  if(isset($_POST['InvoiceID'])){
    $invoiceID = $_POST['InvoiceID'];
  } else {
    header("Location: ../../Frontend/MainPage/Home.php");
  }

  function PayerDetail($invoiceID) {
    $payerID = getPayerID($invoiceID);
    global $connectDB;
    $stmt = $connectDB->prepare(
      "SELECT p.PayerTaxID, p.PayerFName, p.PayerLName, p.PayerTel, p.PayerAddress, p.PayerProvince, p.PayerPostcode
      FROM PAYER p
      WHERE p.PayerID = ?");
    $stmt->bind_param("i", $payerID);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
  }

  function getPayerID($invoiceID) {
    global $connectDB;
    $stmt = $connectDB->prepare(
      "SELECT ir.PayerID
      FROM INVOICE_ORDER ir
      WHERE ir.InvoiceID = ?");
      echo $invoiceID;
    $stmt->bind_param("s", $invoiceID);
    $stmt->execute();
    $result = $stmt->get_result();
    $result = $result->fetch_assoc()['PayerID'];
    $stmt->close();
    return $result;
  }

  function InvoiceDetail($invoiceID) {
    global $connectDB;
    $stmt = $connectDB->prepare(
      "SELECT ir.StartDate, ir.EndDate
      FROM INVOICE_ORDER ir
      WHERE ir.InvoiceID = ?");
    $stmt->bind_param("s", $invoiceID);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
  }

  function ProductDetail($invoiceID) {
    global $connectDB;
    $stmt = $connectDB->prepare(
      "SELECT p.ProName, p.Author, p.PricePerUnit, il.Qty, i.TotalPrice, i.VAT
      FROM INVOICE_LIST il
      JOIN PRODUCT p ON il.ProID = p.ProID
      JOIN INVOICE_ORDER i ON il.InvoiceID = i.InvoiceID
      WHERE il.InvoiceID = ?");
    $stmt->bind_param("s", $invoiceID);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
  }
?>