<?php
session_start();
require_once "OrderManage.php";
require_once "../UserManage/UserInfo.php";
require_once "../../vendor/autoload.php";
require_once "../../Backend/CartQuery/CartDetail.php";
require_once "../../Backend/CartQuery/DeleteFromCart.php";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../Components', 'config.env');
$dotenv->load();

use Firebase\JWT\Key;
use \Firebase\JWT\jwt;

$key = $_ENV['JWT_KEY'];


date_default_timezone_set('Asia/Bangkok');

/* ----------------------------- CusID ------------------------------ */
try {
    if (isset($_SESSION["tokenJWT"])) {
        $jwt = $_SESSION["tokenJWT"];
        $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
        $CusID = $decoded->cusid;
        $userInfo = getUserInfoFromCusID($CusID);
    } else if (isset($_SESSION["tokenGoogle"])) {
        $userInfo = getGoogleUserInfo($_SESSION["tokenGoogle"]);
        $CusID = $userInfo['CusID'];
    } else {
        $CusID = 1;
    }

    /* ----------------------------- Process Field ------------------------------ */

    //define all Post
    $PayerFName = $_POST['PayerFName'];
    $PayerLName = $_POST['PayerLName'];
    $PayerSex = $_POST['PayerSex'];
    $PayerTel = $_POST['PayerTel'];
    $PayerAddr = $_POST['PayerAddr'];
    $PayerProvince = $_POST['PayerProvince'];
    $PayerPostcode = $_POST['PayerPostcode'];

    $RecvFName = $_POST['RecvFName'];
    $RecvLName = $_POST['RecvLName'];
    $RecvSex = $_POST['RecvSex'];
    $RecvTel = $_POST['RecvTel'];
    $RecvAddr = $_POST['RecvAddr'];
    $RecvProvince = $_POST['RecvProvince'];
    $RecvPostcode = $_POST['RecvPostcode'];

    $ProIds = $_POST['ProIds'];
    $PaymentMethod = $_POST['PaymentMethod'];
    $TotalPrice = $_POST['TotalPrice'];

    $ProIds = explode(',', $ProIds);
    // $ciphertext = "";

    if ($_POST['taxInvoice'] == 'Yes') {

        $taxID = $_POST['taxID'];
        $encryptionKey = $_ENV['ENCRYPT_KEY'];
        $iv = $_ENV['IV'];
        $tag = $_ENV['TAG'];
        $ciphertext = openssl_encrypt($taxID, 'aes-256-gcm', $encryptionKey, $options = 0, $iv, $tag);
        $_SESSION['enDec'] = ['key' => $encryptionKey, 'iv' => $iv, 'tag' => $tag, 'ciphertext' => $ciphertext];

        if ($ciphertext === false) {
            die('การเข้ารหัสล้มเหลว');
        }
        $havePayer = getPayer($ciphertext, $PayerFName, $PayerLName, $PayerSex, $PayerTel, $PayerAddr, $PayerProvince, $PayerPostcode, $CusID);

        if ($havePayer == null) {
            insertPayer($ciphertext, $PayerFName, $PayerLName, $PayerSex, $PayerTel, $PayerAddr, $PayerProvince, $PayerPostcode, $CusID);
            $lastPayerId = getLastPayerID($CusID);
            insertPayerList($lastPayerId, $CusID);
        }
    } else if ($_POST['taxInvoice'] == 'No') {
        $taxID = NULL;
        $havePayer = getPayer($taxID, $PayerFName, $PayerLName, $PayerSex, $PayerTel, $PayerAddr, $PayerProvince, $PayerPostcode, $CusID);
        $taxID = "";
        if ($havePayer == null) {
            insertPayer($taxID, $PayerFName, $PayerLName, $PayerSex, $PayerTel, $PayerAddr, $PayerProvince, $PayerPostcode, $CusID);
            $lastPayerId = getLastPayerID($CusID);
            insertPayerList($lastPayerId, $CusID);
        }
    }
    $haveReceiver = getReceiver($RecvFName, $RecvLName, $RecvSex, $RecvTel, $RecvAddr, $RecvProvince, $RecvPostcode, $CusID);
    if ($haveReceiver == null) {
        insertReceiver($RecvFName, $RecvLName, $RecvSex, $RecvTel, $RecvAddr, $RecvProvince, $RecvPostcode, $CusID);
        $lastReceiverId = getLastReceiverID($CusID);
        insertReceiverList($lastReceiverId, $CusID);
    }

    $lastInvoiceID = getLastInvoiceID();
    $newInvoiceID = incrementInvoiceID($lastInvoiceID);
    $receiverID = getReceiver($RecvFName, $RecvLName, $RecvSex, $RecvTel, $RecvAddr, $RecvProvince, $RecvPostcode, $CusID);
    // var_dump($ciphertext);
    $payerID = getPayer($ciphertext, $PayerFName, $PayerLName, $PayerSex, $PayerTel, $PayerAddr, $PayerProvince, $PayerPostcode, $CusID);
    // var_dump($payerID);
    $vat = $TotalPrice * 0.07;
    $startDate = date("Y-m-d H:i:s");
    $endDate = date("Y-m-d H:i:s", strtotime("+1 day"));
    insertInvoice($newInvoiceID, $CusID, $receiverID, $payerID, $TotalPrice, $vat,  $PaymentMethod, $startDate, $endDate);
    insertInvoiceList($CusID, $newInvoiceID, $ProIds);
    updateOnHand($ProIds);
    echo "Success";

    if ($PaymentMethod == "MobileBanking") {
        $_SESSION['InvoiceID'] = $newInvoiceID;
        header("Location: ../../Frontend/MainPage/Payment.php");
    } else if ($PaymentMethod == "COD") {
        $_SESSION['InvoiceID'] = $newInvoiceID;
        header("Location: ../../Frontend/OrderStatus/OrderStatus.php");
    }
} catch (Exception $e) {
    echo $e;
}

/* ----------------------------- Debug Field ------------------------------ */

// $requiredFields = [
//     'taxID', 'PayerFName', 'PayerLName', 'PayerSex',
//     'PayerTel', 'PayerAddr', 'PayerProvince', 'PayerPostcode', 'RecvFName',
//     'RecvLName', 'RecvSex', 'RecvTel', 'RecvAddr', 'RecvProvince', 'RecvPostcode',
//     'ProIds', 'PaymentMethod', 'ProIds', 'TotalPrice'
// ];

// $missingFields = [];

// foreach ($requiredFields as $field) {
//     if (!isset($_POST[$field])) {
//         $missingFields[] = $field;
//     }
// }

// if (!empty($missingFields)) {
//     echo "The following fields are missing: " . implode(', ', $missingFields);
// }
