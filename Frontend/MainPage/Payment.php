<?php
require_once "../../Backend/Authorized/UserAuthorized.php";
require_once "../../Backend/Authorized/ManageHeader.php";
require_once "../../Backend/CartQuery/CartDetail.php";
require_once "../../Backend/UserManage/UserInfo.php";
require_once "../../Backend/OrderManage/GetOrderInfo.php";
require '../../vendor/autoload.php';

use Farzai\PromptPay\Generator;
use Firebase\JWT\Key;
use \Firebase\JWT\JWT;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kodchasan:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <title>SMITI: Payment</title>

    <style>
        * {
            font-family: 'Kodchasan';
            margin: 0px;
            padding: 0px;
        }
    </style>
</head>

<body class="">
    <?php
    $invoiceDetail = getInvoiceInfo($_SESSION['InvoiceID']);
    ?>
    <section class="h-full text-gray-600 mb-10">
        <div class="mx-auto flex flex-col max-w-3xl flex-wrap justify-center rounded-lg bg-white px-16 py-10 shadow-lg">
            <div class="flex">
                <p class="font-bold text-2xl text-black">ชำระผ่าน Qr code</p>
            </div>
            <div class="flex justify-between items-center">
                <p>เลขคำสั่งซื้อ: <?php echo $_SESSION['InvoiceID'] ?></p>
                <button class="block rounded-md border bg-red-500 px-4 py-2 text-white outline-none hover:shadow-md"><i class='bx bxs-file-pdf mr-2' style='color:#ffffff' ></i>ใบแจ้งหนี้</button>
            </div>
            <div class="flex flex-row mt-5 mx-auto">
                <!-- QR Code Number Account & Uploadfile -->
                <div class="flex-wrap md:flex">
                    <div class="mx-auto">
                        <?php
                        $generator = new Generator();
                        $qrCode = $generator->generate(
                            target: "098-888-8888",
                            amount: $invoiceDetail['TotalPrice'] + $invoiceDetail['Vat']
                        );
                        ?>
                        <img class="mx-auto h-32 w-52 md:mt-0" src="../../Pictures/PromptPay.png" alt="">
                        <div class="rounded-lg border p-2">
                            <?php echo '<img class="mx-auto h-38 w-40 p-2 md:mt-0" src="' . $qrCode->asDataUri() . '" />'; ?>
                        </div>
                        <div>
                            <h1 class="font-laonoto mt-4 text-center text-xl font-bold">SMITI SHOP</h1>
                            <p class="mt-2 text-center font-semibold text-gray-600">กรุณาโอนภายใน </p>
                            <p id="countdown" class="mt-1 text-center font-medium text-red-500"></p>
                        </div>
                        <!-- component -->
                    </div>
                    <!-- Step Checkout -->
                    <div class="mt-10 max-w-sm md:ml-10 md:w-2/3 mx-auto">
                        <div class="relative flex pb-12">
                            <div class="absolute inset-0 flex h-full w-10 items-center justify-center">
                                <div class="pointer-events-none h-full w-1 bg-gray-200"></div>
                            </div>
                            <div class="relative z-10 inline-flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-blue-500 text-white">
                                <!-- <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="h-5 w-5" viewBox="0 0 24 24">
                                    <i class='bx bxs-mobile'></i>
                                </svg> -->
                                <!-- <i class='bx bxs-mobile text-2xl'></i> -->
                                <i class='bx bx-wallet text-2xl' style='color:#ffffff'></i>
                            </div>
                            <div class="flex-grow pl-4">
                                <h2 class="title-font mb-1 text-sm tracking-wider font-semibold text-black">STEP 1</h2>
                                <p class="font-laonoto leading-relaxed">
                                    ชำระเงินด้วยการสแกน <b>QR CODE</b><br>
                                    ผ่านแอพพลิเคชั่น
                                </p>
                            </div>
                        </div>
                        <div class="relative flex pb-12">
                            <div class="absolute inset-0 flex h-full w-10 items-center justify-center">
                                <div class="pointer-events-none h-full w-1 bg-gray-200"></div>
                            </div>
                            <div class="relative z-10 inline-flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-blue-500 text-white">
                                <i class='bx bx-mobile-alt text-2xl' style='color:#ffffff'></i>
                            </div>
                            <div class="flex-grow pl-4">
                                <h2 class="title-font mb-1 text-sm tracking-wider font-semibold text-black">STEP 2</h2>
                                <p class="font-laonoto leading-relaxed">จัดเตรียมหลักฐานการชำระเงิน เช่น<br><b>สลิปการโอนเงิน</b>. และกดปุ่มแจ้งการชำระเพื่อแจ้งการชำระเงิน</p>
                            </div>
                        </div>
                        <div class="relative flex pb-12">
                            <div class="relative z-10 inline-flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-blue-500 text-white">
                                <i class='bx bx-check text-2xl'></i>
                            </div>
                            <div class="flex-grow pl-4">
                                <h2 class="title-font mb-1 text-sm tracking-wider font-semibold text-black">STEP 3</h2>
                                <p class="font-laonoto leading-relaxed">
                                    หลังจากแจ้งการชำระเงิน กรุณารอเจ้าหน้าที่ตรวจสอบ คุณสามารถตรวจสอบสถานะการชำระเงินได้โดยไปที่หน้า <span> <b>Profile</b></span>. หรือรอรับการแจ้งเตือนผ่านทางอีเมล
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-between w-full">
                        <button class="ml-5 block rounded-md border my-5 bg-blue-500 px-6 py-2 text-white outline-none hover:shadow-md">แจ้งการชำระเงิน</button>
                    </div>
                    <div class="mt-5">
                        <p class="font-laonoto text-sm text-gray-600">*หมายเหตุ: หากไม่ชำระภายในเวลาที่กำหนด ระบบจะยกเลิกการสั่งซื้อโดยอัตโนมัติ</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        var count = 1800; // 30 minutes
        var countdownElement = document.getElementById('countdown');

        var countdown = setInterval(function() {
            var minutes = Math.floor(count / 60);
            var seconds = count % 60;
            countdownElement.innerText = minutes + " นาที " + seconds + " วินาที";
            count--;

            if (count < 0) {
                clearInterval(countdown);
                countdownElement.innerText = "หมดเวลา";
            }
        }, 1000);
    </script>
</body>

</html>