<?php
  require '../../Backend/Authorized/SuperAdminAuthorized.php';
require '../../Backend/Authorized/ManageHeader.php';
require '../../Backend/SuperAdmin/GetDataSuperAdmin.php';
require '../../Backend/SuperAdmin/ReportQuey.php';
require '../../Components/ConnectDB.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    if (isset($_POST['payment_channel'])) {
        $payment_channel = $_POST['payment_channel'];
        $payment_channel = implode(",", $payment_channel);
        if (isset($_POST['status'])) {
            $status = $_POST['status'];
            $result = getReportInvoiceFromTime($start_date, $end_date, $payment_channel, $status);
        } else {
            $result = getReportInvoiceFromTime($start_date, $end_date, $payment_channel, "None");
        }
    } else {
        $payment_channel = "None";
        if (isset($_POST['status'])) {
            $status = $_POST['status'];
            $result = getReportInvoiceFromTime($start_date, $end_date, $payment_channel, $status);
        } else {
            $result = getReportInvoiceFromTime($start_date, $end_date, $payment_channel, "None");
        }
    }
} else {
    $payment_channel = "None";
    $result = getReportAllTime();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link type="text/css" rel="stylesheet" href="https://cdn.jsdelivr.net/gh/alumuko/vanilla-datetimerange-picker@latest/dist/vanilla-datetimerange-picker.css">
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/gh/alumuko/vanilla-datetimerange-picker@latest/dist/vanilla-datetimerange-picker.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kodchasan:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>SMITI SHOP: HOME</title>
    <style>
        * {
            font-family: Kodchasan;
            margin: 0px;
            padding: 0px;
        }
    </style>
</head>

<body>
    <div class="px-28">
        <div class="flex flex-col">

            <p class="font-semibold text-xl">รายงานคำสั่งซื้อ</p>

            <div>
                <div class="flex flex-col">
                    <form id="SearchForm" style="width: 100%" method="post">
                        <p class="font-medium my-2">ช่วงวันที่</p>
                        <div date-rangepicker class="flex items-center">
                            <div class="relative" style="width: 90%">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-700" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                    </svg>
                                </div>
                                <!-- <input id="datetimerange-input1" type="text" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg block w-full ps-10 py-2" placeholder="วันที่เริ่มต้น" required> -->
                                <input id="datetimerange-input1" type="text" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg block w-full ps-10 py-2" placeholder="วันที่เริ่มต้น" value="<?php echo isset($start_date) ? $start_date : '' ?>" required>
                                <input type="hidden" id="start_date" name="start_date" value="<?php echo isset($start_date) ? $start_date : date('Y-m-d') ?>">
                                <input type="hidden" id="end_date" name="end_date" value="<?php echo isset($end_date) ? $end_date : date('Y-m-d') ?>">
                            </div>
                            <button type="button" onclick="Search()" class="bg-green-300 hover:bg-green-500 rounded-lg text-sm px-5 py-2 ml-3">
                                <img class="h-5 w-auto" src="../../Pictures/search.png" alt="search">
                            </button>
                        </div>
                        <div class="my-2">
                            <div class="flex">

                                <div class="flex w-10/12">
                                    <div>
                                        <p class="font-medium my-2">ตัวกรองการชำระเงิน</p>
                                        <div class="flex items-center">
                                            <?php
                                            if ($payment_channel == "Transfer") { ?>
                                                <input type="checkbox" id="payment_channel_qr" name="payment_channel[]" value="Transfer" class="mx-2" checked>
                                                <label for="payment_channel_qr" class="mx-2">ชำระผ่าน QR Code</label>
                                                <input type="checkbox" id="payment_channel_COD" name="payment_channel[]" value="COD" class="mx-2">
                                                <label for="payment_channel_COD" class="mx-2">ชำระเงินปลายทาง</label>
                                            <?php } else if ($payment_channel == "COD") { ?>
                                                <input type="checkbox" id="payment_channel_qr" name="payment_channel[]" value="Transfer" class="mx-2">
                                                <label for="payment_channel_qr" class="mx-2">ชำระผ่าน QR Code</label>
                                                <input type="checkbox" id="payment_channel_COD" name="payment_channel[]" value="COD" class="mx-2" checked>
                                                <label for="payment_channel_COD" class="mx-2">ชำระเงินปลายทาง</label>
                                            <?php
                                            } else {
                                            ?>
                                                <input type="checkbox" id="payment_channel_qr" name="payment_channel[]" value="Transfer" class="mx-2">
                                                <label for="payment_channel_qr" class="mx-2">ชำระผ่าน QR Code</label>
                                                <input type="checkbox" id="payment_channel_COD" name="payment_channel[]" value="COD" class="mx-2">
                                                <label for="payment_channel_COD" class="mx-2">ชำระเงินปลายทาง</label>
                                            <?php
                                            }
                                            ?>

                                        </div>
                                    </div>
                                    <div class="mx-5">
                                        <p class="font-medium my-2">ตัวกรองสถานะ</p>
                                        <div class="flex items-center">
                                            <input type="checkbox" id="status_ordered" name="status[]" value="Ordered" class="mx-2" <?php if (isset($status) && in_array("Ordered", $status)) echo "checked"; ?>>
                                            <label for="status_ordered" class="mx-2">Ordered</label>
                                            <input type="checkbox" id="status_completed" name="status[]" value="Completed" class="mx-2" <?php if (isset($status) && in_array("Completed", $status)) echo "checked"; ?>>
                                            <label for="status_completed" class="mx-2">Completed</label>
                                            <input type="checkbox" id="status_cancel" name="status[]" value="Cancel" class="mx-2" <?php if (isset($status) && in_array("Cancel", $status)) echo "checked"; ?>>
                                            <label for="status_cancel" class="mx-2">Cancel</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex w-2/12 items-end justify-end">
                                    <button onclick="ExportPDF()" class="bg-red-500 p-2 w-8/12 rounded-lg text-white hover:bg-red-600 hover:shadow-lg">ส่งออกเป็น PDF</button>
                                </div>

                            </div>
                    </form>
                    <div class="flex flex-col">
                        <table class="table-auto w-full my-5">
                            <thead>
                                <tr class="bg-gray-400 shadow-lg">
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

                </div>
            </div>
        </div>
    </div>
    </div>

    <script>
        var QrChannel = document.getElementById('payment_channel_qr');
        var CODChannel = document.getElementById('payment_channel_COD');
        var QrChannelSelected = false;
        var CODChannelSelected = false;

        QrChannel.addEventListener('change', function() {
            if (QrChannel.checked) {
                CODChannel.checked = false;
                QrChannelSelected = true;
                CODChannelSelected = false;
            } else {
                QrChannelSelected = false;
            }
        });

        CODChannel.addEventListener('change', function() {
            if (CODChannel.checked) {
                QrChannel.checked = false;
                CODChannelSelected = true;
                QrChannelSelected = false;
            } else {
                CODChannelSelected = false;
            }
        });

        var SearchForm = document.getElementById('SearchForm');

        function Search() {
            SearchForm.action = "OrderReport.php";
            SearchForm.submit();
        }

        function ExportPDF() {
            SearchForm.action = "../PDF/SuperAdminOrderReport.php";
            SearchForm.submit();
        }

        window.addEventListener("load", function(event) {
            var tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            var minDate = new Date('2023-07-01');
            var todayDate = new Date();
            var startDate = document.getElementById('start_date').value ? new Date(document.getElementById('start_date').value) : todayDate;
            var endDate = document.getElementById('end_date').value ? new Date(document.getElementById('end_date').value) : todayDate;
            new DateRangePicker('datetimerange-input1', {
                minDate: minDate,
                maxDate: tomorrow,
                startDate: startDate,
                endDate: endDate
            });
        });

        window.addEventListener('apply.daterangepicker', function(ev) {
            var startDate = ev.detail.startDate.format('YYYY-MM-DD');
            var endDate = ev.detail.endDate.format('YYYY-MM-DD');
            document.getElementById('start_date').value = startDate;
            document.getElementById('end_date').value = endDate;
        });
    </script>

</body>

</html>