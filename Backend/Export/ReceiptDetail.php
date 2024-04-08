<?php
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  require '../../Components/ConnectDB.php';
  if(isset($_POST['ReceiptID'])){
    $receiptID = $_POST['ReceiptID'];
    echo $receiptID;
  } else {
    header("Location: ../../Frontend/MainPage/Home.php");
  }

  function PayerDetail($receiptID) {
    $payerID = getPayerID($receiptID);
    global $connectDB;
    $stmt = $connectDB->prepare(
      "SELECT p.PayerTaxID, p.PayerFName, p.PayerLName, p.PayerTel, p.PayerAddress, p.PayerProvince, p.PayerPostcode, p.TAG
      FROM PAYER p
      WHERE p.PayerID = ?");
    $stmt->bind_param("i", $payerID);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
  }

  function getPayerID($receiptID) {
    global $connectDB;
    $stmt = $connectDB->prepare(
      "SELECT r.PayerID
      FROM RECEIPT r
      WHERE r.RecID = ?");
    $stmt->bind_param("s", $receiptID);
    $stmt->execute();
    $result = $stmt->get_result();
    $result = $result->fetch_assoc()['PayerID'];
    $stmt->close();
    return $result;
  }

  function ReceiptDetail($receiptID) {
    global $connectDB;
    $stmt = $connectDB->prepare(
      "SELECT r.PayTime
      FROM RECEIPT r
      WHERE r.RecID = ?");
    $stmt->bind_param("s", $receiptID);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
  }

  function ProductDetail($receiptID) {
    global $connectDB;
    $stmt = $connectDB->prepare(
      "SELECT p.ProName, p.Author, p.PricePerUnit, rl.Qty, r.TotalPrice, r.VAT
      FROM RECEIPT_LIST rl
      JOIN PRODUCT p ON rl.ProID = p.ProID
      JOIN RECEIPT r ON rl.RecID = r.RecID
      WHERE r.RecID = ?");
    $stmt->bind_param("s", $receiptID);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
  }
?>