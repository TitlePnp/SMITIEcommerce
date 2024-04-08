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

$sameDate = false;
$startDate = $_POST['start_date'];
$endDate = $_POST['end_date'];
if ($startDate == "NONE" && $endDate == "NONE") {
    $result = BestSeller();
    $allReccord = true;
    $sameDate = false;
} else {
    $result = BestSellerByTime($startDate, $endDate);
    //check result value

    if ($startDate === $endDate) {
        $sameDate = true;
    }
    $allReccord = false;
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
            <h2 class="header">รายงานสินค้าขายดีทั้งหมด</h2>
        <?php
        } else if ($sameDate) {
            $startDate = date("d/m/Y", strtotime($startDate));
        ?>
            <h2 class="header">รายงานสินค้าขายดีวันที่ <?php echo $startDate ?></h2>
        <?php
        } else {
            $startDate = date("d/m/Y", strtotime($startDate));
            $endDate = date("d/m/Y", strtotime($endDate));
        ?>
            <h2 class="header">รายงานสินค้าขายดีระหว่างวันที่ <?php echo $startDate ?> ถึง <?php echo $endDate ?></h2>
        <?php
        }
        ?>
    </div>
    <div class="flex justify-center">
        <table class="table-auto w-full my-5">
            <thead>
                <tr class="bg-gray-500 shadow-lg">
                    <th class="border border-gray-300">รหัสสินค้า</th>
                    <th class="border border-gray-300">ชื่อสินค้า</th>
                    <th class="border border-gray-300">จำนวนสินค้า</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $TotalQty = 0;
                while ($row = $result->fetch_assoc()) {
                    $TotalQty += $row['Qty'];
                ?>
                    <tr class="hover:bg-gray-200">
                        <td class="border border-gray-300 text-center px-3 py-4"><?php echo $row['ProID']; ?></td>
                        <td class="border border-gray-300 text-center px-3 py-4"><?php echo $row['ProName']; ?></td>
                        <td class="border border-gray-300 text-center px-3 py-4"><?php echo $row['Qty']; ?></td>
                    </tr>

                <?php
                }
                ?>
                <tr class="hover:bg-gray-200">
                    <td colspan="2" class="border border-gray-300 text-center px-3 py-4">รวม</td>
                    <td class="border border-gray-300 text-center px-3 py-4"><?php echo $TotalQty ?></td>
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