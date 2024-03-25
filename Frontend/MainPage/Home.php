<?php
session_start();
require '../../Components/HeaderGuest.html';
require '../../vendor/autoload.php';

use Firebase\JWT\Key;
use \Firebase\JWT\JWT;
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

    <title>SMITI SHOP: HOME</title>

    <style>
        * {
            font-family: Kodchasan;
            padding: 0px;
            margin: 0px;
        }

        .container {
            /* padding: 2rem; */
        }

        .slider-wrapper {
            position: relative;
            max-width: 84rem;
            margin: 0 auto;
        }

        .slider {
            display: flex;
            overflow-x: hidden;
            scroll-snap-type: x mandatory;
            scroll-behavior: smooth;
            box-shadow: 0 1.5rem 3rem -0.75rem hsla(0, 0%, 0%, 0.25);
            border-radius: 0.5rem;
        }

        .slider img {
            flex: 1 0 100%;
            scroll-snap-align: start;
            object-fit: cover;
        }

        .slider-nav {
            display: flex;
            column-gap: 1rem;
            position: absolute;
            bottom: 1.25rem;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1;
        }

        .slider-nav a {
            width: 0.5rem;
            height: 0.5rem;
            border-radius: 50%;
            background-color: black;
            opacity: 0.5;
            transition: opacity ease 250ms;
        }

        .slider-nav a:hover {
            opacity: 1;
        }
    </style>

<body>
    <div class="pl-28 pr-28 pt-8">
        <section class="container">
            <div class="slider-wrapper">
                <div class="slider">
                    <img id="banner1" class="w-full" src="../../Pictures//Banner01.png" alt="Banner">
                    <img id="banner2" class="w-full" src="../../Pictures//Banner02.png" alt="Banner">
                    <img id="banner3" class="w-full" src="../../Pictures//Banner03.png" alt="Banner">
                </div>
                <div class="slider-nav">
                    <a href="#banner1" class="w-2 h-2 bg-black-500 rounded-full inline-block mr-1"></a>
                    <a href="#banner2" class="w-2 h-2 bg-black-500 rounded-full inline-block mr-1"></a>
                    <a href="#banner3" class="w-2 h-2 bg-black-500 rounded-full inline-block mr-1"></a>
                </div>
            </div>
        </section>
        <div class="flex pt-8" style="padding-left: 0; padding-right: 0">
            <div class="flex flex-col">
                <h1 class="font-bold text-xl">แนะนำสำหรับคุณ</h1>
                <div class="flex flex-row">
                    <div>
                        <img src="" alt="">
                    </div>
                    <div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const navLinks = document.querySelectorAll('.slider-nav a');

        let currentIndex = 0; // เริ่มจากภาพแรก
        const slides = document.querySelectorAll('.slider img'); // เลือกทุกภาพใน slider
        const totalSlides = slides.length; // นับจำนวนภาพทั้งหมด

        navLinks.forEach((link, index) => {
            link.addEventListener('click', (event) => {
                event.preventDefault(); // Prevent the default action
                currentIndex = index; // Set the current index to the index of the clicked link
                showSlide(currentIndex); // Show the slide at the current index
            });
        });

        function showSlide(index) {
            // ซ่อนภาพทั้งหมด
            slides.forEach(slide => {
                slide.style.display = 'none';
            });

            // แสดงภาพที่ index กำหนด
            slides[index].style.display = 'block';
        }

        function nextSlide() {
            currentIndex = (currentIndex + 1) % totalSlides; // คำนวณ index ภาพถัดไป
            showSlide(currentIndex); // แสดงภาพถัดไป
        }

        // ตั้งเวลาเปลี่ยนภาพอัตโนมัติทุก ๆ 3 วินาที
        setInterval(nextSlide, 4000);

        // เรียกใช้ฟังก์ชันแรกเพื่อแสดงภาพแรกเมื่อเพจโหลด
        showSlide(currentIndex);
    </script>
</body>

</html>

<?php
// $key = "SECRETKEY_SMITIECOM_CLIENT";
// if (isset($_SESSION['tokenJWT'])) {
//     echo "Token is set by JWT";
//     $jwt = $_SESSION['tokenJWT'];
//     $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
//     echo "<br>";
//     echo "Username: " . $decoded->user;
//     echo "<br>";
//     echo "Role: " . $decoded->role;
// }
// else if (isset($_SESSION['tokenGoogle'])) {
//     echo "Token is set by Google";
//     echo "<br>";
//     echo "Username: " . $_SESSION['tokenGoogle'];
//     echo "<br>";
//     echo "Name: " . $_SESSION['name'];
// }
// else {
//     echo "Token is not set";
// }
?>