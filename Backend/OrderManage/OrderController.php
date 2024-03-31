<?php
session_start();
require_once "OrderManage.php";
require_once "../UserManage/UserInfo.php";
require_once "../../vendor/autoload.php";

use Firebase\JWT\Key;
use \Firebase\JWT\jwt;
/* ----------------------------- CusID ------------------------------ */

if (isset($_SESSION["tokenJWT"])) {
    $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
    $CusID = $decoded->cusid;
    $userInfo = getUserInfoFromCusID($CusID);
} else if (isset($_SESSION["tokenGoogle"])) {
    $userInfo = getGoogleUserInfo($_SESSION["tokenGoogle"]);
    $CusID = $userInfo['CusID'];
}

/* ----------------------------- Debug Field ------------------------------ */

$requiredFields = [
    'taxID', 'PayerFName', 'PayerLName', 'PayerSex',
    'PayerTel', 'PayerAddr', 'PayerProvince', 'PayerPostcode', 'RecvFName',
    'RecvLName', 'RecvSex', 'RecvTel', 'RecvAddr', 'RecvProvince', 'RecvPostcode',
    'ProIds', 'PaymentMethod', 'ProIds', 'TotalPrice'
];

$missingFields = [];

foreach ($requiredFields as $field) {
    if (!isset($_POST[$field])) {
        $missingFields[] = $field;
    }
}

if (!empty($missingFields)) {
    echo "The following fields are missing: " . implode(', ', $missingFields);
}


/* ----------------------------- Process Field ------------------------------ */

if ($_POST['taxInvoice'] == 'Yes') {

} else if ($_POST['taxInvoice'] == 'No') {

    $proIds = explode(',', $_POST['ProIds']);
}
