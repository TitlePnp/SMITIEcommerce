<?php
$role = "SuperAdmin";
require '../../Backend/Authorized/ManageHeader.php';
require '../../Backend/SuperAdmin/GetDataSuperAdmin.php';
require '../../Backend/SuperAdmin/ReportQuey.php';
require '../../Components/ConnectDB.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    var_dump($start_date, $end_date);
    $result = getReportByDay($start_date, $end_date);
} else {
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
            <div>
                <p class="font-semibold text-xl">รายงานคำสั่งซื้อ</p>
            </div>
            <div>
                <div class="flex flex-col">
                    <form style="width: 100%" action="OrderReportSort.php" method="post">
                        <p class="font-medium my-2">ช่วงวันที่</p>
                        <div date-rangepicker class="flex items-center">
                            <div class="relative" style="width: 90%">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-700" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                    </svg>
                                </div>
                                <input id="datetimerange-input1" type="text" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg block w-full ps-10 py-2" placeholder="วันที่เริ่มต้น" required>
                                <input type="hidden" id="start_date" name="start_date">
                                <input type="hidden" id="end_date" name="end_date">
                            </div>
                            <button type="button" onclick="checkVal()" class="bg-green-300 hover:bg-green-500 rounded-lg text-sm px-5 py-2 ml-3">
                                <img class="h-5 w-auto" src="../../Pictures/search.png" alt="search">
                            </button>
                        </div>
                    </form>
                    <span id="ValError" class="text-sm text-red-500" style="display: none;">test</span>

                    <div class="flex flex-col">
                        <table class="table-auto w-full my-5">
                            <thead>
                                <tr class="bg-gray-400 shadow-lg">
                                    <th class="border border-gray-300">เลขที่คำสั่งซื้อ</th>
                                    <th class="border border-gray-300">วันที่สั่งซื้อ</th>
                                    <th class="border border-gray-300">ช่องทางการชำระ</th>
                                    <th class="border border-gray-300">ราคา</th>
                                    <th class="border border-gray-300">สถานะ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while ($row = $result->fetch_assoc()) {
                                ?>
                                    <tr class="hover:bg-gray-200">
                                        <td class="border border-gray-300 text-center px-3 py-4"><?php echo $row['InvoiceID']; ?></td>
                                        <td class="border border-gray-300 text-center px-3 py-4"><?php echo $row['StartDate']; ?></td>
                                        <td class="border border-gray-300 text-center px-3 py-4"><?php echo $row['Channel']; ?></td>
                                        <?php
                                        $totalPrice = $row['TotalPrice'] + $row['Vat'];
                                        $totalPrice = number_format($totalPrice, 2);
                                        ?>
                                        <td class="border border-gray-300 text-center"><?php echo $totalPrice; ?></td>
                                        <td class="border border-gray-300 text-center"><?php echo $row['Status']; ?></td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        function checkVal() {
            var startDate = document.getElementById('start_date').value;
            var endDate = document.getElementById('end_date').value;
            if (startDate == "" && endDate == "") {
                var today = new Date();
                var dd = String(today.getDate()).padStart(2, '0');
                var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                var yyyy = today.getFullYear();
                today = yyyy + '-' + mm + '-' + dd;
                document.getElementById('start_date').value = today;
                document.getElementById('end_date').value = today;
                document.querySelector('form').submit();
            } else {
                document.querySelector('form').submit();
            }
        }

        window.addEventListener("load", function(event) {
            var tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            var minDate = new Date('2023-07-01');
            new DateRangePicker('datetimerange-input1', {
                minDate: minDate,
                maxDate: tomorrow,
            });
        });

        window.addEventListener('apply.daterangepicker', function(ev) {
            var startDate = ev.detail.startDate.format('YYYY-MM-DD');
            var endDate = ev.detail.endDate.format('YYYY-MM-DD');
            document.getElementById('start_date').value = startDate;
            document.getElementById('end_date').value = endDate;
        });
    </script>
    </script>

</body>

</html>