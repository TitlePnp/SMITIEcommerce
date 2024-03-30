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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

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
    <form action="">
        <div class="px-28 py-5">
            <div class="flex flex-row border-2 rounded-lg py-5 bg-gray-50">
                <div class="flex flex-col w-full p-10">
                    <div class="mb-2">
                        <a href="Cart.php" class="hover:text-blue-800 text-blue-500 font-semibold"><i class='bx bx-arrow-back mr-2'></i>ย้อนกลับ</a>
                    </div>
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
                                    if ($userInfo["CusFName"] != "") {
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
                                    if ($userInfo["CusLName"] != "") {
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
                                    if ($userInfo["Tel"] != "") {
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
                                    if ($userInfo["Province"] != "") {
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
                                    if ($userInfo["Postcode"] != "") {
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
                        <div id="taxIdDiv" class="w-full ml-5" style="display: none;">
                            <h1 class="font-semibold mt-2">เลขภาษีส่วนบุคคล</h1>
                            <input id="taxIDBox" name="taxID" class="p-2 w-full border rounded-md h-8 border-gray-400" type="text" placeholder="กรุณากรอกเลขกำกับภาษี" required>
                        </div>
                    </div>

                    <!-- ------------------------------------------------------------------------------------------------------------- -->

                    <hr class="border border-gray-300 border-5 my-10 rounded-md w-full" style="border-width: 1px;">

                    <!-- ------------------------------------------------------------------------------------------------------------- -->

                    <div class="py-2 flex-col">
                        <p class="font-semibold text-lg">ช่องทางการชำระเงิน</p>
                        <div class="flex flex-col my-2">
                            <div id="qrBox" class="rounded-lg p-5 bg-white border w-full my-1 relative">
                                <p class="text-md">ชำระผ่าน QR Code</p>
                                <i id="qrCheckIcon" class='bx bxs-check-circle absolute right-3 top-3 text-2xl text-blue-600' style="display: none;"></i>
                                <input type="hidden" name="PaymentMethod" value="MobileBanking">
                            </div>
                            <div id="codBox" class="rounded-lg p-5 bg-white border w-full my-1 relative">
                                <p class="text-md">ชำระผ่านเงินปลายทาง</p>
                                <i id="codCheckIcon" class='bx bxs-check-circle absolute right-3 top-3 text-2xl text-blue-600' style="display: none;"></i>
                                <input type="hidden" name="PaymentMeyhod" value="COD">
                            </div>
                        </div>
                    </div>

                </div>

                <div class="w-full p-10 mt-10">
                    <p class="font-bold text-xl mb-2">รายการสินค้า</p>
                    <div class="flex flex-col border bg-white rounded-md p-5">
                        <?php
                        $totoalPrice = 0;
                        if ($isMember) {
                            foreach ($proIds as $proId) {
                                $row = showCartSession($proId)->fetch_assoc();
                                $qty = getQtyFromCart($CusID, $proId)->fetch_assoc();
                                $totoalPrice += $row['PricePerUnit'] * $qty['Qty'];
                                echo "<div class='flex flex-row mb-5 w-full h-34'>";
                                echo "<div class=''>";
                                echo "<img src='{$row['ImageSource']}' class='w-24 h-32 objec-fill'>";
                                echo "</div>";
                                echo "<div class='ml-3 w-3/4 flex flex-col'>";
                                echo "<p class='font-semibold text-xl'>{$row['ProName']}</p>";
                                echo "<p class=' text-sm'>จำนวน {$qty['Qty']} เล่ม</p>";
                                echo "<div class='flex flex-col justify-end h-full'>";
                                echo "<p class='text-sm font-semibold'>ราคา {$row['PricePerUnit']} บาท</p>";
                                echo "</div>";
                                echo "</div>";
                                echo "</div>";
                                echo "<hr class='border border-gray-300 border-5 mb-5 rounded-md w-full' style='border-width: 1px;'>";
                            }
                        } else {
                            //loop to use showProductSession function
                        }
                        ?>
                        <div class="flex flex-col">
                            <div class="flex justify-between mb-5">
                                <?php
                                $totoalPriceFormat = number_format($totoalPrice, 2);
                                ?>
                                <p class="font-semibold text-md">ราคารวม: </p>
                                <p class="font-md text-md"><?php echo $totoalPriceFormat; ?> บาท</p>
                                <input type="hidden" name="TotalPrice" value="<?php echo $totalPrice ?>">
                            </div>
                            <div class="flex justify-between">
                                <?php
                                $vat = $totoalPrice * 0.07;
                                $vatFormat = number_format($vat, 2);
                                ?>
                                <p class="font-semibold text-md">Vat 7%: </p>
                                <p class="font-md text-md"><?php echo $vatFormat; ?> บาท</p>
                                <input type="hidden" name="Vat" value="<?php echo $vat ?>">
                            </div>
                        </div>

                        <hr class='border border-gray-300 my-6 rounded-md w-full' style='border-width: 0.1px;'>

                        <div class="flex justify-between">
                            <?php
                            $totalPrice = $totoalPrice + $vat;
                            $totalPriceFormat = number_format($totalPrice, 2);
                            ?>
                            <p class="font-semibold text-xl">ราคาสุทธิ: </p>
                            <p class="font-md text-md"><?php echo $totalPriceFormat; ?> บาท</p>
                        </div>

                        <hr class='border border-gray-300 my-6 rounded-md w-full' style='border-width: 0.1px;'>

                        <div>
                            <button onclick="formCheck()" type="button" class="w-full mt-5 h-12 bg-blue-800 rounded-md font-bold text-white hover:shadow-md hover:bg-blue-900">ยืนยันการสั่งซื้อ</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        //define receiverBox element id
        var RecvFNameBox = document.getElementById('RecvFNameBox');
        var RecvLNameBox = document.getElementById('RecvLNameBox');
        var RecvTelBox = document.getElementById('RecvTelBox');
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
        var PayerEmailBox = document.getElementById('PayerEmailBox');
        var PayerAddrInput = document.getElementById('PayerAddrInput');

        //define payerError element id
        var PayerFNameError = document.getElementById('PayerFNameError');
        var PayerLNameError = document.getElementById('PayerLNameError');
        var PayerTelError = document.getElementById('PayerTelError');
        var PayerEmailError = document.getElementById('PayerEmailError');
        var PayerAddrError = document.getElementById('PayerAddrError');

        //define taxID element id
        var taxID = document.getElementById('taxIdDiv');
        var taxIDBox = document.getElementById('taxIDBox');
        var taxInvoice = document.getElementsByName('taxInvoice');

        //define payment method 
        var qrBox = document.getElementById('qrBox');
        var codBox = document.getElementById('codBox');

        //Form Check Format
        function formCheck() {

        }
    </script>
</body>

</html>