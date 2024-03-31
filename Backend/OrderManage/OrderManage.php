<?php
require_once "../../Components/ConnectDB.php";
require_once "../../vendor/autoload.php";

use Firebase\JWT\Key;
use \Firebase\JWT\jwt;

$key = "SECRETKEY_SMITIECOM_CLIENT";

date_default_timezone_set('Asia/Bangkok');

function insertPayer($TaxID, $PayerFName, $PayerLName, $PayerSex, $PayerTel, $PayerAddr)
{
    global $connectDB;
    $stmt = $connectDB->prepare("INSERT INTO Payer (TaxID, PayerFName, PayerLName, PayerSex, PayerTel, PayerAddr) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $TaxID, $PayerFName, $PayerLName, $PayerSex, $PayerTel, $PayerAddr);
    $stmt->execute();
    $stmt->close();
}

function insertPayerList($CusID, $TaxID)
{
    global $connectDB;
    if (!isset($_SESSION['tokenJWT']) && !isset($_SESSION['tokenGoogle'])) {
        $stmt = $connectDB->prepare("SELECT MAX(NumID) AS MaxNumID FROM payer_list");
        $stmt->execute();
        $result = $stmt->get_result();
        $NumID = $result->fetch_assoc()['MaxNumID'] + 1;
    } else if (isset($_SESSION['tokenJWT']) || isset($_SESSION['tokenGoogle'])) {
        $NumID = 1;
    }
    $stmt = $connectDB->prepare("INSERT INTO payer_list (CusID, NumID, TaxID) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $CusID, $NumID, $TaxID);
    $stmt->execute();
    $stmt->close();
}

function insertReceiver($RecvFName, $RecvLName, $RecvSex, $RecvTel, $RecvAddr)
{
    global $connectDB;
    $stmt = $connectDB->prepare("INSERT INTO Receiver (RecvFName, RecvLName, Sex, Tel, Address) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $RecvFName, $RecvLName, $RecvSex, $RecvTel, $RecvAddr);
    $stmt->execute();
}

function insertReceiverList($recieverID)
{
    global $connectDB, $jwt, $key;
    if (!isset($_SESSION['tokenJWT']) && !isset($_SESSION['tokenGoogle'])) {
        $stmt = $connectDB->prepare("SELECT MAX(NumID) AS MaxNumID FROM receiver_list");
        $stmt->execute();
        $result = $stmt->get_result();
        $NumID = $result->fetch_assoc()['MaxNumID'] + 1;
        $CusID = 1;
    } else if (isset($_SESSION['tokenJWT']) || isset($_SESSION['tokenGoogle'])) {
        if (isset($_SESSION['tokenJWT'])) {
            $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
            $CusID = $decoded->CusID;
            $stmt = $connectDB->prepare("SELECT MAX(NumID) AS MaxNumID FROM receiver_list WHERE CusID = ?");
            $stmt->bind_param("s", $CusID);
            $stmt->execute();
            $result = $stmt->get_result();
            $NumID = $result->fetch_assoc()['MaxNumID'] + 1;
        } else if (isset($_SESSION['tokenGoogle'])) {
            $googleToken = $_SESSION['tokenGoogle'];
            $stmt = $connectDB->prepare("SELECT CusID FROM customer_account WHERE GoogleID = ?");
            $stmt->bind_param("s", $googleToken);
            $stmt->execute();
            $result = $stmt->get_result();
            $CusID = $result->fetch_assoc()['CusID'];
            $stmt = $connectDB->prepare("SELECT MAX(NumID) AS MaxNumID FROM receiver_list WHERE CusID = ?");
            $stmt->bind_param("s", $CusID);
            $stmt->execute();
            $result = $stmt->get_result();
            $NumID = $result->fetch_assoc()['MaxNumID'] + 1;
        }
    }
    // Query last NumID from receiver_list where cusID = $CusID
    // $stmt = $connectDB->prepare("SELECT MAX(NumID) AS MaxNumID FROM receiver_list WHERE CusID = ?");
    // $stmt->bind_param("s", $CusID);
    // $stmt->execute();
    // $result = $stmt->get_result();
    // $NumID = $result->fetch_assoc()['MaxNumID'] + 1;

    $stmt = $connectDB->prepare("INSERT INTO receiver_list (CusID, NumID, RecvID) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $CusID, $NumID, $recieverID);
    $stmt->execute();
    $stmt->close();
}


function insertInvoiceOrder($InvoiceID, $payment)
{
    global $connectDB, $jwt, $key;
    $StartDate = date("Y-m-d H:i:s");
    $EndDate = date("Y-m-d H:i:s", strtotime($StartDate . ' + 1 day'));

    if (isset($_SESSION['tokenJWT'])) {
        $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
        $CusID = $decoded->CusID;
    } else if (isset($_SESSION['tokenGoogle'])) {
        $googleToken = $_SESSION['tokenGoogle'];
        $stmt = $connectDB->prepare("SELECT CusID FROM customer_account WHERE CusID = ?");
        $stmt->bind_param("s", $googleToken);
        $stmt->execute();
        $result = $stmt->get_result();
        $CusID = $result->fetch_assoc()['CusID'];
    } else {
        $CusID = 1;
    }

    if ($CusID !== null) {
        $status = 'Ordered';
        $stmt = $connectDB->prepare("INSERT INTO invoice_order(InvoiceID, CusID, StartDate, EndDate, Status, Channel, TotalPrice, Vat) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $InvoiceID, $CusID, $StartDate, $EndDate, $status, $payment);
        $stmt->execute();
        $stmt->close();
    } else {
        var_dump($CusID);
    }
}

function insertInvoice_list($InvoiceID, $cart)
{
    global $connectDB;

    $NumID = 1;
    foreach ($cart as $ProductID => $Quantity) {
        $stmt = $connectDB->prepare("INSERT INTO invoice_list(InvoiceID, NumID, ProID, Qty) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $InvoiceID, $NumID, $ProductID, $Quantity);
        $stmt->execute();
        $NumID++;
    }
    // $stmt->close();
}

function getNewInvoiceID()
{
    global $connectDB;

    $stmt = $connectDB->prepare("SELECT InvoiceID FROM invoice_order ORDER BY CAST(SUBSTRING(InvoiceID FROM 2) AS UNSIGNED) DESC LIMIT 1");
    $stmt->execute();
    $result = $stmt->get_result();
    $lastInvoiceID = $result->fetch_assoc()['InvoiceID'];    // If there is no invoice in the database, return "N1"
    if ($lastInvoiceID == null) {
        return "N1";
    }
    //if have invoice in the database +1 ex N1 -> N2
    $prefix = substr($lastInvoiceID, 0, 1); // Get the prefix (e.g., "N")
    $number = intval(substr($lastInvoiceID, 1)); // Get the number (e.g., 1 from "N1")
    $number++;
    return $prefix . $number;
}

function getInvoiceID()
{
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT InvoiceID FROM invoice_order ORDER BY CAST(SUBSTRING(InvoiceID FROM 2) AS UNSIGNED) DESC LIMIT 1");
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result->fetch_assoc()['InvoiceID'];
}

function getRecvID()
{
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT MAX(RecvID) AS LastRecvID FROM receiver");
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result->fetch_assoc()['LastRecvID'];
}
