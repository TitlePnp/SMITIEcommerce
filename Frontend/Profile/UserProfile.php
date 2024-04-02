<?php
require_once "../../Backend/Authorized/UserAuthorized.php";
require_once "../../Backend/Authorized/ManageHeader.php";
require_once "../../Backend/UserManage/UserInfo.php";
require '../../vendor/autoload.php';

use Firebase\JWT\Key;
use \Firebase\JWT\JWT;
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
        <div class="flex flex-row h-full">
            <div class="flex flex-col w-full">
                <div class="p-5 shadow-lg rounded-lg w-full flex">
                    <div class="w-4/12 flex justify-center items-center">
                        <i class='bx bxs-user-circle text-7xl'></i>
                    </div>
                    <div class="w-8/12 font-semibold mt-2 text-lg">
                        <p>Joa</p>
                    </div>
                </div>
                <div class="mt-5 p-5">
                </div>
            </div>
            <div class="flex flex-row ml-5 w-8/12">
                <div class="flex flex-col w-full">
                    <div class="p-5 shadow-lg rounded-lg w-full flex">
                        <p>สถานะคำสั่งซื้อ</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>