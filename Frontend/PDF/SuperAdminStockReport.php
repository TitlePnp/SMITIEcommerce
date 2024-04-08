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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['ProductType']) && !isset($_POST['status'])) {
        $result = getAllProdcutReport();
        $allReccord = true;
    } else {
        if (isset($_POST['ProductType']) && !empty($_POST['ProductType'])) {
            $ProductType = $_POST['ProductType'];
            $ShowProductType = implode("','", $ProductType);
            $ShowProductStatus = true;
        } else {
            $ProductType = [];
            $ShowProductStatus = false;
        }

        if (isset($_POST['status']) && !empty($_POST['status'])) {
            $status = $_POST['status'];
            $Showstatus = implode("','", $status);
            $ShowStatusStatus = true;
        } else {
            $status = [];
            $ShowStatusStatus = false;
        }
        $result = getProductReportFilter($ProductType, $status);
        $allReccord = false;
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
            <h2 class="header">รายงานคลังสินค้าทั้งหมด</h2>
        <?php
        } else if ($ShowProductStatus && $ShowStatusStatus) {
        ?>
            <h2 class="header">รายงานคลังสินค้าประเภท <?php echo $ShowProductType ?> สถานะ <?php echo $Showstatus ?></h2>
        <?php
        } else if ($ShowStatusStatus && !$ShowProductStatus) {
        ?>
            <h2 class="header">รายงานคลังสินค้าทั้งหมด สถานะ <?php echo $Showstatus ?></h2>
        <?php
        } else if ($ShowProductStatus && !$ShowStatusStatus) {
        ?>
            <h2 class="header">รายงานคลังสินค้าประเภท <?php echo $ShowProductType ?></h2>
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
                    <th class="border border-gray-300">ประเภท</th>
                    <th class="border border-gray-300">ผู้เขียน</th>
                    <th class="border border-gray-300">ราคา</th>
                    <th class="border border-gray-300">ต้นทุน</th>
                    <th class="border border-gray-300">จำนวนสินค้าในคลัง</th>
                    <th class="border border-gray-300">สถานะ</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = $result->fetch_assoc()) {
                ?>
                    <tr class="hover:bg-gray-200">
                        <td class="border border-gray-300 text-center px-3 py-4"><?php echo $row['ProID']; ?></td>
                        <td class="border border-gray-300 text-center px-3 py-4"><?php echo $row['ProName']; ?></td>
                        <td class="border border-gray-300 text-center px-3 py-4"><?php echo $row['ThaiType']; ?></td>
                        <td class="border border-gray-300 text-center px-3 py-4"><?php echo $row['Author']; ?></td>
                        <td class="border border-gray-300 text-center px-3 py-4"><?php echo $row['PricePerUnit']; ?></td>
                        <td class="border border-gray-300 text-center px-3 py-4"><?php echo $row['CostPerUnit']; ?></td>
                        <td class="border border-gray-300 text-center px-3 py-4"><?php echo $row['StockQty']; ?></td>
                        <td class="border border-gray-300 text-center px-3 py-4"><?php echo $row['Status']; ?></td>
                    </tr>
                <?php
                }
                ?>
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