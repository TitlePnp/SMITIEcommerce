<?php
//   require '../../Backend/Authorized/UserAuthorized.php';
//   include '../../Backend/Export/ReceiptDetail.php';
require "../../Components/ConnectDB.php";
require "../../Backend/SuperAdmin/ReportQuey.php";
require "../../vendor/autoload.php";
$defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
$fontDirs = $defaultConfig['fontDir'];
$defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
$fontData = $defaultFontConfig['fontdata'];
$mpdf = new \Mpdf\Mpdf([
    'mode' => 'utf-8',
    'format' => 'A4',
    'fontDir' => array_merge($fontDirs, [
        __DIR__ . '/tmp',
    ]),
    'fontdata' => $fontData + [ // lowercase letters only in font key
        'kodchasan' => [
            'R' => 'TH Kodchasal.ttf',
            'I' => 'TH Kodchasal Italic.ttf',
            'B' => 'TH Kodchasal Bold.ttf',
            'BI' => 'TH Kodchasal Bold Italic.ttf',
        ]
    ],
    'default_font' => 'kodchasan'
]);
ob_start();
$ShowPaymentStatus = false;
$ShowStatusStatus = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $allReccord = false;
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    if ($start_date == "" && $end_date == "") {
        $payment_channel = "None";
        $result = getReportAllTime();
        $allReccord = true;
    }
    else if (isset($_POST['payment_channel'])) {
        $payment_channel = $_POST['payment_channel'];
        $payment_channel = implode(",", $payment_channel);
        $ShowPaymentStatus = true;
        $ShowPayment =  $payment_channel;
        if (!empty($_POST['status'])) {
            $status = $_POST['status'];
            $ShowStatus = implode(",", $status);
            $result = getReportInvoiceFromTime($start_date, $end_date, $payment_channel, $status);
            $ShowStatusStatus = true;
        } else {
            $result = getReportInvoiceFromTime($start_date, $end_date, $payment_channel, "None");
            $ShowStatusStatus = false;
        }
    } else {
        $payment_channel = "None";
        $ShowPaymentStatus = false;
        if (isset($_POST['status'])) {
            $status = $_POST['status'];
            $result = getReportInvoiceFromTime($start_date, $end_date, $payment_channel, $status);
            $ShowStatus = implode(",", $status);
            $ShowStatusStatus = true;
        } else {
            $result = getReportInvoiceFromTime($start_date, $end_date, $payment_channel, "None");
            $ShowPaymentStatus = false;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kodchasan:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    <title></title>
    <style>
        * {
            font-family: 'Kodchasan';
        }

        body {
            text-align: center;
            color: #000;
        }

        @media only screen and (max-width: 600px) {
            .invoice-box table tr.top table td {
                width: 100%;
                display: block;
                text-align: center;
            }

            .invoice-box table tr.information table td {
                width: 100%;
                display: block;
                text-align: center;
            }
        }

        .header {
            font-size: 24px;
            font-weight: bold;
            color: #000;
        }

        .flex {
            display: flex;
        }

        .bg-test {
            background-color: red;
        }

        .justify-center {
            justify-content: center;
        }

        .text-center {
            text-align: center;
        }

        .w-full {
            width: 100%;
        }

        .font-bold {
            font-weight: bold;
        }

        .bg-gray-500 {
            background-color: rgb(107, 114, 128);
        }

        .custom-width {
            width: 50px;
        }
    </style>
</head>

<body>
    <div class="Report-box">
        <h1 class="headText">รายงาน</h1>
        <?php
        if ($allReccord) {
        ?>
            <h2 class="header">รายงานคำสั่งซื้อทั้งหมด</h2>
        <?php
        } else if ($ShowPaymentStatus && $ShowStatusStatus) {
        ?>
            <h2 class="header">รายงานคำสั่งซื้อประเภทการชำระ <?php echo $ShowPayment ?> สถานะ <?php echo $ShowStatus ?></h2>
        <?php
        } else if ($ShowStatusStatus && !$ShowPaymentStatus) {
        ?>
            <h2 class="header">รายงานคำสั่งซื้อ สถานะ <?php echo $ShowStatus ?></h2>
        <?php
        } else if ($ShowPaymentStatus && !$ShowStatusStatus) {
        ?>
            <h2 class="header">รายงานคำสั่งซื้อค้าประเภทการชำระ <?php echo $ShowPaymentStatus ?></h2>
        <?php
        }
        ?>
    </div>
    <div class="flex justify-center">
        <table class="table-auto w-full my-5">
            <thead>
                <tr class="bg-gray-500 shadow-lg">
                    <th class="border border-gray-300 py-5">เลขที่คำสั่งซื้อ</th>
                    <th class="border border-gray-300 py-5">วันที่สั่งซื้อ</th>
                    <th class="border border-gray-300 py-5">ช่องทางการชำระ</th>
                    <th class="border border-gray-300 py-5">สถานะ</th>
                    <th class="border border-gray-300 py-5">ราคา</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $FinalTotalPrice = 0;
                while ($row = $result->fetch_assoc()) {
                    if ($row['Channel'] == 'COD') {
                        $Channel = 'เก็บเงินปลายทาง';
                    } else if ($row['Channel'] == "Transfer") {
                        $Channel = 'ชำระผ่าน QR code';
                    }
                ?>
                    <tr class="hover:bg-gray-200">
                        <td class="border border-gray-300 text-center px-3 py-4"><?php echo $row['InvoiceID']; ?></td>
                        <td class="border border-gray-300 text-center px-3 py-4"><?php echo $row['StartDate']; ?></td>
                        <td class="border border-gray-300 text-center px-3 py-4"><?php echo $Channel ?></td>
                        <?php
                        $totalPrice = $row['TotalPrice'] + $row['Vat'];
                        ?>
                        <td class="border border-gray-300 text-center"><?php echo $row['Status']; ?></td>
                        <td class="border border-gray-300 text-center"><?php echo number_format($totalPrice, 2) ?> ฿</td>
                    </tr>
                <?php
                    $FinalTotalPrice += $totalPrice;
                }
                ?>
                <tr class="hover:bg-gray-200">
                    <td colspan="4" class="border border-gray-300 text-center px-3 py-4">รวม</td>
                    <td class="border border-gray-300 text-center"><?php echo number_format($FinalTotalPrice, 2) ?> ฿</td>
                </tr>
            </tbody>
        </table>
    </div>

</body>

</html>
<?php
$file = "Report_.pdf";
$html = ob_get_contents();
ob_end_clean();
$mpdf->WriteHTML($html);
$mpdf->Output($file, 'I');
echo '<script>window.open("' . $file . '", "_blank");</script>';
?>