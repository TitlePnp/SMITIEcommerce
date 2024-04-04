<?php
require_once "../../Backend/Authorized/UserAuthorized.php";
require_once "../../Backend/Authorized/ManageHeader.php";
require_once "../../Backend/UserManage/UserInfo.php";
require_once "../../Backend/OrderManage/OrderQuery.php";
require '../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../Components', 'config.env');
$dotenv->load();

use Firebase\JWT\Key;
use \Firebase\JWT\jwt;

$key = $_ENV['JWT_KEY'];


if (isset($_SESSION["tokenJWT"])) {
    $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
    $CusID = $decoded->cusid;
    $userInfo = getUserInfoFromCusID($CusID);
    $isMember = true;
} else if (isset($_SESSION["tokenGoogle"])) {
    $userInfo = getGoogleUserInfo($_SESSION["tokenGoogle"]);
    $CusID = $userInfo['CusID'];
    $isMember = true;
} else {
    header("Location: ../MainPage/Home.php");
}

$ordersPerPage = 5;
$countOrders = countUserOrder($CusID);
$pages = ceil($countOrders / $ordersPerPage);
if (!isset($_GET['page'])) {
    $page = 1;
} else {
    $page = $_GET['page'];
}
$startOrder = ($page - 1) * $ordersPerPage;
$orders = showOrderSplitPage($CusID, $startOrder, $ordersPerPage);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com/3.4.3"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kodchasan:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <title>SMITI:User Profile</title>
    <style>
        * {
            font-family: 'Kodchasan';
            padding: 0px;
            margin: 0px;
        }
    </style>
</head>

<body>
    <div class="px-28 py-5">
        <div class="mb-5 flex flex-col">
            <div class="mb-5">
                <a href="../MainPage/Home.php" class="hover:text-blue-800 text-blue-500 font-semibold"><i class='bx bx-arrow-back mr-2'></i>กลับไปหน้าร้านค้า</a>
            </div>
            <div class="">
                <p class="font-bold text-bold text-2xl mb-2">บัญชีผู้ใช้งาน</p>
            </div>
        </div>
        <div class="flex flex-row h-full">
            <div class="flex flex-col w-full w-4/12">
                <div class="p-5 shadow-lg rounded-lg w-full flex">
                    <div class="w-4/12 flex justify-center items-center">
                        <i class='bx bxs-user-circle text-7xl'></i>
                    </div>
                    <div class="w-8/12 font-semibold mt-2 text-lg">
                        <?php
                        echo "<p>{$userInfo['CusFName']}</p>";
                        echo "<p>{$userInfo['CusLName']}</p>";
                        ?>
                    </div>
                </div>
                <div class="p-5 mt-5 shadow-lg rounded-lg w-full flex">
                    <div class="w-full flex flex-col">
                        <div class="flex justify-between">
                            <h1 class="font-semibold text-lg mb-2">ข้อมูลผู้ใช้</h1>
                            <button class="text-sm hover:text-blue-800 text-blue-500">แก้ไขข้อมูล<i class='bx bxs-edit-alt ml-2'></i></button>
                        </div>
                        <div class="flex flex-col">
                            <div class="flex flex-row w-full items-center">
                                <p class="font-semibold 6/12 text-sm">ชื่อผู้ใช้:</p>
                                <input type="text" class="11/12 border-gray-300 ml-2" value="<?php echo $userInfo['UserName']; ?>" disabled>
                            </div>
                            <div class="flex flex-row w-full items-center">
                                <p class="font-semibold 6/12 text-sm">ชื่อ:</p>
                                <input type="text" class="10/12 border-gray-300 ml-2" value="<?php echo $userInfo['CusFName']; ?>" disabled>
                            </div>
                            <div class="flex flex-row w-full items-center">
                                <p class="font-semibold 6/12 text-sm">นามสกุล:</p>
                                <input type="text" class="10/12 border-gray-300 ml-2" value="<?php echo $userInfo['CusFName']; ?>" disabled>
                            </div>
                            <div class="flex flex-row w-full items-center">
                                <p class="font-semibold 6/12 text-sm">เพศ:</p>
                                <?php
                                if ($userInfo['Sex'] == 'M') {
                                    echo "<input type='text' class='10/12 border-gray-300 ml-2' value='ชาย' disabled>";
                                } else if ($userInfo['Sex'] == 'F') {
                                    echo "<input type='text' class='10/12 border-gray-300 ml-2' value='หญิง' disabled>";
                                } else {
                                    echo "<input type='text' class='10/12 border-gray-300 ml-2' value='ไม่ระบุ' disabled>";
                                }
                                ?>
                            </div>
                            <div class="flex flex-row w-full items-center">
                                <p class="font-semibold 6/12 text-sm">เบอร์โทรศัพท์:</p>
                                <input type="text" class="10/12 border-gray-300 ml-2" value="<?php echo $userInfo['Tel']; ?>" disabled>
                            </div>
                            <div class="flex flex-row w-full items-center">
                                <p class="font-semibold 6/12 text-sm">อีเมล:</p>
                                <input type="text" class="10/12 border-gray-300 ml-2" value="<?php echo $userInfo['Email']; ?>" disabled>
                            </div>
                        </div>
                        <div class="flex justify-center">
                            <button id="confirmEditUserInfo" type="button" class="my-10 bg-green-500 w-3/5 h-2/5 rounded-lg font-semibold hover:shadow-md" style="display: none;">ยืนยันการแก้ไขข้อมูล</button>
                        </div>
                    </div>
                </div>

                <div class="p-5 mt-5 shadow-lg rounded-lg w-full flex">
                    <div class="w-full flex flex-col">
                        <div class="flex justify-between">
                            <h1 class="font-semibold text-lg mb-2">ที่อยู่</h1>
                            <button class="text-sm hover:text-blue-800 text-blue-500">แก้ไขข้อมูล<i class='bx bxs-edit-alt ml-2'></i></button>
                        </div>
                        <div class="flex flex-col">
                            <?php
                            $resultAddr = getAllAddress($CusID);
                            $count = 1;
                            while ($allAddr = $resultAddr->fetch_assoc()) {
                                //create radio address and when click div will select address
                                if ($count == 1) {
                                    echo "<div class='flex flex-row w-full items-center bg-gray-100 hover:bg-gray-200 py-5 px-2 rounded-md'>";
                                    echo "<input type='radio' class='6/12 border-gray-300 mr-2' value='{$allAddr['Address']}' disabled checked>";
                                    echo "<p class='font-semibold 6/12 text-sm'>ที่อยู่:</p>";
                                    echo "<p class='10/12 border-gray-300 ml-2'>{$allAddr['Address']}, {$allAddr['Province']} {$allAddr['Postcode']}<p>";
                                    echo "<h1 class='text-sm text-end ml-5 text-red-500'>ค่าเริ่มต้น</p>";
                                    echo "</div>";
                                } else {
                                    echo "<div class='flex flex-row w-full items-center hover:bg-gray-100 py-5 px-2 rounded-md'>";
                                    echo "<input type='radio' class='6/12 border-gray-300 mr-2' value='{$allAddr['Address']}' disabled>";
                                    echo "<p class='font-semibold 6/12 text-sm'>ที่อยู่:</p>";
                                    // echo "<div class='flex'>";
                                    // echo "<input type='text' class='10/12 border-gray-300 ml-2' value='{$orderList['Address']}' disabled>";
                                    echo "<p class='10/12 border-gray-300 ml-2'>{$allAddr['Address']}, {$allAddr['Province']} {$allAddr['Postcode']}<p>";
                                    // echo "</div>";
                                    echo "</div>";
                                }

                                $count++;
                            }


                            ?>
                        </div>
                        <div class="flex justify-center">
                            <button id="confirmEditUserInfo" type="button" class="my-10 bg-green-500 w-3/5 h-2/5 rounded-lg font-semibold hover:shadow-md" style="display: none;">ยืนยันการแก้ไขข้อมูล</button>
                        </div>
                    </div>
                </div>

            </div>
            <div class="flex flex-row ml-5 w-8/12">
                <div class="flex flex-col w-full p-5 shadow-lg rounded-lg">
                    <div class="">
                        <p class="text-lg font-semibold">สถานะคำสั่งซื้อ</p>
                    </div>
                    <div>
                        <?php
                        $result = getOrderDetail($CusID);
                        if ($result->num_rows == 0) {
                            echo "<div class='flex flex-col h-full items-center justify-center'>";
                            echo "<p class='mt-5'>ยังไม่มีคำสั่งซื้อ</p>";
                            echo "<a href='../MainPage/Home.php' class='mt-5 bg-blue-500 text-white px-3 py-1 rounded-md hover:bg-blue-700'>เลือกซื้อสินค้า</a>";
                            echo "</div>";
                        } else if ($result->num_rows != 0) {
                            while ($order = $result->fetch_assoc()) {
                                if ($order['Status'] == 'Ordered') {
                                    // OrderedOrder($order, $order['InvoiceID']);
                                    echo "<form action='../OrderStatus/OrderStatus.php' method='POST'>";
                                    $result2 = getOrderListDetail($order['InvoiceID']);
                                    echo '<ul role="list" class="divide-y divide-gray-100 hover:bg-gray-100 rounded-lg px-2">';
                                    echo '    <li class="flex justify-between gap-x-6 py-5">';
                                    echo '        <div class="flex min-w-0 gap-x-4">';
                                    echo '        <div class="flex justify-center items-center">';
                                    echo "            <i class='bx bxs-cart-alt text-2xl text-blue-400' ></i>";
                                    echo "           </div>";
                                    echo '            <div class="min-w-0 flex-auto">';
                                    echo "                <p class='text-sm font-semibold leading-6 text-gray-900'>เลขคำสั่งซื้อ: {$order['InvoiceID']}</p>";

                                    echo "                 <div class='flex flex-row'>";
                                    $count = 0;
                                    while ($orderList = $result2->fetch_assoc()) {
                                        $count++;
                                        if ($count > 3) {
                                            echo "<p class='mt-1 truncate text-sm text-gray-500 mr-1'>...</p>";
                                            break;
                                        }
                                        echo "<p class='mt-1 truncate text-sm text-gray-500 mr-1'>{$orderList['ProName']}, </p>";
                                    };
                                    echo "                 </div>";
                                    echo '            </div>';
                                    echo '        </div>';
                                    echo '        <div class="hidden shrink-0 sm:flex sm:flex-col sm:items-end">';
                                    echo "<div class='flex'>";
                                    echo "           <p class='text-sm leading-6 text-gray-900 mr-2'>สถานะ:</p>";
                                    echo "           <p class='text-sm leading-6  text-blue-500'>{$order['Status']}</p>";
                                    echo "</div>";
                                    echo '            <button type="submit"><p class="mt-1 text-xs leading-5 hover:text-blue-500 cursor-pointer text-gray-500">กดเพื่อดูรายละเอียดเพิ่มเติม</p></button>';
                                    echo "<input type='hidden' name='invoiceID' value='" . $order['InvoiceID'] . "'>";
                                    echo '        </div>';
                                    echo '    </li>';
                                    echo '</ul>';
                                    echo "</form>";
                                } else if ($order['Status'] == 'Completed') {
                                    // CompleteOrder($order, $order['InvoiceID']);
                                    $result2 = getOrderListDetail($order['InvoiceID']);
                                    echo "<form action='../OrderStatus/OrderStatus.php' method='POST'>";
                                    echo '<ul role="list" class="divide-y divide-gray-100 hover:bg-gray-100 rounded-lg px-2">';
                                    echo '    <li class="flex justify-between gap-x-6 py-5">';
                                    echo '        <div class="flex min-w-0 gap-x-4">';
                                    echo '        <div class="flex justify-center items-center">';
                                    echo "            <i class='bx bx-check text-3xl text-green-300'></i>";
                                    echo "           </div>";
                                    echo '            <div class="min-w-0 flex-auto">';
                                    echo "                <p class='text-sm font-semibold leading-6 text-gray-900'>เลขคำสั่งซื้อ: {$order['InvoiceID']}</p>";
                                    echo "                 <div class='flex flex-row'>";
                                    $count = 0;
                                    while ($orderList = $result2->fetch_assoc()) {
                                        $count++;
                                        if ($count > 3) {
                                            echo "<p class='mt-1 truncate text-sm text-gray-500 mr-1'>...</p>";
                                            break;
                                        }
                                        echo "<p class='mt-1 truncate text-sm text-gray-500 mr-1'>{$orderList['ProName']}, </p>";
                                    };
                                    echo "                 </div>";
                                    echo '            </div>';
                                    echo '        </div>';
                                    echo '        <div class="hidden shrink-0 sm:flex sm:flex-col sm:items-end">';
                                    echo "<div class='flex'>";
                                    echo "           <p class='text-sm leading-6 text-gray-900 mr-2'>สถานะ:</p>";
                                    echo "           <p class='text-sm leading-6 text-green-500'>{$order['Status']}</p>";
                                    echo "</div>";
                                    echo '            <button type="submit"><p id="seeStatus" class="mt-1 text-xs leading-5 hover:text-blue-500 cursor-pointer text-gray-500">กดเพื่อดูรายละเอียดเพิ่มเติม</p></button>';
                                    echo "<input type='hidden' name='invoiceID' value='" . $order['InvoiceID'] . "'>";
                                    echo '        </div>';
                                    echo '    </li>';
                                    echo '</ul>';
                                    echo "</form>";
                                } else if ($order['Status'] == 'Cancel') {
                                    // CancelOrder($order, $order['InvoiceID']);
                                    echo "<form action='../OrderStatus/OrderStatus.php' method='POST'>";
                                    $result2 = getOrderListDetail($order['InvoiceID']);
                                    echo '<ul role="list" class="divide-y divide-gray-100 hover:bg-gray-100 rounded-lg px-2">';
                                    echo '    <li class="flex justify-between gap-x-6 py-5">';
                                    echo '        <div class="flex min-w-0 gap-x-4">';
                                    echo '        <div class="flex justify-center items-center">';
                                    echo "            <i class='bx bx-x text-3xl text-red-700'></i>";
                                    echo "           </div>";
                                    echo '            <div class="min-w-0 flex-auto">';
                                    echo "                <p class='text-sm font-semibold leading-6 text-gray-900'>เลขคำสั่งซื้อ: {$order['InvoiceID']}</p>";
                                    echo "                 <div class='flex flex-row'>";
                                    $count = 0;
                                    while ($orderList = $result2->fetch_assoc()) {
                                        $count++;
                                        if ($count > 3) {
                                            echo "<p class='mt-1 truncate text-sm text-gray-500 mr-1'>...</p>";
                                            break;
                                        }
                                        echo "<p class='mt-1 truncate text-sm text-gray-500 mr-1'>{$orderList['ProName']}, </p>";
                                    };
                                    echo "                 </div>";
                                    echo '            </div>';
                                    echo '        </div>';
                                    echo '        <div class="hidden shrink-0 sm:flex sm:flex-col sm:items-end">';
                                    echo "<div class='flex'>";
                                    echo "           <p class='text-sm leading-6 text-gray-900 mr-2'>สถานะ:</p>";
                                    echo "           <p class='text-sm leading-6 text-red-500'>{$order['Status']}</p>";
                                    echo "</div>";
                                    echo '            <button type="submit"><p id="seeStatus" class="mt-1 text-xs leading-5 hover:text-blue-500 cursor-pointer text-gray-500">กดเพื่อดูรายละเอียดเพิ่มเติม</p></button>';
                                    echo "<input type='hidden' name='invoiceID' value='" . $order['InvoiceID'] . "'>";
                                    echo '        </div>';
                                    echo '    </li>';
                                    echo '</ul>';
                                    echo "</form>";
                                }
                            }
                        }
                        ?>
                    </div>
                    <div class="mt-6 flex justify-center">
                        <div class="flex">
                            <?php for ($i = 1; $i <= $pages; $i++) {
                                if ($i == $page) { ?>
                                    <a href="?page=<?php echo $i; ?>" class="mx-1 px-3 py-1 bg-[#062639] rounded-md text-white"><?php echo $i; ?></a>
                                <?php } else { ?>
                                    <a href="?page=<?php echo $i; ?>" class="mx-1 px-3 py-1 bg-gray-200 rounded-md"><?php echo $i; ?></a>
                            <?php }
                            } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>