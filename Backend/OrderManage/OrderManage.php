<?php
require_once "../../Components/ConnectDB.php";
require_once "../../vendor/autoload.php";

use Firebase\JWT\Key;
use \Firebase\JWT\jwt;

$key = "SECRETKEY_SMITIECOM_CLIENT";

date_default_timezone_set('Asia/Bangkok');

function insertReceiver($recvFName, $recvLName, $recvSex, $recvTel, $recvAddress, $recvPostcode, $CusID)
{
    global $connectDB;
    $stmt = $connectDB->prepare("INSERT INTO receiver(RecvFName, RecvLName, RecvSex, RecvTel, RecvAddress, RecvAddress, RecvPostcode, CusID) VALUES (?,?,?,?,?,?,?,?);");
    $stmt->bind_param("ssssssi", $recvFName, $recvLName, $recvSex, $recvTel, $recvAddress, $recvPostcode, $CusID);
    $stmt->execute();
    $stmt->close();
};

function insertReceiverList($recvID, $CusID)
{
    global $connectDB;
    //query last NumID form receiver_list where CusID = ? 
    $stmt = $connectDB->prepare("SELECT NumID FROM receiver_list WHERE CusID = ? ORDER BY NumID DESC LIMIT 1;");
    $stmt->bind_param("i", $CusID);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($NumID);
    $stmt->fetch();

    $stmt = $connectDB->prepare("INSERT INTO receiver_list(CusID, NumID, RecvID) VALUES (?, ?, ?);");
    $stmt->bind_param("iii", $CusID, $NumID, $recvID);
    $stmt->execute();
    $stmt->close();
}

function insertPayer($payerTaxID, $payerFName, $payerLName, $payerSex, $payerTel, $payerAddress, $payerProvince, $payerPostcode, $CusID)
{
    global $connectDB;
    $stmt = $connectDB->prepare("INSERT INTO payer(PayerTaxID, PayerFName, PayerLName, PayerSex, PayerTel, PayerAddress, PayerProvince, PayerPostcode, CusID) VALUES (?,?,?,?,?,?,?,?,?);");
    $stmt->bind_param("ssssssssi", $payerTaxID, $payerFName, $payerLName, $payerSex, $payerTel, $payerAddress, $payerProvince, $payerPostcode, $CusID);
    $stmt->execute();
    $stmt->close();
}

function insertPayerList($payerID, $CusID)
{
    global $connectDB;
    //query last NumID form payer_list where CusID = ?
    $stmt = $connectDB->prepare("SELECT NumID FROM payer_list WHERE CusID = ? ORDER BY NumID DESC LIMIT 1;");
    $stmt->bind_param("i", $CusID);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($NumID);
    $stmt->fetch();

    $stmt = $connectDB->prepare("INSERT INTO payer_list(CusID, NumID, PayerID) VALUES (?, ?, ?);");
    $stmt->bind_param("iii", $CusID, $NumID, $payerID);
    $stmt->execute();
    $stmt->close();
}

function insertInvoice(&$InvoiceID, $CusID, $RecvID, $PayerID, $TotalPrice, $Vat, $Channel, $StartDate, $EndDate,)
{
    global $connectDB;
    $Status = "Ordered";
    $stmt = $connectDB->prepare("INSERT INTO invoice(InvoiceID, CusID, RecvID, PayerID, TotalPrice, Vat, Channel StartDate, EndDate, Status) VALUES (?,?,?,?,?,?,?,?,?,?);");
    $stmt->bind_param("iississs", $InvoiceID, $CusID, $RecvID, $PayerID, $TotalPrice, $Vat, $Channel,  $StartDate, $EndDate, $Status);
    $stmt->execute();
    $stmt->close();
}

function insertInvoiceList($CusID, $ProIds)
{
    global $connectDB;
    //query last NumID form invoice_list where CusID = ?
    $stmt = $connectDB->prepare("SELECT NumID FROM invoice_list WHERE CusID = ? ORDER BY NumID DESC LIMIT 1;");
    $stmt->bind_param("i", $CusID);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($NumID);
    $stmt->fetch();

    if ($CusID != 1) {
        foreach ($ProIds as $proId) {
            $qtyQuery = getQtyFromCart($CusID, $proId)->fetch_assoc();
            $qty = $qtyQuery['Qty'];
            $stmt = $connectDB->prepare("INSERT INTO invoice_list(InvoiceID, NumID, ProID, Qty) VALUES (?, ?, ?, ?);");
            $stmt->bind_param("iiii", $CusID, $NumID, $proId, $qty);
            $stmt->execute();
        }
    } else if ($CusID == 1) {
        foreach ($ProIds as $proId) {
            $qty = $_SESSION['cart'][$proId];
            $stmt = $connectDB->prepare("INSERT INTO invoice_list(InvoiceID, NumID, ProID, Qty) VALUES (?, ?, ?, ?);");
            $stmt->bind_param("iiii", $CusID, $NumID, $proId, $qty);
            $stmt->execute();
        }
    }
}
