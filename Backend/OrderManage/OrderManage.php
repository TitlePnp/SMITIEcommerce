<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once "../../Components/ConnectDB.php";
require_once "../../vendor/autoload.php";
require_once "../../Backend/CartQuery/CartDetail.php";
require_once '../ProductQuery/ProductInfo.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../Components', 'config.env');
$dotenv->load();

use Firebase\JWT\Key;
use \Firebase\JWT\JWT;

$key = $_ENV['JWT_KEY'];

date_default_timezone_set('Asia/Bangkok');

function getLastReceiverID($CusID)
{
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT RecvID FROM RECEIVER WHERE CusID = ? ORDER BY RecvID DESC LIMIT 1;");
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
    $stmt = $connectDB->prepare("INSERT INTO RECEIVER(RecvFName, RecvLName, RecvSex, RecvTel, RecvAddress, RecvProvince, RecvPostcode, CusID) VALUES (?,?,?,?,?,?,?,?);");
    $stmt->bind_param("sssssssi", $recvFName, $recvLName, $recvSex, $recvTel, $recvAddress, $recvProvince, $recvPostcode, $CusID);
    $stmt->execute();
    $stmt->close();
};

function insertReceiverList($recvID, $CusID)
{
    global $connectDB;
    //query last NumID form RECEIVER_LIST where CusID = ? 
    $stmt = $connectDB->prepare("SELECT NumID FROM RECEIVER_LIST WHERE CusID = ? ORDER BY NumID DESC LIMIT 1;");
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

    $stmt = $connectDB->prepare("INSERT INTO RECEIVER_LIST(CusID, NumID, RecvID) VALUES (?, ?, ?);");
    $stmt->bind_param("iii", $CusID, $NumID, $recvID);
    $stmt->execute();
    $stmt->close();
}

function getLastPayerID($CusID)
{
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT PayerID FROM PAYER WHERE CusID = ? ORDER BY PayerID DESC LIMIT 1;");
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
    $stmt = $connectDB->prepare("INSERT INTO PAYER(PayerTaxID, PayerFName, PayerLName, PayerSex, PayerTel, PayerAddress, PayerProvince, PayerPostcode, CusID, TAG) VALUES (?,?,?,?,?,?,?,?,?,?);");
    $stmt->bind_param("ssssssssis", $payerTaxID, $payerFName, $payerLName, $payerSex, $payerTel, $payerAddress, $payerProvince, $payerPostcode, $CusID, $PayerTag);
    $stmt->execute();
    $stmt->close();
}

function insertPayerList($payerID, $CusID)
{
    global $connectDB;
    //query last NumID form payer_list where CusID = ?
    $stmt = $connectDB->prepare("SELECT NumID FROM PAYER_LIST WHERE CusID = ? ORDER BY NumID DESC LIMIT 1;");
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

    $stmt = $connectDB->prepare("INSERT INTO PAYER_LIST(CusID, NumID, PayerID) VALUES (?, ?, ?);");
    $stmt->bind_param("iii", $CusID, $NumID, $payerID);
    $stmt->execute();
    $stmt->close();
}

function getLastInvoiceID()
{
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT InvoiceID FROM INVOICE_ORDER WHERE InvoiceID LIKE 'IN%' ORDER BY CAST(SUBSTRING(InvoiceID, 3) AS UNSIGNED) DESC LIMIT 1;");
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
    $stmt = $connectDB->prepare("INSERT INTO INVOICE_ORDER(InvoiceID, CusID, RecvID, PayerID, TotalPrice, Vat, Channel, StartDate, EndDate, Status) VALUES (?,?,?,?,?,?,?,?,?,?);");
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
            $stmt = $connectDB->prepare("INSERT INTO INVOICE_LIST(InvoiceID, NumID, ProID, Qty) VALUES (?, ?, ?, ?);");
            $stmt->bind_param("siii", $invoiceID, $NumID, $proId, $qty);
            $stmt->execute();
            $NumID++;
        }
    } else if ($CusID == 1) {
        foreach ($ProIds as $proId) {
            $qty = $_SESSION['cart'][$proId];
            $stmt = $connectDB->prepare("INSERT INTO INVOICE_LIST(InvoiceID, NumID, ProID, Qty) VALUES (?, ?, ?, ?);");
            $stmt->bind_param("siii", $invoiceID, $NumID, $proId, $qty);
            $stmt->execute();
            $NumID++;
        }
    }
}

function getReceiver($recvFName, $recvLName, $recvSex, $recvTel, $recvAddress, $recvProvince, $recvPostcode, $CusID)
{
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT RecvID FROM RECEIVER WHERE RecvFName = ? AND RecvLName = ? AND RecvSex = ? AND RecvTel = ? AND RecvAddress = ? AND RecvProvince = ? AND RecvPostcode = ? AND CusID = ?;");
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
    $sql = "SELECT PayerID FROM PAYER WHERE PayerFName = ? AND PayerLName = ? AND PayerSex = ? AND PayerTel = ? AND PayerAddress = ? AND PayerProvince = ? AND PayerPostcode = ? AND CusID = ?";
    if ($payerTaxID !== NULL) {
        $sql = "SELECT PayerID FROM PAYER WHERE PayerTaxID = ? AND PayerFName = ? AND PayerLName = ? AND PayerSex = ? AND PayerTel = ? AND PayerAddress = ? AND PayerProvince = ? AND PayerPostcode = ? AND CusID = ?";
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

function cutStock($ProIds, $CusID)
{
    global $connectDB;
    if ($CusID != 1) {
        foreach ($ProIds as $proId) {
            $result = selectProductByID($proId);
            $row = $result->fetch_assoc();
            $qtyQuery = getQtyFromCart($CusID)->fetch_assoc();
            $qty = $qtyQuery['Qty'];
            $setQty = $row['StockQty'] - $qty;
            $stmt = $connectDB->prepare("UPDATE PRODUCT SET StockQty =  ? WHERE ProID = ?;");
            $stmt->bind_param("ii", $setQty, $proId);
            $stmt->execute();
            $stmt->close();
        }
    } else if ($CusID == 1) {
        foreach ($ProIds as $proId) {
            $result = selectProductByID($proId);
            $row = $result->fetch_assoc();
            $qty = $_SESSION['cart'][$proId];
            $setQty = $row['StockQty'] - $qty;
            $stmt = $connectDB->prepare("UPDATE PRODUCT SET StockQty = ? WHERE ProID = ?;");
            $stmt->bind_param("ii", $setQty, $proId);
            $stmt->execute();
            $stmt->close();
        }
    }
}

function cutStockByInvoice($InvoiceID)
{
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT ProID, Qty FROM INVOICE_LIST WHERE InvoiceID = ?");
    $stmt->bind_param("s", $InvoiceID);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $proId = $row['ProID'];
        $qty = $row['Qty'];
        $PRODUCTResult = selectProductByID($proId);
        $PRODUCTRow = $PRODUCTResult->fetch_assoc();
        $setQty = $PRODUCTRow['StockQty'] - $qty;
        $stmt = $connectDB->prepare("UPDATE PRODUCT SET StockQty = ? WHERE ProID = ?");
        $stmt->bind_param("ii", $setQty, $proId);
        $stmt->execute();
    }
    $stmt->close();
}


function updateInvoice($invoiceID)
{
    global $connectDB;
    $status = 'Completed';
    $stmt = $connectDB->prepare("UPDATE INVOICE_ORDER SET Status = ? WHERE InvoiceID = ?");
    $stmt->bind_param("ss", $status, $invoiceID);
    $stmt->execute();
    $stmt->close();
}
