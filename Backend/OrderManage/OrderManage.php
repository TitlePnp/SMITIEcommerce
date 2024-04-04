<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once "../../Components/ConnectDB.php";
require_once "../../vendor/autoload.php";
require_once "../../Backend/CartQuery/CartDetail.php";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../Components', 'config.env');
$dotenv->load();

use Firebase\JWT\Key;
use \Firebase\JWT\jwt;

$key = $_ENV['JWT_KEY'];

date_default_timezone_set('Asia/Bangkok');

function getLastReceiverID($CusID)
{
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT RecvID FROM receiver WHERE CusID = ? ORDER BY RecvID DESC LIMIT 1;");
    $stmt->bind_param("i", $CusID);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($RecvID);
    $stmt->fetch();
    return $RecvID;
}

function insertReceiver($recvFName, $recvLName, $recvSex, $recvTel, $recvAddress, $recvProvince, $recvPostcode, $CusID)
{
    global $connectDB;
    $stmt = $connectDB->prepare("INSERT INTO receiver(RecvFName, RecvLName, RecvSex, RecvTel, RecvAddress, RecvProvince, RecvPostcode, CusID) VALUES (?,?,?,?,?,?,?,?);");
    $stmt->bind_param("sssssssi", $recvFName, $recvLName, $recvSex, $recvTel, $recvAddress, $recvProvince, $recvPostcode, $CusID);
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

    if ($NumID == null) {
        $NumID = 1;
    } else {
        $NumID++;
    }

    $stmt = $connectDB->prepare("INSERT INTO receiver_list(CusID, NumID, RecvID) VALUES (?, ?, ?);");
    $stmt->bind_param("iii", $CusID, $NumID, $recvID);
    $stmt->execute();
    $stmt->close();
}

function getLastPayerID($CusID)
{
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT PayerID FROM payer WHERE CusID = ? ORDER BY PayerID DESC LIMIT 1;");
    $stmt->bind_param("i", $CusID);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($PayerID);
    $stmt->fetch();
    return $PayerID;
}

function insertPayer($payerTaxID, $payerFName, $payerLName, $payerSex, $payerTel, $payerAddress, $payerProvince, $payerPostcode, $CusID, $PayerTag)
{
    global $connectDB;
    $stmt = $connectDB->prepare("INSERT INTO payer(PayerTaxID, PayerFName, PayerLName, PayerSex, PayerTel, PayerAddress, PayerProvince, PayerPostcode, CusID, TAG) VALUES (?,?,?,?,?,?,?,?,?,?);");
    $stmt->bind_param("ssssssssis", $payerTaxID, $payerFName, $payerLName, $payerSex, $payerTel, $payerAddress, $payerProvince, $payerPostcode, $CusID, $PayerTag);    $stmt->execute();
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

    if ($NumID == null) {
        $NumID = 1;
    } else {
        $NumID++;
    }

    $stmt = $connectDB->prepare("INSERT INTO payer_list(CusID, NumID, PayerID) VALUES (?, ?, ?);");
    $stmt->bind_param("iii", $CusID, $NumID, $payerID);
    $stmt->execute();
    $stmt->close();
}

function getLastInvoiceID()
{
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT InvoiceID FROM invoice_order WHERE InvoiceID LIKE 'IN%' ORDER BY CAST(SUBSTRING(InvoiceID, 3) AS UNSIGNED) DESC LIMIT 1;");
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($InvoiceID);
    $stmt->fetch();

    if ($stmt->num_rows == 0) {
        $InvoiceID = "IN1";
    }

    $stmt->close();
    return $InvoiceID;
}

function insertInvoice($InvoiceID, $CusID, $RecvID, $PayerID, $TotalPrice, $Vat, $Channel, $StartDate, $EndDate,)
{
    global $connectDB;
    $Status = "Ordered";
    $stmt = $connectDB->prepare("INSERT INTO invoice_order(InvoiceID, CusID, RecvID, PayerID, TotalPrice, Vat, Channel, StartDate, EndDate, Status) VALUES (?,?,?,?,?,?,?,?,?,?);");
    $stmt->bind_param("siiiddssss", $InvoiceID, $CusID, $RecvID, $PayerID, $TotalPrice, $Vat, $Channel,  $StartDate, $EndDate, $Status);
    $stmt->execute();
    $stmt->close();
}

function insertInvoiceList($CusID, $invoiceID, $ProIds)
{
    global $connectDB;
    $NumID = 1;

    if ($CusID != 1) {
        foreach ($ProIds as $proId) {
            $qtyQuery = getQtyFromCart($CusID, $proId)->fetch_assoc();
            $qty = $qtyQuery['Qty'];
            $stmt = $connectDB->prepare("INSERT INTO invoice_list(InvoiceID, NumID, ProID, Qty) VALUES (?, ?, ?, ?);");
            $stmt->bind_param("siii", $invoiceID, $NumID, $proId, $qty);
            $stmt->execute();
            $NumID++;
        }
    } else if ($CusID == 1) {
        foreach ($ProIds as $proId) {
            $qty = $_SESSION['cart'][$proId];
            $stmt = $connectDB->prepare("INSERT INTO invoice_list(InvoiceID, NumID, ProID, Qty) VALUES (?, ?, ?, ?);");
            $stmt->bind_param("siii", $invoiceID, $NumID, $proId, $qty);
            $stmt->execute();
            $NumID++;
        }
    }
}

function getReceiver($recvFName, $recvLName, $recvSex, $recvTel, $recvAddress, $recvProvince, $recvPostcode, $CusID)
{
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT RecvID FROM receiver WHERE RecvFName = ? AND RecvLName = ? AND RecvSex = ? AND RecvTel = ? AND RecvAddress = ? AND RecvProvince = ? AND RecvPostcode = ? AND CusID = ?;");
    $stmt->bind_param("sssssssi", $recvFName, $recvLName, $recvSex, $recvTel, $recvAddress, $recvProvince, $recvPostcode, $CusID);

    // $stmt->execute();
    // $result = $stmt->get_result();
    // $row = $result->fetch_assoc();
    // return $row['RecvID'];
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($RecvID);
    $stmt->fetch();
    return $RecvID;
}

function getPayer($payerTaxID, $payerFName, $payerLName, $payerSex, $payerTel, $payerAddress, $payerProvince, $payerPostcode, $CusID)
{
    global $connectDB;
    $sql = "SELECT PayerID FROM payer WHERE PayerFName = ? AND PayerLName = ? AND PayerSex = ? AND PayerTel = ? AND PayerAddress = ? AND PayerProvince = ? AND PayerPostcode = ? AND CusID = ?";
    if ($payerTaxID !== NULL) {
        $sql = "SELECT PayerID FROM payer WHERE PayerTaxID = ? AND PayerFName = ? AND PayerLName = ? AND PayerSex = ? AND PayerTel = ? AND PayerAddress = ? AND PayerProvince = ? AND PayerPostcode = ? AND CusID = ?";
    }
    $stmt = $connectDB->prepare($sql);
    if ($payerTaxID !== NULL) {
        $stmt->bind_param("ssssssssi", $payerTaxID, $payerFName, $payerLName, $payerSex, $payerTel, $payerAddress, $payerProvince, $payerPostcode, $CusID);
    } else {
        $stmt->bind_param("sssssssi", $payerFName, $payerLName, $payerSex, $payerTel, $payerAddress, $payerProvince, $payerPostcode, $CusID);
    }

    // $stmt->execute();
    // $result = $stmt->get_result();
    // $row = $result->fetch_assoc();
    // return $row['PayerID'];
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($PayerID);
    $stmt->fetch();
    return $PayerID;
}

function incrementInvoiceID($InvoiceID)
{
    // ตัดตัวอักษร 'IN' ออก และเก็บเฉพาะตัวเลข
    $numberPart = substr($InvoiceID, 2);

    // เพิ่มค่าตัวเลขด้วย 1
    $incrementedNumber = (int)$numberPart + 1;

    // รวม 'IN' กับตัวเลขที่เพิ่มค่าแล้ว
    $newInvoiceID = 'IN' . $incrementedNumber;

    return $newInvoiceID;
}
