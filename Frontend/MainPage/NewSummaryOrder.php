<?php
require_once "../../Backend/Authorized/UserAuthorized.php";
require_once "../../Backend/Authorized/ManageHeader.php";
require_once "../../Backend/CartQuery/CartDetail.php";
require_once "../../Backend/UserManage/UserInfo.php";
require '../../vendor/autoload.php';

use Firebase\JWT\Key;
use \Firebase\JWT\JWT;

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

    <title>SMITI Shop:Summary Order</title>

    <style>
        * {
            font-family: 'Kodchasan';
            margin: 0px;
            padding: 0px;
        }

        input:focus {
            outline-color: rgb(59 130 246);
        }
    </style>
</head>

<body>
    <div class="px-28 py-5">
        <div class="flex flex-row border-2 rounded-lg py-5 bg-gray-50">
            <div class="flex flex-col w-full p-10">
                <p class="font-bold text-2xl">Check out</p>

                <!-- ข้อมูลผู้รับ -->
                <div class="py-2 flex-col">
                    <p class="font-semibold text-lg">ข้อมูลผู้รับ</p>

                    <!-- ชื่อ-นามสกุล -->
                    <div class="flex flex-row">
                        <div class="py-2 w-full mr-3">
                            <p class="font-semibold mb-1">ชื่อจริง</p>
                            <?php
                            if ($isMember) {
                                if ($userInfo["CusFName"] == "") {
                                    echo "<input id='RecvFNameBox' name='RecvFName' value='{$userInfo["CusFName"]}' type='text' class='border rounded-md h-10 w-full shadow-sm p-2'>";
                                } else {
                                    echo "<input id='RecvFNameBox' name='RecvFName' placeholder='กรุณากรอกชื่อจริง' type='text' class='border rounded-md h-10 w-full shadow-sm p-2 text-md'>";
                                }
                            } else {
                                echo "<input id='RecvFNameBox' name='RecvFName' placeholder='กรุณากรอกชื่อจริง' type='text' class='border rounded-md h-10 w-full shadow-sm p-2 text-md'>";
                            }
                            ?>
                            <span id="RecvFNameError" class="text-red-500 text-sm h-2 bg-red-500"></span>
                        </div>
                        <div class="py-2 w-full ml-3">
                            <p class="font-semibold mb-1">นามสกุล</p>
                            <?php
                            if ($isMember) {
                                if ($userInfo["CusLName"] == "") {
                                    echo "<input id='RecvLNameBox' name='RecvLName' value='{$userInfo["CusLName"]}' type='text' class='border rounded-md h-10 w-full shadow-sm p-2'>";
                                } else {
                                    echo "<input id='RecvLNameBox' name='RecvLName' placeholder='กรุณากรอกนามสกุล' type='text' class='border rounded-md h-10 w-full shadow-sm p-2 text-md'>";
                                }
                            } else {
                                echo "<input id='RecvLNameBox' name='RecvLName' placeholder='กรุณากรอกนามสกุล' type='text' class='border rounded-md h-10 w-full shadow-sm p-2 text-md'>";
                            }
                            ?>
                            <span id="RecvFNameError" class="text-red-500 text-sm h-2 bg-red-500"></span>
                        </div>
                    </div>

                    <!-- เบอร์โทร-เพศ -->
                    <div class="flex flex-row">
                        <div class="py-2 w-full mr-3">
                            <p class="font-semibold mb-1">เบอร์โทรศัพท์</p>
                            <?php
                            if ($isMember) {
                                if ($userInfo["Tel"] == "") {
                                    echo "<input id='RecvTelBox' name='RecvTel' value='{$userInfo["Tel"]}' type='text' class='border rounded-md h-10 w-full shadow-sm p-2'>";
                                } else {
                                    echo "<input id='RecvTelBox' name='RecvTel' placeholder='กรุณากรอกเบอร์โทรศัพท์' type='text' class='border rounded-md h-10 w-full shadow-sm p-2 text-md'>";
                                }
                            } else {
                                echo "<input id='RecvTelBox' name='RecvTel' placeholder='กรุณากรอกเบอร์โทรศัพท์' type='text' class='border rounded-md h-10 w-full shadow-sm p-2 text-md'>";
                            }
                            ?>
                            <span id="RecvTelError" class="text-red-500 text-sm h-2 bg-red-500"></span>
                        </div>
                        <div class="py-2 w-full ml-3">
                            <p class="font-semibold mb-1">เพศ</p>
                            <div class=" mt-3">
                                <?php
                                if ($isMember) {
                                    if ($userInfo['Sex'] == 'M') {
                                        echo '<input type="radio" name="RecvSex" value="M" checked> ชาย
                                        <input type="radio" name="RecvSex" value="F"> หญิง
                                        <input type="radio" name="RecvSex" value="N"> ไม่ระบุ';
                                    } else if ($userInfo['Sex'] == 'F') {
                                        echo '<input type="radio" name="RecvSex" value="M"> ชาย
                                        <input type="radio" name="RecvSex" value="F" checked> หญิง
                                        <input type="radio" name="RecvSex" value="N"> ไม่ระบุ';
                                    } else {
                                        echo '<input type="radio" name="RecvSex" value="M"> ชาย
                                        <input type="radio" name="RecvSex" value="F"> หญิง
                                        <input type="radio" name="RecvSex" value="N"> ไม่ระบุ';
                                    }
                                } else {
                                    echo '<input type="radio" name="RecvSex" value="M"> ชาย
                                        <input type="radio" name="RecvSex" value="F"> หญิง
                                        <input type="radio" name="RecvSex" value="N"> ไม่ระบุ';
                                }
                                ?>
                            </div>
                            <span id="RecvSexError" class="text-red-500 text-sm h-2 bg-red-500"></span>
                        </div>
                    </div>

                    <!-- อีเมล -->
                    <div class="flex flex-row">
                        <div class="py-2 w-full">
                            <p class="font-semibold mb-1">อีเมล</p>
                            <?php
                            if ($isMember) {
                                if ($userInfo["Email"] == "") {
                                    echo "<input id='RecvEmailBox' name='RecvEmail' value='{$userInfo["Email"]}' type='text' class='border rounded-md h-10 w-full shadow-sm p-2'>";
                                } else {
                                    echo "<input id='RecvEmailBox' name='RecvEmail' placeholder='กรุณากรอกอีเมล' type='text' class='border rounded-md h-10 w-full shadow-sm p-2 text-md'>";
                                }
                            } else {
                                echo "<input id='RecvEmailBox' name='RecvEmail' placeholder='กรุณากรอกอีเมล' type='text' class='border rounded-md h-10 w-full shadow-sm p-2 text-md'>";
                            }
                            ?>
                            <span id="RecvEmailError" class="text-red-500 text-sm h-2 bg-red-500"></span>
                        </div>
                    </div>

                    <!-- ที่อยู่ -->
                    <div class="flex flex-row">
                        <div class="py-2 w-full">
                            <p class="font-semibold mb-1">ที่อยู่</p>
                            <?php
                            if ($isMember) {
                                if ($userInfo['Address'] != "") {
                                    echo '<textarea id="RecvAddrInput" name="RecvAddr" class="mt-1 w-full p-2 border rounded-md h-20 shadow-sm resize-none" required>' . $userInfo['Address'] . '</textarea>';
                                } else {
                                    echo '<textarea id="RecvAddrInput" name="RecvAddr" class="mt-1 w-full p-2 border rounded-md h-20 shadow-sm resize-none" placeholder="กรุณากรอกที่อยู่ผู้รับ" required></textarea>';
                                }
                            } else {
                                echo '<textarea id="RecvAddrInput" name="RecvAddr" class="mt-1 w-full p-2 border rounded-md h-20 shadow-sm resize-none" placeholder="กรุณากรอกที่อยู่ผู้รับ" required></textarea>';
                            }
                            ?>
                            <span id="RecvAddrError" class="text-red-500 text-sm h-2 bg-red-500"></span>
                        </div>
                    </div>

                    <!-- จังหวัด-รหัสไปรษณีย์ -->
                    <div class="flex flex-row">
                        <div class="py-2 w-full mr-3">
                            <p class="font-semibold mb-1">จังหวัด</p>
                            <?php
                            if ($isMember) {
                                if ($userInfo["Province"] == "") {
                                    echo "<input id='RecvProvinceBox' name='RecvProvince' value='{$userInfo["Province"]}' type='text' class='border rounded-md h-10 w-full shadow-sm p-2'>";
                                } else {
                                    echo "<input id='RecvProvinceBox' name='RecvProvince' placeholder='กรุณากรอกจังหวัด' type='text' class='border rounded-md h-10 w-full shadow-sm p-2 text-md'>";
                                }
                            } else {
                                echo "<input id='RecvProvinceBox' name='RecvProvince' placeholder='กรุณากรอกจังหวัด' type='text' class='border rounded-md h-10 w-full shadow-sm p-2 text-md'>";
                            }
                            ?>
                            <span id="RecvProvinceError" class="text-red-500 text-sm h-2 bg-red-500"></span>
                        </div>
                        <div class="py-2 w-full ml-3">
                            <p class="font-semibold mb-1">รหัสไปรษณีย์</p>
                            <?php
                            if ($isMember) {
                                if ($userInfo["Postcode"] == "") {
                                    echo "<input id='RecvPostcodeBox' name='RecvPostcode' value='{$userInfo["Postcode"]}' type='text' class='border rounded-md h-10 w-full shadow-sm p-2'>";
                                } else {
                                    echo "<input id='RecvPostcodeBox' name='RecvPostcode' placeholder='กรุณากรอกรหัสไปรษณีย์' type='text' class='border rounded-md h-10 w-full shadow-sm p-2 text-md'>";
                                }
                            } else {
                                echo "<input id='RecvPostcodeBox' name='RecvPostcode' placeholder='กรุณากรอกรหัสไปรษณีย์' type='text' class='border rounded-md h-10 w-full shadow-sm p-2 text-md'>";
                            }
                            ?>
                            <span id="RecvPostcodeError" class="text-red-500 text-sm h-2 bg-red-500"></span>
                        </div>
                    </div>
                </div>

                <!-- ------------------------------------------------------------------------------------------------------------- -->

                <hr class="border border-gray-300 border-5 my-10 rounded-md w-full" style="border-width: 1px;">

                <!-- ------------------------------------------------------------------------------------------------------------- -->


                <!-- ข้อมูลผู้ส่ง -->
                <div class="py-2 flex-col">
                    <div class="flex justify-between">
                        <p class="font-semibold text-lg">ข้อมูลผู้ส่ง</p>
                        <div class="flex mt-1.5">
                            <input type="checkbox" id="sameAddr" class="ml-5">
                            <p class="text-sm mt-1 ml-2">ใช้ข้อมูลผู้รับ</p>
                        </div>
                    </div>

                    <!-- ชื่อ-นามสกุล -->
                    <div class="flex flex-row">
                        <div class="py-2 w-full mr-3">
                            <p class="font-semibold mb-1">ชื่อจริง</p>
                            <?php
                            if ($isMember) {
                                if ($userInfo["CusFName"] == "") {
                                    echo "<input id='PayerFNameBox' name='PayerFName' value='{$userInfo["CusFName"]}' type='text' class='border rounded-md h-10 w-full shadow-sm p-2'>";
                                } else {
                                    echo "<input id='PayerFNameBox' name='PayerFName' placeholder='กรุณากรอกชื่อจริง' type='text' class='border rounded-md h-10 w-full shadow-sm p-2 text-md'>";
                                }
                            } else {
                                echo "<input id='PayerFNameBox' name='PayerFName' placeholder='กรุณากรอกชื่อจริง' type='text' class='border rounded-md h-10 w-full shadow-sm p-2 text-md'>";
                            }
                            ?>
                            <span id="PayerFNameError" class="text-red-500 text-sm h-2 bg-red-500"></span>
                        </div>
                        <div class="py-2 w-full ml-3">
                            <p class="font-semibold mb-1">นามสกุล</p>
                            <?php
                            if ($isMember) {
                                if ($userInfo["CusLName"] == "") {
                                    echo "<input id='PayerLNameBox' name='PayerLName' value='{$userInfo["CusLName"]}' type='text' class='border rounded-md h-10 w-full shadow-sm p-2'>";
                                } else {
                                    echo "<input id='PayerLNameBox' name='PayerLName' placeholder='กรุณากรอกนามสกุล' type='text' class='border rounded-md h-10 w-full shadow-sm p-2 text-md'>";
                                }
                            } else {
                                echo "<input id='PayerLNameBox' name='PayerLName' placeholder='กรุณากรอกนามสกุล' type='text' class='border rounded-md h-10 w-full shadow-sm p-2 text-md'>";
                            }
                            ?>
                            <span id="PayerFNameError" class="text-red-500 text-sm h-2 bg-red-500"></span>
                        </div>
                    </div>

                    <!-- เบอร์โทร-เพศ -->
                    <div class="flex flex-row">
                        <div class="py-2 w-full mr-3">
                            <p class="font-semibold mb-1">เบอร์โทรศัพท์</p>
                            <?php
                            if ($isMember) {
                                if ($userInfo["Tel"] == "") {
                                    echo "<input id='PayerTelBox' name='PayerTel' value='{$userInfo["Tel"]}' type='text' class='border rounded-md h-10 w-full shadow-sm p-2'>";
                                } else {
                                    echo "<input id='PayerTelBox' name='PayerTel' placeholder='กรุณากรอกเบอร์โทรศัพท์' type='text' class='border rounded-md h-10 w-full shadow-sm p-2 text-md'>";
                                }
                            } else {
                                echo "<input id='PayerTelBox' name='PayerTel' placeholder='กรุณากรอกเบอร์โทรศัพท์' type='text' class='border rounded-md h-10 w-full shadow-sm p-2 text-md'>";
                            }
                            ?>
                            <span id="PayerTelError" class="text-red-500 text-sm h-2 bg-red-500"></span>
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
                                        <input type="radio" name="PayerSex" value="N"> ไม่ระบุ';
                                    }
                                } else {
                                    echo '<input type="radio" name="PayerSex" value="M"> ชาย
                                        <input type="radio" name="PayerSex" value="F"> หญิง
                                        <input type="radio" name="PayerSex" value="N"> ไม่ระบุ';
                                }
                                ?>
                            </div>
                            <span id="PayerSexError" class="text-red-500 text-sm h-2 bg-red-500"></span>
                        </div>
                    </div>

                    <!-- อีเมล -->
                    <div class="flex flex-row">
                        <div class="py-2 w-full">
                            <p class="font-semibold mb-1">อีเมล</p>
                            <?php
                            if ($isMember) {
                                if ($userInfo["Email"] == "") {
                                    echo "<input id='PayerEmailBox' name='PayerEmail' value='{$userInfo["Email"]}' type='text' class='border rounded-md h-10 w-full shadow-sm p-2'>";
                                } else {
                                    echo "<input id='PayerEmailBox' name='PayerEmail' placeholder='กรุณากรอกอีเมล' type='text' class='border rounded-md h-10 w-full shadow-sm p-2 text-md'>";
                                }
                            } else {
                                echo "<input id='PayerEmailBox' name='PayerEmail' placeholder='กรุณากรอกอีเมล' type='text' class='border rounded-md h-10 w-full shadow-sm p-2 text-md'>";
                            }
                            ?>
                            <span id="PayerEmailError" class="text-red-500 text-sm h-2 bg-red-500"></span>
                        </div>
                    </div>

                    <!-- ที่อยู่ -->
                    <div class="flex flex-row">
                        <div class="py-2 w-full">
                            <p class="font-semibold mb-1">ที่อยู่</p>
                            <?php
                            if ($isMember) {
                                if ($userInfo['Address'] != "") {
                                    echo '<textarea id="PayerAddrInput" name="PayerAddr" class="mt-1 w-full p-2 border rounded-md h-20 shadow-sm resize-none" required>' . $userInfo['Address'] . '</textarea>';
                                } else {
                                    echo '<textarea id="PayerAddrInput" name="PayerAddr" class="mt-1 w-full p-2 border rounded-md h-20 shadow-sm resize-none" placeholder="กรุณากรอกที่อยู่ผู้รับ" required></textarea>';
                                }
                            } else {
                                echo '<textarea id="PayerAddrInput" name="PayerAddr" class="mt-1 w-full p-2 border rounded-md h-20 shadow-sm resize-none" placeholder="กรุณากรอกที่อยู่ผู้รับ" required></textarea>';
                            }
                            ?>
                            <span id="PayerAddrError" class="text-red-500 text-sm h-2 bg-red-500"></span>
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
                    <div id="taxID" class="w-full ml-5" style="display: none;">
                        <h1 class="font-semibold mt-2">เลขภาษีส่วนบุคคล</h1>
                        <input id="taxPasonalNumber" name="taxPasonalNumber" class="p-2 w-full border rounded-md h-8 border-gray-400" type="text" placeholder="กรุณากรอกเลขกำกับภาษี" required>
                    </div>
                </div>

                <!-- ------------------------------------------------------------------------------------------------------------- -->

                <hr class="border border-gray-300 border-5 my-10 rounded-md w-full" style="border-width: 1px;">

                <!-- ------------------------------------------------------------------------------------------------------------- -->

                <div class="py-2 flex-col">
                    <p class="font-semibold text-lg">ช่องทางการชำระเงิน</p>
                    <div class="flex flex-col my-2">
                        <div class="rounded-lg p-5 bg-white border w-full my-1">
                            <p class="text-md">ชำระผ่าน QR Code</p>
                        </div>
                        <div class="rounded-lg p-5 bg-white border w-full my-1">
                            <p class="text-md">ชำระผ่านเงินปลายทาง</p>
                        </div>
                    </div>
                </div>

            </div>

            <div class="w-full p-10">
                <p class="font-bold text-xl">รายการสินค้า</p>
                <div class="flex flex-col border bg-white rounded-md p-5">
                    <?php
                    if ($isMember) {
                        foreach ($proIds as $proId) {
                            $row = showCartSession($proId)->fetch_assoc();
                            $qty = getQtyFromCart($CusID, $proId)->fetch_assoc();
                            echo "<div class='flex flex-row mb-5 w-full'>";
                            echo "<div class=''>";
                            echo "<img src='{$row['ImageSource']}' class='w-24 h-30 objec-fill'>";
                            echo "</div>";
                            echo "<div class='ml-3 w-3/4 flex flex-col'>";
                            echo "<p class='font-semibold text-md'>{$row['ProName']}</p>";
                            echo "<p class='font-semibold text-md'>{$qty['Qty']}</p>";
                            echo "</div>";
                            echo "</div>";
                            echo "<hr class='border border-gray-300 border-5 mb-5 rounded-md w-full' style='border-width: 1px;'>";
                        }
                    } else {
                        //loop to use showProductSession function
                    }
                    ?>
                    <div>
                        <button class="w-full mt-10 h-12 bg-blue-800 rounded-md font-bold text-white hover:shadow-md hover:bg-blue-900">ยืนยันการสั่งซื้อ</button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        const sameAddr = document.getElementById('sameAddr');
    </script>
</body>

</html>