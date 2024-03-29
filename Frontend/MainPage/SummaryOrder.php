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

<body class="">
    <form id="OrderControl" method="POST" action="../../Backend/OrderManage/OrderController.php">
        <div class="flex flex-row px-28 py-8">
            <div class="w-full bg-white mr-3 p-5 rounded-lg shadow-lg">
                <div class="mb-3">
                    <a class="hover:text-blue-500" href="Cart.php"><button class="font-semibold"><i class='bx bx-arrow-back'></i> ย้อนกลับ</button></a>
                </div>
                <div class="">
                    <h1 class="text-2xl font-bold">เช็คเอาท์</h1>
                </div>
                <div class="mt-3">
                    <h1 class="font-semibold text-lg">ข้อมูลผู้รับสินค้า</h1>
                    <!-- ชื่อ นามสกุล-->
                    <div class="flex flex-row mt-2">
                        <div class="w-full mr-5">
                            <h1 class="font-semibold">ชื่อ*</h1>
                            <!-- plaecholder with address in database -->
                            <input id="RecvFNameInput" name="RecvFName" class="w-full p-2 border rounded-md h-8 border-gray-400" placeholder="กรุณากรอกชื่อ" type="text" required>
                            <span id="RecvFNameError" class="text-sm text-red-500" style="display:none"></span>
                        </div>
                        <div class="w-full ml-5">
                            <h1 class="font-semibold">นามสกุล*</h1>
                            <!-- plaecholder with address in database -->
                            <input id="RecvLNameInput" name="RecvLName" class="w-full border p-2 rounded-md h-8 border-gray-400" placeholder="กรุณากรอกนามสกุล" type="text" required>
                            <span id="RecvLNameError" class="text-sm text-red-500" style="display:none"></span>
                        </div>
                    </div>

                    <div class="flex flex-row mt-2">
                        <div class="w-full mr-5">
                            <h1 class="font-semibold">เบอร์โทรศัพท์*</h1>
                            <!-- plaecholder with address in database -->
                            <input id="RecvTelInput" name="RecvTel" class="w-full p-2 border rounded-md h-8 border-gray-400" placeholder="กรุณากรอกเบอร์โทรศัพท์" type="text" required>
                            <span id="RecvTelError" class="text-sm text-red-500" style="display:none"></span>
                        </div>
                        <div class="w-full ml-5">
                            <h1 class="font-semibold">เพศ</h1>
                            <!-- plaecholder with address in database -->
                            <input type="radio" name="sex" value="M"> ชาย
                            <input type="radio" name="sex" value="F"> หญิง
                            <input type="radio" name="sex" value="N" required> ไม่ระบุ
                        </div>
                    </div>

                    <div class="mt-2">
                        <h1 class="font-semibold">อีเมล*</h1>
                        <input id="RecvEmailInput" name="RecvEmail" class="w-full p-2 border rounded-md h-8 border-gray-400" placeholder="กรุณากรอกอีเมล" type="email" required>
                        <span id="RecvEmailError" class="text-sm text-red-500" style="display:none"></span>
                    </div>

                    <div class="mt-2">
                        <h1 class="font-semibold">ที่อยู่สำหรับการจัดส่ง*</h1>
                        <textarea id="RecvAddrInput" name="RecvAddr" class="mt-1 w-full p-2 border rounded-md h-20 border-gray-400 resize-none" placeholder="กรุณากรอกที่อยู่ผู้รับ" required></textarea>
                        <span id="RecvAddrError" class="text-sm text-red-500" style="display:none"></span>
                    </div>

                    <div class="flex flex-row mt-2">
                        <div class="w-full mr-5">
                            <h1 class="font-semibold">จัวหวัด*</h1>
                            <!-- plaecholder with address in database -->
                            <input id="RecvProvinceInput" name="RecvProvince" class="w-full p-2 border rounded-md h-8 border-gray-400 p-2" placeholder="กรุณากรอกจังหวัด" type="text" required>
                            <span id="RecvProvinceError" class="text-sm text-red-500" style="display:none"></span>
                        </div>
                        <div class="w-full ml-5">
                            <h1 class="font-semibold">รหัสไปรษณีย์*</h1>
                            <!-- plaecholder with address in database -->
                            <input id="RecvInputPost" name="RecvPost" class="w-full p-2 border rounded-md h-8 border-gray-400 p-2" placeholder="กรุณากรอกรหัสไปรษณีย์" type="text" required>
                            <span id="RecvPostError" class="text-sm text-red-500" style="display:none"></span>
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
                            <input id="taxPasonalNumber" name="taxPasonalNumber" class="p-2 w-full border rounded-md h-8 border-gray-400" type="text" placeholder="กรุณากรอกเลขกำกับภาษี" required>
                        </div>
                    </div>
                </div>

                <div class="mt-3">
                    <h1 class="font-semibold text-lg">ช่องทางการชำระเงิน*</h1>
                    <div class="mt-2">
                        <div id="PaymentMobileBanking" class="w-full border rounded-md p-5 my-2 flex justify-between items-center" onclick="document.getElementById('paymentCreditCard').checked = true">
                            <div>
                                <input id="MB" class="font-semibold text-lg" type="radio" name="payment" value="MobileBanking"> ชำระผ่าน Mobile Banking
                            </div>
                            <i class='bx bxs-bank text-2xl'></i>
                        </div>

                        <div id="PaymentCOD" class="w-full border rounded-md p-5 my-2 flex justify-between items-center" onclick="document.getElementById('paymentCreditCard').checked = true">
                            <div>
                                <input id="COD" class="font-semibold text-lg" type="radio" name="payment" value="COD"> เก็บเงินปลายทาง
                            </div>
                            <i class='bx bxs-truck text-2xl'></i>
                        </div>
                        <h1 id="PaymetError" style="display:none" class="font-regular text-md text-red-500">*กรุณาเลือกช่องทางการชำระเงิน</h1>
                    </div>
                </div>
            </div>
            <div class="w-3/6 bg-white ml-3 rounded-lg p-5 shadow-lg">
                <div class=" flex rounded-md h-10 items-center mb-5" style="background-color: #062639;">
                    <p class="font-semibold text-white text-lg ml-2">รายการสินค้า</p>
                </div>
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
                        <?php
                        // format number
                        $totalPriceFormat = number_format($totalPrice, 2);
                        ?>
                        <p class="font-semibold text-md"><?php echo "" . $totalPriceFormat . " บาท" ?></p>
                    </div>
                    <div class="flex flex-row justify-between">
                        <p class="font-semibold text-md">ค่าจัดส่ง</p>
                        <p class="font-semibold text-md">0 บาท</p>
                    </div>
                    <div class="flex flex-row justify-between">
                        <p class="font-semibold text-md">Vat 7%</p>
                        <?php
                        $vatFormat = number_format($vat, 2);
                        ?>
                        <p class="font-semibold text-md"><?php echo "" . $vatFormat . " บาท" ?></p>
                    </div>
                    <div class="flex flex-row justify-between">
                        <p class="font-semibold text-md">ยอดสุทธิ</p>
                        <?php
                        $totalPriceVatFormat = number_format($totalPrice + $vat, 2);
                        ?>
                        <p class="font-semibold text-md"><?php echo "" . $totalPriceVatFormat . " บาท" ?></p>
                    </div>
                    <div class="text-center mt-5">
                        <button type="button" onclick="checkForm()" class="p-2 bg-red-500 rounded-lg font-semibold text-white w-4/5 hover:shadow-lg">ยืนยันการสั่งซื้อ</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

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

        //Form Control
        let RecvFNameStatus = false;
        let RecvLNameStatus = false;
        let RecvTelStatus = false;
        let RecvEmailStatus = false;
        let RecvAddrStatus = false;
        let RecvProvinceStatus = false;
        let RecvPostStatus = false;
        let PaymentStatus = false;

        const RecvFNameInput = document.getElementById("RecvFNameInput");
        const RecvLNameInput = document.getElementById("RecvLNameInput");
        const RecvTelInput = document.getElementById("RecvTelInput");
        const RecvEmailInput = document.getElementById("RecvEmailInput");
        const RecvAddrInput = document.getElementById("RecvAddrInput");
        const RecvProvinceInput = document.getElementById("RecvProvinceInput");
        const RecvInputPost = document.getElementById("RecvInputPost");

        const RecvFNameError = document.getElementById('RecvFNameError');
        const RecvTelError = document.getElementById('RecvTelError');
        const RecvEmailError = document.getElementById('RecvEmailError');
        const RecvAddrError = document.getElementById('RecvAddrError');
        const RecvProvinceError = document.getElementById('RecvProvinceError');
        const RecvPostError = document.getElementById('RecvPostError');

        function checkForm() {
            if (RecvFNameInput.value === "") {
                RecvFNameInput.style.borderColor = 'red';
                RecvFNameError.style.display = 'block';
                RecvFNameError.innerHTML = '*กรุณากรอกชื่อ';
                RecvFNameStatus = false;
            } else {
                RecvFNameInput.style.borderColor = 'rgb(156 163 175)';
                RecvFNameError.style.display = 'none';
                RecvFNameStatus = true;
            }

            if (RecvLNameInput.value === "") {
                RecvLNameInput.style.borderColor = 'red';
                RecvLNameError.style.display = 'block';
                RecvLNameError.innerHTML = '*กรุณากรอกนามสกุล';
                RecvLNameStatus = false;
            } else {
                RecvLNameInput.style.borderColor = 'rgb(156 163 175)';
                RecvLNameError.style.display = 'none';
                RecvLNameStatus = true;
            }

            if (RecvTelInput.value === "") {
                RecvTelInput.style.borderColor = 'red';
                RecvTelError.style.display = 'block';
                RecvTelError.innerHTML = '*กรุณากรอกเบอร์โทรศัพท์';
                RecvTelStatus = false;
            } else if (isNaN(RecvTelInput.value)) {
                RecvTelError.style.display = 'block';
                RecvTelError.innerHTML = '*กรุณากรอกเบอร์โทรศัพท์เป็นตัวเลขเท่านั้น';
                RecvTelStatus = false;
            } else if (RecvTelInput.value.length !== 10) {
                RecvTelError.style.display = 'block';
                RecvTelError.innerHTML = '*กรุณากรอกเบอร์โทรศัพท์ให้ครบ 10 หลัก';
                RecvTelStatus = false;
            } else {
                RecvTelInput.style.borderColor = 'rgb(156 163 175)';
                RecvTelError.style.display = 'none';
                RecvTelStatus = true;
            }

            if (RecvEmailInput.value === "") {
                RecvEmailInput.style.borderColor = 'red';
                RecvEmailError.style.display = 'block';
                RecvEmailError.innerHTML = '*กรุณากรอกอีเมล';
                RecvEmailStatus = false;
            } else if (!/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/.test(RecvEmailInput.value)) {
                RecvEmailError.style.display = 'block';
                RecvEmailError.innerHTML = '*กรุณากรอกอีเมลให้ถูกต้อง';
                RecvEmailStatus = false;
            } else {
                RecvEmailInput.style.borderColor = 'rgb(156 163 175)';
                RecvEmailError.style.display = 'none';
                RecvEmailStatus = true;
            }

            if (RecvAddrInput.value === "") {
                RecvAddrInput.style.borderColor = 'red';
                RecvAddrError.style.display = 'block';
                RecvAddrError.innerHTML = '*กรุณากรอกที่อยู่';
                RecvAddrStatus = false;
            } else {
                RecvAddrInput.style.borderColor = 'rgb(156 163 175)';
                RecvAddrError.style.display = 'none';
                RecvAddrStatus = true;
            }

            if (RecvProvinceInput.value === "") {
                RecvProvinceInput.style.borderColor = 'red';
                RecvProvinceError.style.display = 'block';
                RecvProvinceError.innerHTML = '*กรุณากรอกจังหวัด';
                RecvPostStatus = false;
            } else {
                RecvProvinceInput.style.borderColor = 'rgb(156 163 175)';
                RecvProvinceError.style.display = 'none';
                RecvProvinceStatus = true;
            }

            if (RecvInputPost.value === "") {
                RecvInputPost.style.borderColor = 'red';
                RecvPostError.style.display = 'block';
                RecvPostError.innerHTML = '*กรุณากรอกรหัสไปรษณีย์';
                RecvPostStatus = false;
            } else {
                RecvInputPost.style.borderColor = 'rgb(156 163 175)';
                RecvPostError.style.display = 'none';
                RecvPostStatus = true;
            }

            if (document.getElementById('MB').checked === false && document.getElementById('COD').checked === false) {
                document.getElementById('PaymetError').style.display = 'block';
            } else {
                document.getElementById('PaymetError').style.display = 'none';
                PaymentStatus = true;
            }

            if (RecvAddrStatus && RecvEmailStatus && RecvFNameStatus && RecvLNameStatus && RecvPostStatus && RecvProvinceStatus && RecvTelStatus &&PaymentStatus) {
                document.getElementById('OrderControl').submit();
            }

        }
    </script>
</body>

</html>