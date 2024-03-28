<?php
require_once "../../Backend/Authorized/UserAuthorized.php";
require_once "../../Backend/Authorized/ManageHeader.php";
require_once "../../Backend/MainPage/CartDetail.php"
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kodchasan:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <title>SMITI SHOP:Summary Order</title>
    <style>
        * {
            font-family: 'Kodchasan';
            margin: 0px;
            padding: 0px;
        }
    </style>
</head>

<body class="bg-gray-500">
    <div class="flex flex-row px-28 py-8">
        <div class="w-full bg-white mr-3 p-5 rounded-lg">
            <div class="mb-3">
                <a class="hover:text-blue-500" href="Cart.php"><button class="font-semibold"><i class='bx bx-arrow-back'></i> ย้อนกลับ</button></a>
            </div>
            <h1 class="text-2xl font-bold">เช็คเอาท์</h1>
            <div class="mt-3">
                <h1 class="font-semibold text-lg">ข้อมูลผู้รับสินค้า</h1>
                <!-- ชื่อ นามสกุล-->
                <div class="flex flex-row mt-2">
                    <div class="w-full mr-5">
                        <h1 class="font-semibold">ชื่อ*</h1>
                        <!-- plaecholder with address in database -->
                        <input class="w-full p-2 border-2 rounded-md h-8 border-gray-400" placeholder="กรุณากรอกชื่อ" type="text" required>
                    </div>
                    <div class="w-full ml-5">
                        <h1 class="font-semibold">นามสกุล*</h1>
                        <!-- plaecholder with address in database -->
                        <input class="w-full border-2 p-2 rounded-md h-8 border-gray-400" placeholder="กรุณากรอกนามสกุล" type="text" required>
                    </div>
                </div>

                <div class="flex flex-row mt-2">
                    <div class="w-full mr-5">
                        <h1 class="font-semibold">เบอร์โทรศัพท์*</h1>
                        <!-- plaecholder with address in database -->
                        <input class="w-full p-2 border-2 rounded-md h-8 border-gray-400" placeholder="กรุณากรอกเบอร์โทรศัพท์" type="text" required>
                    </div>
                    <div class="w-full ml-5">
                        <h1 class="font-semibold">เพศ</h1>
                        <!-- plaecholder with address in database -->
                        <input type="radio" name="sex" value="M"> ชาย
                        <input type="radio" name="sex" value="F"> หญิง
                        <input type="radio" name="sex" value="N"> ไม่ระบุ
                    </div>
                </div>

                <div class="mt-2">
                    <h1 class="font-semibold">อีเมล*</h1>
                    <input class="w-full p-2 border-2 rounded-md h-8 border-gray-400" placeholder="กรุณากรอกอีเมล" type="email" required>
                </div>

                <div class="mt-2">
                    <h1 class="font-semibold">ที่อยู่*</h1>
                    <textarea class="mt-1 w-full p-2 border-2 rounded-md h-20 border-gray-400 resize-none" placeholder="กรุณากรอกที่อยู่ผู้รับ" required></textarea>
                </div>

                <div class="flex flex-row mt-2">
                    <div class="w-full mr-5">
                        <h1 class="font-semibold">จัวหวัด*</h1>
                        <!-- plaecholder with address in database -->
                        <input class="w-full p-2 border-2 rounded-md h-8 border-gray-400 p-2" placeholder="กรุณากรอกจังหวัด" type="text" required>
                    </div>
                    <div class="w-full ml-5">
                        <h1 class="font-semibold">รหัสไปรษณีย์*</h1>
                        <!-- plaecholder with address in database -->
                        <input class="w-full p-2 border-2 rounded-md h-8 border-gray-400 p-2" placeholder="กรุณากรอกรหัสไปรษณีย์" type="text" required>
                    </div>
                </div>

                <div class="flex flex-row mt-2">
                    <div class="w-full mr-5">
                        <h1 class="font-semibold mt-2">ใบกำกับภาษี</h1>
                        <input name="taxInvoice" type="radio" value="No" checked> ไม่ต้องการ
                        <input name="taxInvoice" type="radio" value="Yes"> ต้องการ
                    </div>
                    <div id="taxID" class="w-full ml-5" style="display: none;">
                        <h1 class="font-semibold mt-2">เลขภาษีส่วนบุคคล</h1>
                        <input id="taxPasonalNumber" name="taxPasonalNumber" class="p-2 w-full border-2 rounded-md h-8 border-gray-400" type="text" placeholder="กรุณากรอกเลขกำกับภาษี" required>
                    </div>
                </div>
            </div>

            <div class="mt-3">
                <h1 class="font-semibold text-lg">ช่องทางการชำระเงิน</h1>
                <div class="mt-2">
                    <div id="PaymentMobileBanking" class="w-full border-2 rounded-md p-5 my-2 flex justify-between items-center" onclick="document.getElementById('paymentCreditCard').checked = true">
                        <div>
                            <input id="MB" class="font-semibold text-lg" type="radio" name="payment" value="MobileBanking"> ชำระผ่าน Mobile Banking
                        </div>
                        <i class='bx bxs-bank text-2xl'></i>
                    </div>

                    <div id="PaymentCOD" class="w-full border-2 rounded-md p-5 my-2 flex justify-between items-center" onclick="document.getElementById('paymentCreditCard').checked = true">
                        <div>
                            <input id="COD" class="font-semibold text-lg" type="radio" name="payment" value="COD"> เก็บเงินปลายทาง
                        </div>
                        <i class='bx bxs-truck text-2xl'></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="w-3/6 bg-white ml-3 rounded-lg p-5">
            <p class="font-semibold text-lg">รายการสินค้า</p>
            <hr class="border-t border-gray-400 my-2">
            <div class="">
                <?php
                $totalPrice = 0;
                $count = count($_SESSION['cart']);
                $key = array_keys($_SESSION['cart']);
                for ($i = 0; $i < $count; $i++) {
                    $row = showCartProduct($key[$i])->fetch_assoc();
                    $totalPrice += $row['PricePerUnit'] * $_SESSION['cart'][$i + 1];
                    echo "<div class='flex flex-row my-2'>";
                    echo "<div class='w-5/12'>";
                    echo "<img class='h-16 w-16 max-w-20 object-cover' src='" . $row['ImageSource'] . "'>";
                    echo "</div>";
                    echo "<div class='w-4/12'>";
                    echo "<p class='text-sm font-semibold truncate'>" . $row['ProName'] . "</p>";
                    echo "<p class='text-sm'>x" . $_SESSION['cart'][$i + 1] . "</p>";
                    echo "</div>";
                    echo "<div class='flex flex-row w-full justify-end'>";
                    echo "<p class='text-sm font-semibold justify-end'>" . $row['PricePerUnit'] . " บาท</p>";
                    echo "</div>";
                    echo "</div>";
                    echo "<hr class='border-t border-gray-400 my-2'>";
                }
                $vat = $totalPrice * 0.07;

                ?>
            </div>

            <div class="mt-2">
                <div class="flex flex-row justify-between">
                    <p class="font-semibold text-md">ยอดรวม</p>
                    <p class="font-semibold text-md"><?php echo "" . $totalPrice . " บาท" ?></p>
                </div>
                <div class="flex flex-row justify-between">
                    <p class="font-semibold text-md">ค่าจัดส่ง</p>
                    <p class="font-semibold text-md">0 บาท</p>
                </div>
                <div class="flex flex-row justify-between"> 
                    <p class="font-semibold text-md">Vat 7%</p>
                    <p class="font-semibold text-md"><?php echo "" . $vat . " บาท" ?></p>
                </div>
                <div class="flex flex-row justify-between">
                    <p class="font-semibold text-md">ยอดสุทธิ</p>
                    <p class="font-semibold text-md"><?php echo "" . $totalPrice + $vat . " บาท" ?></p>
                </div>
                <div class="text-center mt-5">
                    <button class="p-2 bg-red-500 rounded-lg font-semibold text-white w-4/5 hover:shadow-lg">ยืนยันการสั่งซื้อ</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        //TaxIvoice
        let taxInvoice = document.getElementsByName("taxInvoice");
        const taxID = document.getElementById("taxID");

        for (let i = 0; i < taxInvoice.length; i++) {
            taxInvoice[i].addEventListener('change', function() {
                if (this.value === "Yes") {
                    taxID.style.display = 'block';
                } else {
                    taxID.style.display = 'none';
                }
            });
        }

        const PaymentMobileBanking = document.getElementById("PaymentMobileBanking");
        const PaymentCOD = document.getElementById("PaymentCOD");
        //Payment
        PaymentMobileBanking.addEventListener('click', function() {
            document.getElementById('MB').checked = true;
            PaymentMobileBanking.style.backgroundColor = '#f3f4f6';
            PaymentMobileBanking.style.borderColor = 'black';
            PaymentCOD.style.borderColor = 'rgb(209 213 219)';
            PaymentCOD.style.backgroundColor = '#fff';
        });
        PaymentCOD.addEventListener('click', function() {
            document.getElementById('COD').checked = true;
            PaymentMobileBanking.style.backgroundColor = '#fff';
            PaymentMobileBanking.style.borderColor = 'rgb(209 213 219)';
            PaymentCOD.style.backgroundColor = '#f3f4f6';
            PaymentCOD.style.borderColor = 'black';
        });
    </script>
</body>

</html>