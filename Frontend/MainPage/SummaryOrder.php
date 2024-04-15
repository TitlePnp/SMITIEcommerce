<?php
require_once "../../Backend/Authorized/UserAuthorized.php";
require_once "../../Backend/Authorized/ManageHeader.php";
require_once "../../Backend/CartQuery/CartDetail.php";
require_once "../../Backend/UserManage/UserInfo.php";
require '../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../Components', 'config.env');
$dotenv->load();

use Firebase\JWT\Key;
use \Firebase\JWT\JWT;

$key = $_ENV['JWT_KEY'];


$isMember = false;
if (isset($_SESSION["tokenJWT"])) {
    $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
    $CusID = $decoded->cusid;
    $userInfo = getUserInfoFromCusID($CusID);
    $isMember = true;
} else if (isset($_SESSION["tokenGoogle"])) {
    $userInfo = getGoogleUserInfo($_SESSION["tokenGoogle"]);
    $CusID = $userInfo['CusID'];
    $isMember = true;
}
$proIds = explode(',', $_POST['select-proID']);
$proIds = array_filter($proIds);

// var_dump($_POST['quantityHidden'])

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kodchasan:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- JQYERY THAILAND.js -->
    <script type="text/javascript" src="https://earthchie.github.io/jquery.Thailand.js/jquery.Thailand.js/dependencies/JQL.min.js"></script>
    <script type="text/javascript" src="https://earthchie.github.io/jquery.Thailand.js/jquery.Thailand.js/dependencies/typeahead.bundle.js"></script>
    <link rel="stylesheet" href="https://earthchie.github.io/jquery.Thailand.js/jquery.Thailand.js/dist/jquery.Thailand.min.css">
    <script type="text/javascript" src="https://earthchie.github.io/jquery.Thailand.js/jquery.Thailand.js/dist/jquery.Thailand.min.js"></script>

    <title>SMITI Shop:Summary Order</title>

    <style>
        * {
            font-family: 'Kodchasan';
            margin: 0px;
            padding: 0px;
        }

        input:focus,
        textarea:focus {
            outline-color: rgb(59 130 246);
        }
    </style>
</head>

<body>
    <form id="PaymentForm" method="POST">
        <?php
        $proIdsString = implode(',', $proIds);
        echo "<input type='hidden' name='ProIds' value='" .htmlspecialchars($proIdsString) . "'>";
        ?>
        <div class="px-28 py-5">
            <div class="flex flex-row border-2 rounded-lg py-5 bg-gray-50">
                <div class="flex flex-col w-full p-10">
                    <div class="mb-2">
                        <a href="Cart.php" class="hover:text-blue-800 text-blue-500 font-semibold"><i class='bx bx-arrow-back mr-2'></i>ย้อนกลับ</a>
                    </div>
                    <p class="font-bold text-2xl">Check out</p>

                    <!-- ข้อมูลผู้รับ -->
                    <div class="py-2 flex-col">
                        <p class="font-semibold text-lg">ข้อมูลผู้จ่าย</p>

                        <!-- ชื่อ-นามสกุล -->
                        <div class="flex flex-row">
                            <div class="py-2 w-full mr-3">
                                <p class="font-semibold mb-1">ชื่อจริง</p>
                                <?php
                                if ($isMember) {
                                    if ($userInfo["CusFName"] != "") {
                                        echo "<input id='PayerFNameBox' name='PayerFName' value='" . htmlspecialchars($userInfo["CusFName"]) . "' placeholder='กรุณากรอกชื่อจริง' type='text' class='border rounded-md h-10 w-full shadow-sm p-2'>";
                                    } else {
                                        echo "<input id='PayerFNameBox' name='PayerFName' placeholder='กรุณากรอกชื่อจริง' type='text' class='border rounded-md h-10 w-full shadow-sm p-2 text-md'>";
                                    }
                                } else {
                                    echo "<input id='PayerFNameBox' name='PayerFName' placeholder='กรุณากรอกชื่อจริง' type='text' class='border rounded-md h-10 w-full shadow-sm p-2 text-md'>";
                                }
                                ?>
                                <span id="PayerFNameError" class="text-red-500 text-sm h-2"></span>
                            </div>
                            <div class="py-2 w-full ml-3">
                                <p class="font-semibold mb-1">นามสกุล</p>
                                <?php
                                if ($isMember) {
                                    if ($userInfo["CusLName"] != "") {
                                        echo "<input id='PayerLNameBox' name='PayerLName' value='" . htmlspecialchars($userInfo["CusLName"]) . "' placeholder='กรุณากรอกนามสกุล' type='text' class='border rounded-md h-10 w-full shadow-sm p-2'>";
                                    } else {
                                        echo "<input id='PayerLNameBox' name='PayerLName' placeholder='กรุณากรอกนามสกุล' type='text' class='border rounded-md h-10 w-full shadow-sm p-2 text-md'>";
                                    }
                                } else {
                                    echo "<input id='PayerLNameBox' name='PayerLName' placeholder='กรุณากรอกนามสกุล' type='text' class='border rounded-md h-10 w-full shadow-sm p-2 text-md'>";
                                }
                                ?>
                                <span id="PayerLNameError" class="text-red-500 text-sm h-2"></span>
                            </div>
                        </div>

                        <!-- เบอร์โทร-เพศ -->
                        <div class="flex flex-row">
                            <div class="py-2 w-full mr-3">
                                <p class="font-semibold mb-1">เบอร์โทรศัพท์</p>
                                <?php
                                if ($isMember) {
                                    if ($userInfo["Tel"] != "") {
                                        echo "<input id='PayerTelBox' name='PayerTel' value='" . htmlspecialchars($userInfo["Tel"]) . "' placeholder='กรุณากรอกเบอร์โทรศัพท์' type='text' class='border rounded-md h-10 w-full shadow-sm p-2'>";
                                    } else {
                                        echo "<input id='PayerTelBox' name='PayerTel' placeholder='กรุณากรอกเบอร์โทรศัพท์' type='text' class='border rounded-md h-10 w-full shadow-sm p-2 text-md'>";
                                    }
                                } else {
                                    echo "<input id='PayerTelBox' name='PayerTel' placeholder='กรุณากรอกเบอร์โทรศัพท์' type='text' class='border rounded-md h-10 w-full shadow-sm p-2 text-md'>";
                                }
                                ?>
                                <span id="PayerTelError" class="text-red-500 text-sm h-2"></span>
                            </div>
                            <div class="py-2 w-full ml-3">
                                <p class="font-semibold mb-1">เพศ</p>
                                <div class=" mt-3">
                                    <?php
                                    if ($isMember) {
                                        if ($userInfo['Sex'] == 'M') {
                                            echo '<input type="radio" name="PayerSex" value="M" checked> ชาย
                                        <input type="radio" name="PayerSex" value="F"> หญิง
                                        <input type="radio" name="PayerSex" value="N"> ไม่ระบุ';
                                        } else if ($userInfo['Sex'] == 'F') {
                                            echo '<input type="radio" name="PayerSex" value="M"> ชาย
                                        <input type="radio" name="PayerSex" value="F" checked> หญิง
                                        <input type="radio" name="PayerSex" value="N"> ไม่ระบุ';
                                        } else {
                                            echo '<input type="radio" name="PayerSex" value="M"> ชาย
                                        <input type="radio" name="PayerSex" value="F"> หญิง
                                        <input type="radio" name="PayerSex" value="N" checked> ไม่ระบุ';
                                        }
                                    } else {
                                        echo '<input type="radio" name="PayerSex" value="M"> ชาย
                                        <input type="radio" name="PayerSex" value="F"> หญิง
                                        <input type="radio" name="PayerSex" value="N" checked> ไม่ระบุ';
                                    }
                                    ?>
                                </div>
                                <span id="PayerSexError" class="text-red-500 text-sm h-2"></span>
                            </div>
                        </div>

                        <!-- อีเมล -->
                        <div class="flex flex-row">
                            <div class="py-2 w-full">
                                <p class="font-semibold mb-1">อีเมล</p>
                                <?php
                                if ($isMember) {
                                    if ($userInfo["Email"] != "") {
                                        echo "<input id='PayerEmailBox' name='PayerEmail' value='" . htmlspecialchars($userInfo["Email"]) . "' type='text' placeholder='กรุณากรอกอีเมล' class='border rounded-md h-10 w-full shadow-sm p-2'>";
                                    } else {
                                        echo "<input id='PayerEmailBox' name='PayerEmail' placeholder='กรุณากรอกอีเมล' type='text' class='border rounded-md h-10 w-full shadow-sm p-2 text-md'>";
                                    }
                                } else {
                                    echo "<input id='PayerEmailBox' name='PayerEmail' placeholder='กรุณากรอกอีเมล' type='text' class='border rounded-md h-10 w-full shadow-sm p-2 text-md'>";
                                }
                                ?>
                                <span id="PayerEmailError" class="text-red-500 text-sm h-2"></span>
                            </div>
                        </div>

                        <!-- ที่อยู่ -->
                        <div class="flex flex-row">
                            <div class="py-2 w-full">
                                <p class="font-semibold mb-1">ที่อยู่</p>
                                <?php
                                if ($isMember) {
                                    if ($userInfo['Address'] != "") {
                                        echo '<textarea id="PayerAddrInput" name="PayerAddr" class="mt-1 w-full p-2 border rounded-md h-20 shadow-sm resize-none" placeholder="กรุณากรอกที่อยู่ผู้รับ" required>' . htmlspecialchars($userInfo['Address']) . '</textarea>';
                                    } else {
                                        echo '<textarea id="PayerAddrInput" name="PayerAddr" class="mt-1 w-full p-2 border rounded-md h-20 shadow-sm resize-none" placeholder="กรุณากรอกที่อยู่ผู้รับ" required></textarea>';
                                    }
                                } else {
                                    echo '<textarea id="PayerAddrInput" name="PayerAddr" class="mt-1 w-full p-2 border rounded-md h-20 shadow-sm resize-none" placeholder="กรุณากรอกที่อยู่ผู้รับ" required></textarea>';
                                }
                                ?>
                                <span id="PayerAddrError" class="text-red-500 text-sm h-2"></span>
                            </div>
                        </div>

                        <!-- จังหวัด-รหัสไปรษณีย์ -->
                        <div class="flex flex-row">
                            <div class="py-2 w-full mr-3">
                                <p class="font-semibold mb-1">จังหวัด</p>
                                <?php
                                if ($isMember) {
                                    if ($userInfo["Province"] != "") {
                                        echo "<input id='PayerProvinceBox' name='PayerProvince' value='" . htmlspecialchars($userInfo["Province"]) . "' placeholder='กรุณากรอกจังหวัด' type='text' class='border rounded-md h-10 w-full shadow-sm p-2'>";
                                    } else {
                                        echo "<input id='PayerProvinceBox' name='PayerProvince' placeholder='กรุณากรอกจังหวัด' type='text' class='border rounded-md h-10 w-full shadow-sm p-2 text-md'>";
                                    }
                                } else {
                                    echo "<input id='PayerProvinceBox' name='PayerProvince' placeholder='กรุณากรอกจังหวัด' type='text' class='border rounded-md h-10 w-full shadow-sm p-2 text-md'>";
                                }
                                ?>
                                <span id="PayerProvinceError" class="text-red-500 text-sm h-2"></span>
                            </div>
                            <div class="py-2 w-full ml-3">
                                <p class="font-semibold mb-1">รหัสไปรษณีย์</p>
                                <?php
                                if ($isMember) {
                                    if ($userInfo["Postcode"] != "") {
                                        echo "<input id='PayerPostcodeBox' name='PayerPostcode' value='" . htmlspecialchars($userInfo["Postcode"]) . "' placeholder='กรุณากรอกรหัสไปรษณีย์' type='text' class='border rounded-md h-10 w-full shadow-sm p-2'>";
                                    } else {
                                        echo "<input id='PayerPostcodeBox' name='PayerPostcode' placeholder='กรุณากรอกรหัสไปรษณีย์' type='text' class='border rounded-md h-10 w-full shadow-sm p-2 text-md'>";
                                    }
                                } else {
                                    echo "<input id='PayerPostcodeBox' name='PayerPostcode' placeholder='กรุณากรอกรหัสไปรษณีย์' type='text' class='border rounded-md h-10 w-full shadow-sm p-2 text-md'>";
                                }
                                ?>
                                <span id="PayerPostcodeError" class="text-red-500 text-sm h-2"></span>
                            </div>
                        </div>
                    </div>

                    <!-- ------------------------------------------------------------------------------------------------------------- -->

                    <div class="flex flex-row">
                        <div class="w-full mr-5">
                            <h1 class="font-semibold mt-2">ใบกำกับภาษี</h1>
                            <input name="taxInvoice" type="radio" value="No" checked> ไม่ต้องการ
                            <input name="taxInvoice" type="radio" value="Yes"> ต้องการ
                        </div>
                        <div id="taxIdDiv" class="w-full ml-5" style="display: none;">
                            <h1 class="font-semibold mt-2">เลขภาษีส่วนบุคคล*</h1>
                            <input id="taxIDBox" name="taxID" class="border rounded-md h-10 w-full shadow-sm p-2 text-md" type="text" placeholder="กรุณากรอกเลขกำกับภาษี" required>
                            <span id="taxIDError" class="text-red-500 text-sm h-2" style="display: none;">*กรุณากรอกเลขภาษีประจำตัว</span>
                        </div>
                    </div>
                    <span id="Check"></span>

                    <!-- ------------------------------------------------------------------------------------------------------------- -->

                    <hr class="border border-gray-300 border-5 my-10 rounded-md w-full" style="border-width: 1px;">

                    <!-- ------------------------------------------------------------------------------------------------------------- -->


                    <!-- ข้อมูลผู้ส่ง -->
                    <span id="sameAddrError" class="text-red-500 text-sm h-2 text-right" style="display: none;">*กรุณากรอกข้อมูลผู้ส่งให้ครบถ้วน</span>
                    <div class="py-2 flex-col">
                        <div class="flex justify-between">
                            <p class="font-semibold text-lg">ข้อมูลผู้รับ</p>
                            <div class="flex mt-1.5">
                                <?php ?>
                                <input type="checkbox" name="sameAddr" id="sameAddr" class="ml-5">
                                <p class="text-sm mt-1 ml-2">ใช้ข้อมูลผู้ส่ง</p>
                            </div>
                        </div>

                        <!-- ชื่อ-นามสกุล -->
                        <div class="flex flex-row">
                            <div class="py-2 w-full mr-3">
                                <p class="font-semibold mb-1">ชื่อจริง</p>
                                <?php
                                if ($isMember) {
                                    echo "<input id='RecvFNameBox' name='RecvFName' placeholder='กรุณากรอกชื่อจริง' type='text' class='border rounded-md h-10 w-full shadow-sm p-2 text-md'>";
                                } else {
                                    echo "<input id='RecvFNameBox' name='RecvFName' placeholder='กรุณากรอกชื่อจริง' type='text' class='border rounded-md h-10 w-full shadow-sm p-2 text-md'>";
                                }
                                ?>
                                <span id="RecvFNameError" class="text-red-500 text-sm h-2"></span>
                            </div>
                            <div class="py-2 w-full ml-3">
                                <p class="font-semibold mb-1">นามสกุล</p>
                                <?php
                                if ($isMember) {
                                    echo "<input id='RecvLNameBox' name='RecvLName' placeholder='กรุณากรอกนามสกุล' type='text' class='border rounded-md h-10 w-full shadow-sm p-2 text-md'>";
                                } else {
                                    echo "<input id='RecvLNameBox' name='RecvLName' placeholder='กรุณากรอกนามสกุล' type='text' class='border rounded-md h-10 w-full shadow-sm p-2 text-md'>";
                                }
                                ?>
                                <span id="RecvLNameError" class="text-red-500 text-sm h-2"></span>
                            </div>
                        </div>

                        <!-- เบอร์โทร-เพศ -->
                        <div class="flex flex-row">
                            <div class="py-2 w-full mr-3">
                                <p class="font-semibold mb-1">เบอร์โทรศัพท์</p>
                                <?php
                                if ($isMember) {
                                    echo "<input id='RecvTelBox' name='RecvTel' placeholder='กรุณากรอกเบอร์โทรศัพท์' type='text' class='border rounded-md h-10 w-full shadow-sm p-2 text-md'>";
                                } else {
                                    echo "<input id='RecvTelBox' name='RecvTel' placeholder='กรุณากรอกเบอร์โทรศัพท์' type='text' class='border rounded-md h-10 w-full shadow-sm p-2 text-md'>";
                                }
                                ?>
                                <span id="RecvTelError" class="text-red-500 text-sm h-2"></span>
                            </div>
                            <div class="py-2 w-full ml-3">
                                <p class="font-semibold mb-1">เพศ</p>
                                <div class=" mt-3">
                                    <?php
                                    if ($isMember) {
                                        echo '<input type="radio" name="RecvSex" value="M"> ชาย
                                        <input type="radio" name="RecvSex" value="F"> หญิง
                                        <input type="radio" name="RecvSex" value="N" checked> ไม่ระบุ';
                                    } else {
                                        echo '<input type="radio" name="RecvSex" value="M"> ชาย
                                        <input type="radio" name="RecvSex" value="F"> หญิง
                                        <input type="radio" name="RecvSex" value="N" checked> ไม่ระบุ';
                                    }
                                    ?>
                                </div>
                                <span id="RecvSexError" class="text-red-500 text-sm h-2"></span>
                            </div>
                        </div>

                        <!-- อีเมล -->
                        <div class="flex flex-row">
                            <div class="py-2 w-full">
                                <p class="font-semibold mb-1">อีเมล</p>
                                <?php
                                if ($isMember) {
                                    echo "<input id='RecvEmailBox' name='RecvEmail' placeholder='กรุณากรอกอีเมล' type='text' class='border rounded-md h-10 w-full shadow-sm p-2 text-md'>";
                                } else {
                                    echo "<input id='RecvEmailBox' name='RecvEmail' placeholder='กรุณากรอกอีเมล' type='text' class='border rounded-md h-10 w-full shadow-sm p-2 text-md'>";
                                }
                                ?>
                                <span id="RecvEmailError" class="text-red-500 text-sm h-2"></span>
                            </div>
                        </div>

                        <!-- ที่อยู่ -->
                        <div class="flex flex-row">
                            <div class="py-2 w-full">
                                <p class="font-semibold mb-1">ที่อยู่</p>
                                <?php
                                if ($isMember) {
                                    echo '<textarea id="RecvAddrInput" name="RecvAddr" class="mt-1 w-full p-2 border rounded-md h-20 shadow-sm resize-none" placeholder="กรุณากรอกที่อยู่ผู้รับ" required></textarea>';
                                } else {
                                    echo '<textarea id="RecvAddrInput" name="RecvAddr" class="mt-1 w-full p-2 border rounded-md h-20 shadow-sm resize-none" placeholder="กรุณากรอกที่อยู่ผู้รับ" required></textarea>';
                                }
                                ?>
                                <span id="RecvAddrError" class="text-red-500 text-sm h-2"></span>
                            </div>
                        </div>

                        <!-- จังหวัด-รหัสไปรษณีย์ -->
                        <div class="flex flex-row">
                            <div class="py-2 w-full mr-3">
                                <p class="font-semibold mb-1">จังหวัด</p>
                                <?php
                                if ($isMember) {
                                    echo "<input id='RecvProvinceBox' name='RecvProvince' placeholder='กรุณากรอกจังหวัด' type='text' class='border rounded-md h-10 w-full shadow-sm p-2 text-md'>";
                                } else {
                                    echo "<input id='RecvProvinceBox' name='RecvProvince' placeholder='กรุณากรอกจังหวัด' type='text' class='border rounded-md h-10 w-full shadow-sm p-2 text-md'>";
                                }
                                ?>
                                <span id="RecvProvinceError" class="text-red-500 text-sm h-2"></span>
                            </div>
                            <div class="py-2 w-full ml-3">
                                <p class="font-semibold mb-1">รหัสไปรษณีย์</p>
                                <?php
                                if ($isMember) {
                                    echo "<input id='RecvPostcodeBox' name='RecvPostcode' placeholder='กรุณากรอกรหัสไปรษณีย์' type='text' class='border rounded-md h-10 w-full shadow-sm p-2 text-md'>";
                                } else {
                                    echo "<input id='RecvPostcodeBox' name='RecvPostcode' placeholder='กรุณากรอกรหัสไปรษณีย์' type='text' class='border rounded-md h-10 w-full shadow-sm p-2 text-md'>";
                                }
                                ?>
                                <span id="RecvPostcodeError" class="text-red-500 text-sm h-2"></span>
                            </div>
                        </div>
                    </div>

                    <!-- ------------------------------------------------------------------------------------------------------------- -->

                    <hr class="border border-gray-300 border-5 my-10 rounded-md w-full" style="border-width: 1px;">

                    <!-- ------------------------------------------------------------------------------------------------------------- -->

                    <div class="py-2 flex-col">
                        <div class="flex flex-row justify-between">
                            <p class="font-semibold text-lg">ช่องทางการชำระเงิน</p>
                            <span id="PaymentMethodError" class="text-red-500 text-sm h-2 mt-1" style="display: none">*กรุณาเลือกช่องทางการชำระเงิน</span>
                        </div>
                        <input type="hidden" name="PaymentMethod">
                        <div class="flex flex-col my-2">
                            <div id="qrBox" class="rounded-lg p-5 bg-white border w-full my-1 relative">
                                <p class="text-md">ชำระผ่าน QR Code</p>
                                <i id="qrCheckIcon" class='bx bxs-check-circle absolute right-3 top-3 text-2xl text-blue-600' style="display: none;"></i>
                            </div>
                            <div id="codBox" class="rounded-lg p-5 bg-white border w-full my-1 relative">
                                <p class="text-md">ชำระผ่านเงินปลายทาง</p>
                                <i id="codCheckIcon" class='bx bxs-check-circle absolute right-3 top-3 text-2xl text-blue-600' style="display: none;"></i>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="w-full p-10 mt-10">
                    <p class="font-bold text-xl mb-2">รายการสินค้า</p>
                    <div class="flex flex-col border bg-white rounded-md p-5">
                        <?php
                        $totalPrice = 0;
                        if ($isMember) {
                            foreach ($proIds as $proId) {
                                $row = showCartSession($proId)->fetch_assoc();
                                if (isset($quantityHidden)) {
                                    $qty['Qty'] = $quantityHidden;
                                } else {
                                    $qty = getQtyFromCart($CusID)->fetch_assoc();
                                }
                                // $qty = getQtyFromCart($CusID, $proId)->fetch_assoc();
                                $totalPrice += $row['PricePerUnit'] * $qty['Qty'];
                                echo "<div class='flex flex-row mb-5 w-full h-34'>";
                                echo "<div class=''>";
                                echo "<img src='{$row['ImageSource']}' class='w-24 h-32 objec-fill'>";
                                echo "</div>";
                                echo "<div class='ml-3 w-3/4 flex flex-col'>";
                                echo "<p class='font-semibold text-xl'>" . htmlspecialchars($row['ProName']) . "</p>";
                                echo "<p class=' text-sm'>จำนวน " . htmlspecialchars($qty['Qty']) . " เล่ม</p>";
                                echo "<div class='flex flex-col justify-end h-full'>";
                                echo "<p class='text-sm font-semibold'>ราคา " . htmlspecialchars($row['PricePerUnit']) . " บาท</p>";
                                echo "</div>";
                                echo "</div>";
                                echo "</div>";
                                echo "<hr class='border border-gray-300 border-5 mb-5 rounded-md w-full' style='border-width: 1px;'>";
                            }
                        } else {
                            foreach ($proIds as $proId) {
                                $row = showCartSession($proId)->fetch_assoc();
                                $qty = $_SESSION['cart'][$proId];
                                $totalPrice += $row['PricePerUnit'] * $qty;
                                echo "<div class='flex flex-row mb-5 w-full h-34'>";
                                echo "<div class=''>";
                                echo "<img src='{$row['ImageSource']}' class='w-24 h-32 objec-fill'>";
                                echo "</div>";
                                echo "<div class='ml-3 w-3/4 flex flex-col'>";
                                echo "<p class='font-semibold text-xl'>{$row['ProName']}</p>";
                                echo "<p class=' text-sm'>จำนวน {$qty} เล่ม</p>";
                                echo "<div class='flex flex-col justify-end h-full'>";
                                echo "<p class='text-sm font-semibold'>ราคา {$row['PricePerUnit']} บาท</p>";
                                echo "</div>";
                                echo "</div>";
                                echo "</div>";
                                echo "<hr class='border border-gray-300 border-5 mb-5 rounded-md w-full' style='border-width: 1px;'>";
                            }
                        }
                        ?>
                        <div class="flex flex-col">
                            <div class="flex justify-between mb-5">
                                <?php
                                $totalPriceFormat = number_format($totalPrice, 2);
                                ?>
                                <p class="font-semibold text-md">ราคารวม: </p>
                                <p class="font-md text-md"><?php echo htmlspecialchars($totalPriceFormat); ?> บาท</p>
                                <input type="hidden" name="TotalPrice" value="<?php echo htmlspecialchars($totalPrice) ?>">
                            </div>
                            <div class="flex justify-between">
                                <?php
                                $vat = $totalPrice * 0.07;
                                $vatFormat = number_format($vat, 2);
                                ?>
                                <p class="font-semibold text-md">Vat 7%: </p>
                                <p class="font-md text-md"><?php echo htmlspecialchars($vatFormat); ?> บาท</p>
                            </div>
                        </div>

                        <hr class='border border-gray-300 my-6 rounded-md w-full' style='border-width: 0.1px;'>

                        <div class="flex justify-between">
                            <?php
                            $totalPrice = $totalPrice + $vat;
                            $totalPriceFormat = number_format($totalPrice, 2);
                            ?>
                            <p class="font-semibold text-xl">ราคาสุทธิ: </p>
                            <p class="font-md text-md"><?php echo htmlspecialchars($totalPriceFormat); ?> บาท</p>
                        </div>

                        <hr class='border border-gray-300 my-6 rounded-md w-full' style='border-width: 0.1px;'>

                        <div>
                            <button onclick="submitForm()" type="button" class="w-full mt-5 h-12 bg-blue-800 rounded-md font-bold text-white hover:shadow-md hover:bg-blue-900">ยืนยันการสั่งซื้อ</button>
                        </div>
                    </div>
                    <!-- <p id="CheckStatus"></p> -->
                    <!-- <p id="CheckSubmit"></p> -->
                </div>
            </div>
        </div>
    </form>

    <script>
        //define receiverBox element id
        var RecvFNameBox = document.getElementById('RecvFNameBox');
        var RecvLNameBox = document.getElementById('RecvLNameBox');
        var RecvTelBox = document.getElementById('RecvTelBox');
        var RecvSexBox = document.getElementsByName('RecvSex');
        var RecvEmailBox = document.getElementById('RecvEmailBox');
        var RecvAddrInput = document.getElementById('RecvAddrInput');
        var RecvProvinceBox = document.getElementById('RecvProvinceBox');
        var RecvPostcodeBox = document.getElementById('RecvPostcodeBox');

        //define receiverError element id
        var RecvFNameError = document.getElementById('RecvFNameError');
        var RecvLNameError = document.getElementById('RecvLNameError');
        var RecvTelError = document.getElementById('RecvTelError');
        var RecvEmailError = document.getElementById('RecvEmailError');
        var RecvAddrError = document.getElementById('RecvAddrError');
        var RecvProvinceError = document.getElementById('RecvProvinceError');
        var RecvPostcodeError = document.getElementById('RecvPostcodeError');

        //define payer element id
        var PayerFNameBox = document.getElementById('PayerFNameBox');
        var PayerLNameBox = document.getElementById('PayerLNameBox');
        var PayerTelBox = document.getElementById('PayerTelBox');
        var PayerSexBox = document.getElementsByName('PayerSex');
        var PayerEmailBox = document.getElementById('PayerEmailBox');
        var PayerAddrInput = document.getElementById('PayerAddrInput');
        var PayerProvinceBox = document.getElementById('PayerProvinceBox');
        var PayerPostcodeBox = document.getElementById('PayerPostcodeBox');

        //define payerError element id
        var PayerFNameError = document.getElementById('PayerFNameError');
        var PayerLNameError = document.getElementById('PayerLNameError');
        var PayerTelError = document.getElementById('PayerTelError');
        var PayerEmailError = document.getElementById('PayerEmailError');
        var PayerAddrError = document.getElementById('PayerAddrError');
        var PayerProvinceError = document.getElementById('PayerProvinceError');
        var PayerPostcodeError = document.getElementById('PayerPostcodeError');

        //define taxID element id
        var taxID = document.getElementById('taxIdDiv');
        var taxIDBox = document.getElementById('taxIDBox');
        var taxInvoice = document.getElementsByName('taxInvoice');
        var taxIDError = document.getElementById('taxIDError');
        var taxIDCheck = false;
        var taxIDStatus = false;

        //define payment method 
        var qrBox = document.getElementById('qrBox');
        var codBox = document.getElementById('codBox');
        var PaymentMethod = document.getElementsByName('PaymentMethod');
        var PaymentMethodError = document.getElementById('PaymentMethodError');
        var PaymentMethodStatus = false;
        var PaymentMethodCheckValue = ""

        //define same address checkbox
        var sameAddr = document.getElementById('sameAddr');
        var sameAddrError = document.getElementById('sameAddrError');
        var sameAddrStatus = false;
        var fillAllPayerBox = false;

        //define recv status each inputBox
        var RecvFNameStatus = false;
        var RecvLNameStatus = false;
        var RecvTelStatus = false;
        var RecvEmailStatus = false;
        var RecvAddrStatus = false;
        var RecvProvinceStatus = false;
        var RecvPostcodeStatus = false;

        //define payer status each inputBox
        var PayerFNameStatus = false;
        var PayerLNameStatus = false;
        var PayerTelStatus = false;
        var PayerEmailStatus = false;
        var PayerAddrStatus = false;
        var PayerProvinceStatus = false;
        var PayerPostcodeStatus = false;

        //Form Check Format
        function formCheck() {
            //Check each input box
            //check payer Fname
            if (PayerFNameBox.value == "") {
                PayerFNameError.innerHTML = "*กรุณากรอกชื่อจริง";
                PayerFNameError.style.display = "block";
                PayerFNameBox.style.borderColor = "red";
                PayerFNameStatus = false;
            } else {
                PayerFNameError.style.display = "none";
                PayerFNameBox.style.borderColor = "rgb(229 231 235)";
                PayerFNameStatus = true;
            }

            //check payer Lname
            if (PayerLNameBox.value == "") {
                PayerLNameError.innerHTML = "*กรุณากรอกนามสกุล";
                PayerLNameError.style.display = "block";
                PayerLNameBox.style.borderColor = "red";
                PayerLNameStatus = false;
            } else {
                PayerLNameError.style.display = "none";
                PayerLNameBox.style.borderColor = "rgb(229 231 235)";
                PayerLNameStatus = true;
            }

            //check payer Tel
            if (PayerTelBox.value == "") {
                PayerTelError.innerHTML = "*กรุณากรอกเบอร์โทรศัพท์";
                PayerTelError.style.display = "block";
                PayerTelBox.style.borderColor = "red";
                PayerTelStatus = false;
            } else if (isNaN(PayerTelBox.value)) {
                PayerTelError.innerHTML = "*กรุณากรอกแค่ตัวเลขเท่านั้น";
                PayerTelError.style.display = "block";
                PayerTelBox.style.borderColor = "red";
                PayerTelStatus = false;
            } else if (PayerTelBox.value.length != 10) {
                PayerTelError.innerHTML = "*กรุณากรอกเบอร์โทรศัพท์ให้ครบ 10 หลัก";
                PayerTelError.style.display = "block";
                PayerTelBox.style.borderColor = "red";
                PayerTelStatus = false;
            } else {
                PayerTelError.style.display = "none";
                PayerTelBox.style.borderColor = "rgb(229 231 235)";
                PayerTelStatus = true;
            }

            //check payer Email
            if (PayerEmailBox.value == "") {
                PayerEmailError.innerHTML = "*กรุณากรอกอีเมล";
                PayerEmailError.style.display = "block";
                PayerEmailBox.style.borderColor = "red";
                PayerEmailStatus = false;
            } else if (!/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/.test(PayerEmailBox.value)) {
                PayerEmailError.innerHTML = "*กรุณากรอกรูปแบบอีเมลให้ถูกต้อง";
                PayerEmailError.style.display = "block";
                PayerEmailBox.style.borderColor = "red";
                PayerEmailStatus = false;
            } else {
                PayerEmailError.style.display = "none";
                PayerEmailBox.style.borderColor = "rgb(229 231 235)";
                PayerEmailStatus = true;
            }

            //check payer Address
            if (PayerAddrInput.value == "") {
                PayerAddrError.innerHTML = "*กรุณากรอกที่อยู่";
                PayerAddrError.style.display = "block";
                PayerAddrInput.style.borderColor = "red";
                PayerAddrStatus = false;
            } else {
                PayerAddrError.style.display = "none";
                PayerAddrInput.style.borderColor = "rgb(229 231 235)";
                PayerAddrStatus = true;
            }

            //check payer Province
            if (PayerProvinceBox.value == "") {
                PayerProvinceError.innerHTML = "*กรุณากรอกจังหวัด";
                PayerProvinceError.style.display = "block";
                PayerProvinceBox.style.borderColor = "red";
                PayerProvinceStatus = false;
            } else {
                PayerProvinceError.style.display = "none";
                PayerProvinceBox.style.borderColor = "rgb(229 231 235)";
                PayerProvinceStatus = true;
            }

            //check payer Postcode
            if (PayerPostcodeBox.value == "") {
                PayerPostcodeError.innerHTML = "*กรุณากรอกรหัสไปรษณีย์";
                PayerPostcodeError.style.display = "block";
                PayerPostcodeBox.style.borderColor = "red";
                PayerPostcodeStatus = false;
            } else if (isNaN(PayerPostcodeBox.value)) {
                PayerPostcodeError.innerHTML = "*กรุณากรอกแค่ตัวเลขเท่านั้น";
                PayerPostcodeError.style.display = "block";
                PayerPostcodeBox.style.borderColor = "red";
                PayerPostcodeStatus = false;
            } else if (PayerPostcodeBox.value.length != 5) {
                PayerPostcodeError.innerHTML = "*กรุณากรอกรหัสไปรษณีย์ให้ครบ 5 หลัก";
                PayerPostcodeError.style.display = "block";
                PayerPostcodeBox.style.borderColor = "red";
                PayerPostcodeStatus = false;
            } else {
                PayerPostcodeError.style.display = "none";
                PayerPostcodeBox.style.borderColor = "rgb(229 231 235)";
                PayerPostcodeStatus = true;
            }

            //check fill all payer box status
            if (PayerFNameStatus && PayerLNameStatus && PayerTelStatus && PayerEmailStatus && PayerAddrStatus && PayerProvinceStatus && PayerPostcodeStatus && sameAddrStatus) {
                fillAllPayerBox = true;
            } else {
                fillAllPayerBox = false;
            }

            if (sameAddrStatus != true) {
                //Check recv Fname
                if (RecvFNameBox.value == "") {
                    RecvFNameError.innerHTML = "*กรุณากรอกชื่อจริง";
                    RecvFNameError.style.display = "block";
                    RecvFNameBox.style.borderColor = "red";
                    RecvFNameStatus = false;
                } else {
                    RecvFNameError.style.display = "none";
                    RecvFNameBox.style.borderColor = "rgb(229 231 235)";
                    RecvFNameStatus = true;
                }
                //Check recv Lname
                if (RecvLNameBox.value == "") {
                    RecvLNameError.innerHTML = "*กรุณากรอกนามสกุล";
                    RecvLNameError.style.display = "block";
                    RecvLNameBox.style.borderColor = "red";
                    RecvLNameStatus = false;
                } else {
                    RecvLNameError.style.display = "none";
                    RecvLNameBox.style.borderColor = "rgb(229 231 235)";
                    RecvLNameStatus = true;
                }
                //Check recv Tel
                if (RecvTelBox.value == "") {
                    RecvTelError.innerHTML = "*กรุณากรอกเบอร์โทรศัพท์";
                    RecvTelError.style.display = "block";
                    RecvTelBox.style.borderColor = "red";
                    RecvTelStatus = false;
                } else if (isNaN(RecvTelBox.value)) {
                    RecvTelError.innerHTML = "*กรุณากรอกแค่ตัวเลขเท่านั้น";
                    RecvTelError.style.display = "block";
                    RecvTelBox.style.borderColor = "red";
                    RecvTelStatus = false;
                } else if (RecvTelBox.value.length != 10) {
                    RecvTelError.innerHTML = "*กรุณากรอกเบอร์โทรศัพท์ให้ครบ 10 หลัก";
                    RecvTelError.style.display = "block";
                    RecvTelBox.style.borderColor = "red";
                    RecvTelStatus = false;
                } else {
                    RecvTelError.style.display = "none";
                    RecvTelBox.style.borderColor = "rgb(229 231 235)";
                    RecvTelStatus = true;
                }
                //Check recv Email
                if (RecvEmailBox.value == "") {
                    RecvEmailError.innerHTML = "*กรุณากรอกอีเมล";
                    RecvEmailError.style.display = "block";
                    RecvEmailBox.style.borderColor = "red";
                    RecvEmailStatus = false;
                } else if (!/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/.test(RecvEmailBox.value)) {
                    RecvEmailError.innerHTML = "*กรุณากรอกรูปแบบอีเมลให้ถูกต้อง";
                    RecvEmailError.style.display = "block";
                    RecvEmailBox.style.borderColor = "red";
                    RecvEmailStatus = false;
                } else {
                    RecvEmailError.style.display = "none";
                    RecvEmailBox.style.borderColor = "rgb(229 231 235)";
                    RecvEmailStatus = true;
                }

                //Check recv Address
                if (RecvAddrInput.value == "") {
                    RecvAddrError.innerHTML = "*กรุณากรอกที่อยู่";
                    RecvAddrError.style.display = "block";
                    RecvAddrInput.style.borderColor = "red";
                    RecvAddrStatus = false;
                } else {
                    RecvAddrError.style.display = "none";
                    RecvAddrInput.style.borderColor = "rgb(229 231 235)";
                    RecvAddrStatus = true;
                }

                //Check recv Province
                if (RecvProvinceBox.value == "") {
                    RecvProvinceError.innerHTML = "*กรุณากรอกจังหวัด";
                    RecvProvinceError.style.display = "block";
                    RecvProvinceBox.style.borderColor = "red";
                    RecvProvinceStatus = false;
                } else {
                    RecvProvinceError.style.display = "none";
                    RecvProvinceBox.style.borderColor = "rgb(229 231 235)";
                    RecvProvinceStatus = true;
                }

                //Check recv Postcode
                if (RecvPostcodeBox.value == "") {
                    RecvPostcodeError.innerHTML = "*กรุณากรอกรหัสไปรษณีย์";
                    RecvPostcodeError.style.display = "block";
                    RecvPostcodeBox.style.borderColor = "red";
                    RecvPostcodeStatus = false;
                } else if (isNaN(RecvPostcodeBox.value)) {
                    RecvPostcodeError.innerHTML = "*กรุณากรอกแค่ตัวเลขเท่านั้น";
                    RecvPostcodeError.style.display = "block";
                    RecvPostcodeBox.style.borderColor = "red";
                    RecvPostcodeStatus = false;
                } else if (RecvPostcodeBox.value.length != 5) {
                    RecvPostcodeError.innerHTML = "*กรุณากรอกรหัสไปรษณีย์ให้ครบ 5 หลัก";
                    RecvPostcodeError.style.display = "block";
                    RecvPostcodeBox.style.borderColor = "red";
                    RecvPostcodeStatus = false;
                } else {
                    RecvPostcodeError.style.display = "none";
                    RecvPostcodeBox.style.borderColor = "rgb(229 231 235)";
                    RecvPostcodeStatus = true;
                }

            } else {
                RecvFNameStatus = true;
                RecvLNameStatus = true;
                RecvTelStatus = true;
                RecvEmailStatus = true;
                RecvAddrStatus = true;
                RecvProvinceStatus = true;
                RecvPostcodeStatus = true;
                RecvFNameBox.style.borderColor = "rgb(229 231 235)";
                RecvFNameError.style.display = "none";
                RecvLNameBox.style.borderColor = "rgb(229 231 235)";
                RecvLNameError.style.display = "none";
                RecvTelBox.style.borderColor = "rgb(229 231 235)";
                RecvTelError.style.display = "none";
                RecvEmailBox.style.borderColor = "rgb(229 231 235)";
                RecvEmailError.style.display = "none";
                RecvAddrInput.style.borderColor = "rgb(229 231 235)";
                RecvAddrError.style.display = "none";
                RecvProvinceBox.style.borderColor = "rgb(229 231 235)";
                RecvProvinceError.style.display = "none";
                RecvPostcodeBox.style.borderColor = "rgb(229 231 235)";
                RecvPostcodeError.style.display = "none";
            }

            if (PaymentMethodCheckValue == "") {
                PaymentMethodError.style.display = "block";
                PaymentMethodStatus = false;
            } else if (PaymentMethodCheckValue == "Transfer") {
                PaymentMethodError.style.display = "none";
                PaymentMethodStatus = true;
            } else if (PaymentMethodCheckValue == "COD") {
                PaymentMethodError.style.display = "none";
                PaymentMethodStatus = true;
            }

            if (taxIDCheck) {
                checkTaxID();
            }


            /* -------------------------------------- For Debug --------------------------------------*/
            //taxShow show all status for debug
            // document.getElementById('CheckStatus').innerHTML = "RecvFNameStatus: " + RecvFNameStatus + "<br>" +
            //     "RecvLNameStatus: " + RecvLNameStatus + "<br>" +
            //     "RecvTelStatus: " + RecvTelStatus + "<br>" +
            //     "RecvEmailStatus: " + RecvEmailStatus + "<br>" +
            //     "RecvAddrStatus: " + RecvAddrStatus + "<br>" +
            //     "RecvProvinceStatus: " + RecvProvinceStatus + "<br>" +
            //     "RecvPostcodeStatus: " + RecvPostcodeStatus + "<br>" +
            //     "PayerFNameStatus: " + PayerFNameStatus + "<br>" +
            //     "PayerLNameStatus: " + PayerLNameStatus + "<br>" +
            //     "PayerTelStatus: " + PayerTelStatus + "<br>" +
            //     "PayerEmailStatus: " + PayerEmailStatus + "<br>" +
            //     "PayerAddrStatus: " + PayerAddrStatus + "<br>" +
            //     "PayerProvinceStatus: " + PayerProvinceStatus + "<br>" +
            //     "PayerPostcodeStatus: " + PayerPostcodeStatus + "<br>" +
            //     "PaymentMethodStatus: " + PaymentMethodStatus + "<br>" +
            //     "taxIDStatus: " + taxIDStatus + "<br>" +
            //     "fillAllPayerBox: " + fillAllPayerBox + "<br>" +
            //     "sameAddrStatus: " + sameAddrStatus + "<br>" + 
            //     "paymentMethodCheckValue: " + PaymentMethod.value + "<br>";

            // show all Recv value
            // document.getElementById('CheckSubmit').innerHTML = "RecvFName: " + RecvFNameBox.value + "<br>" +
            //     "RecvLName: " + RecvLNameBox.value + "<br>" +
            //     "RecvTel: " + RecvTelBox.value + "<br>" +
            //     "RecvEmail: " + RecvEmailBox.value + "<br>" +
            //     "RecvAddr: " + RecvAddrInput.value + "<br>" +
            //     "RecvProvince: " + RecvProvinceBox.value + "<br>" +
            //     "RecvPostcode: " + RecvPostcodeBox.value + "<br>";

        }

        function submitForm() {
            formCheck();
            if (taxIDCheck) {
                if (RecvFNameStatus && RecvLNameStatus && RecvTelStatus && RecvEmailStatus && RecvAddrStatus &&
                    RecvProvinceStatus && RecvPostcodeStatus && PayerFNameStatus && PayerLNameStatus && PayerTelStatus &&
                    PayerEmailStatus && PayerAddrStatus && PayerProvinceStatus && PayerPostcodeStatus && taxIDStatus && PaymentMethodStatus) {
                    console.log("tax condition and all field pass");
                    document.getElementById('PaymentForm').action = '../../Backend/OrderManage/OrderController.php';
                    document.getElementById('PaymentForm').submit();
                }
            } else {
                if (RecvFNameStatus && RecvLNameStatus && RecvTelStatus && RecvEmailStatus && RecvAddrStatus &&
                    RecvProvinceStatus && RecvPostcodeStatus && PayerFNameStatus && PayerLNameStatus && PayerTelStatus &&
                    PayerEmailStatus && PayerAddrStatus && PayerProvinceStatus && PayerPostcodeStatus && PaymentMethodStatus) {
                    console.log("all field pass");
                    document.getElementById('PaymentForm').action = '../../Backend/OrderManage/OrderController.php';
                    document.getElementById('PaymentForm').submit();
                }
            }

        }

        //Same address script as when user checked this box ajax will send data to payer input box
        sameAddr.addEventListener('change', () => {
            if (sameAddr.checked) {
                sameAddrStatus = true;
                formCheck();
                if (fillAllPayerBox) {
                    sameAddrError.style.display = "none";
                    RecvFNameBox.value = PayerFNameBox.value;
                    RecvFNameBox.readOnly = true;
                    RecvLNameBox.value = PayerLNameBox.value;
                    RecvLNameBox.readOnly = true;
                    RecvSexBox.value = PayerSexBox.value;
                    for (var i = 0; i < PayerSexBox.length; i++) {
                        if (PayerSexBox[i].checked) {
                            RecvSexBox[i].checked = true;
                            break;
                        }
                    }
                    RecvTelBox.value = PayerTelBox.value;
                    RecvTelBox.readOnly = true;
                    RecvEmailBox.value = PayerEmailBox.value;
                    RecvEmailBox.readOnly = true;
                    RecvAddrInput.value = PayerAddrInput.value
                    RecvAddrInput.readOnly = true;
                    RecvProvinceBox.value = PayerProvinceBox.value;
                    RecvProvinceBox.readOnly = true;
                    RecvPostcodeBox.value = PayerPostcodeBox.value;
                    RecvPostcodeBox.readOnly = true;
                    // formCheck();
                } else {
                    sameAddr.checked = false;
                    sameAddrStatus = false;
                    sameAddrError.style.display = "block";
                }
            } else {
                //reset payer input box
                sameAddr.checked = false;
                sameAddrStatus = false;
                sameAddrError.style.display = "none";
                RecvFNameBox.value = "";
                RecvFNameBox.readOnly = false;
                RecvLNameBox.value = "";
                RecvLNameBox.readOnly = false;
                RecvTelBox.value = "";
                RecvTelBox.readOnly = false;
                RecvEmailBox.value = "";
                RecvEmailBox.readOnly = false;
                RecvAddrInput.value = "";
                RecvAddrInput.readOnly = false;
                RecvProvinceBox.value = "";
                RecvProvinceBox.readOnly = false;
                RecvPostcodeBox.value = "";
                RecvPostcodeBox.readOnly = false;
            }
        });

        //function for check taxID input box when user checked taxInvoice box
        function checkTaxID() {
            if (taxIDBox.value == "") {
                taxIDBox.style.borderColor = "red";
                taxIDError.style.display = "block";
                taxIDStatus = false;
            }
            //check 13 หลัก
            else if (taxIDBox.value.length != 13) {
                taxIDBox.style.borderColor = "red";
                taxIDError.innerHTML = "*กรุณากรอกเลขภาษีประจำตัวให้ครบ 13 หลัก";
                taxIDError.style.display = "block";
                taxIDStatus = false;
            }
            //check only number
            else if (isNaN(taxIDBox.value)) {
                taxIDBox.style.borderColor = "red";
                taxIDError.innerHTML = "*กรุณากรอกเลขภาษีประจำตัวเป็นตัวเลขเท่านั้น";
                taxIDError.style.display = "block";
                taxIDStatus = false;
            } else {
                taxIDBox.style.borderColor = "rgb(229 231 235)";
                taxIDError.style.display = "none";
                taxIDStatus = true;
            }
        }

        //Tax Invoice script as when user checked this box taxID input box will show
        for (let i = 0; i < taxInvoice.length; i++) {
            taxInvoice[i].addEventListener('change', function() {
                if (this.value === "Yes") {
                    taxID.style.display = 'block';
                    taxIDCheck = true;
                } else {
                    taxID.style.display = 'none';
                    taxIDCheck = false;
                    taxIDStatus = false;
                    taxIDBox.value = "";
                }
            });
        }

        //Payment method script as when user checked this box payment method will show
        qrBox.addEventListener('click', () => {
            qrBox.style.borderColor = "rgb(37 99 235)";
            qrBox.style.borderWidth = "2px";
            qrCheckIcon.style.display = "block";
            codBox.style.borderColor = "rgb(229 231 235)";
            codCheckIcon.style.display = "none";
            PaymentMethodCheckValue = "Transfer";
            document.querySelector('input[name="PaymentMethod"]').value = PaymentMethodCheckValue; // กำหนดค่าให้กับ input แบบ hidden
        });

        codBox.addEventListener('click', () => {
            codBox.style.borderColor = "rgb(37 99 235)";
            codBox.style.borderWidth = "2px";
            codCheckIcon.style.display = "block";
            qrBox.style.borderColor = "rgb(229 231 235)";
            qrCheckIcon.style.display = "none";
            PaymentMethodCheckValue = "COD";
            document.querySelector('input[name="PaymentMethod"]').value = PaymentMethodCheckValue; // กำหนดค่าให้กับ input แบบ hidden
        });

        //auto fill address
        // $.Thailand({
        //     autocomplete_size: 5,
        //     // $district: $('#district'),
        //     // $amphoe: $('#subdistrict'),
        //     // $province: $('#PayerProvince'),
        //     // $zipcode: $('#PayerPostcode'),
        // });
    </script>
</body>

</html>