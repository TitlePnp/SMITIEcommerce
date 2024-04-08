<?php
$role = "SuperAdmin";
require '../../Backend/Authorized/ManageHeader.php';
require '../../Backend/SuperAdmin/GetDataSuperAdmin.php';
require '../../Backend/SuperAdmin/ReportQuey.php';
require '../../Components/ConnectDB.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['ProductType']) && !empty($_POST['ProductType'])) {
        $ProductType = $_POST['ProductType'];

    } else {
        $ProductType = [];
    }
    //iseet conditon to status
    if (isset($_POST['status']) && !empty($_POST['status'])) {
        $status = $_POST['status'];
    } else {
        $status = [];
    }
    $result = getProductReportFilter($ProductType, $status);
} else {
    $result = getAllProdcutReport();
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
        <form id="SearchForm" style="width: 100%" action="StockReport.php" method="post">

            <div class="flex flex-col">
                <div>
                    <p class="font-semibold text-xl">รายงานคลังสินค้า</p>
                </div>
                <div>
                    <div class="flex flex-col">
                        <div class="flex w-10/12">
                            <div>
                                <p class="font-medium my-2">ตัวกรองประเภท</p>
                                <div class="flex items-center">
                                    <?php
                                    $ThaiTypeResult = getAllProductThaiType();
                                    while ($row = $ThaiTypeResult->fetch_assoc()) { ?>
                                        <input type="checkbox" name="ProductType[]" value="<?php echo $row['TypeID'] ?>" class="mx-2" <?php if (isset($ProductType) && in_array($row['TypeID'], $ProductType)) echo "checked"; ?>>
                                        <label for="status_ProductTyoe" class="mx-2"><?php echo $row['ThaiType'] ?></label>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5">
                        <p class="font-medium my-2">ตัวกรองสถานะ</p>
                        <div class="flex items-center">
                            <input type="checkbox" id="status_active" name="status[]" value="Active" class="mx-2" <?php if (isset($status) && in_array("Active", $status)) echo "checked"; ?>>
                            <label for="status_ordered" class="mx-2">Active</label>
                            <input type="checkbox" id="status_inactive" name="status[]" value="Inactive" class="mx-2" <?php if (isset($status) && in_array("Inactive", $status)) echo "checked"; ?>>
                            <label for="status_completed" class="mx-2">Inactive</label>
                            <input type="checkbox" id="status_outstock" name="status[]" value="OutStock" class="mx-2" <?php if (isset($status) && in_array("OutStock", $status)) echo "checked"; ?>>
                            <label for="status_cancel" class="mx-2">OutStock</label>
                        </div>
                    </div>
                </div>

                <div class="flex items-end my-3 justify-between">
                    <button type="button" onclick="Search()" class="bg-green-500 p-2 px-5 text-sm rounded-lg text-white hover:bg-green-600 hover:shadow-lg">ค้นหา</button>
                    <button class="bg-red-500 p-2 rounded-lg text-white hover:bg-red-600 hover:shadow-lg">ส่งออกเป็น PDF</button>
                </div>

                <div>
                    <div class="flex flex-col">

                        <div class="flex flex-col">
                            <table class="table-auto w-full my-5">
                                <thead>
                                    <tr class="bg-gray-400 shadow-lg">
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
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        function Search() {
            document.getElementById("SearchForm").submit();
        }
    </script>

</body>

</html>