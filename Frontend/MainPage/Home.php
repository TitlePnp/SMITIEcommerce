<?php
require '../../Backend/Authorized/UserAuthorized.php';
require '../../Backend/Authorized/ManageHeader.php';
require '../../vendor/autoload.php';
require_once "../../Backend/ProductQuery/ProductInfo.php";
use Firebase\JWT\Key;
use \Firebase\JWT\JWT;
?>

<!DOCTYPE html>
<html>

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kodchasan:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>


    <title>SMITI SHOP: HOME</title>

    <style>
        * {
            font-family: Kodchasan;
            padding: 0px;
            margin: 0px;
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

        /* ---------------------------------------------- Product section ---------------------------------------------- */
        .product-container::-webkit-scrollbar {
            display: none;
        }

        .product-card {
            flex: 0 0 auto;
        }

        .addCartBtn {
            transform: translateX(-50%);
            transition: 0.5s;
            transition: opacity 0.5s ease;
            background-color: black;
            color: white;
        }

        .product-card:hover .addCartBtn {
            opacity: 1;
        }

        .addCartBtn:hover {
            background-color: white;
            border-width: 1px;
            border-color: gray;
            color: black;
        }

        /* .pre-btn {
            left: 0px;
        }

        .next-btn {
            right: 0px;
        }

        .pre-btn a,
        .next-btn a {
            opacity: 0.5;
        }

        .pre-btn:hover,
        .next-btn:hover {
            opacity: 1;
        }

        .collection-container {
            width: 100%;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
        }

        .collection {
            position: relative;
        }

        .collection img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .collection p {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            font-size: 20px;
            color: #fff;
            text-transform: capitalize;
        }

        .collection:nth-child(3) {
            grid-column: span 2;
            margin-bottom: 10px;

        } */

        .productImage:hover {
            /* box-shadow: 0 5px 15px black; */
        }
    </style>

<body>
    <div class="pl-28 pr-28 pt-8">
        <section class="container">
            <div class="slider-wrapper">


                <div class="slider">
                    <a class="w-full" href=""><img id="banner1" class="w-full" src="../../Pictures//Banner01.png" alt="Banner"></a>
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
        <div class="flex flex-col pt-8">
            <div class="flex flex-col w-full">
                <h1 class="font-bold text-2xl mb-2">แนะนำสำหรับคุณ</h1>
                <div class="flex flex-row w-full">
                    <div class="flex justify-center w-full">
                        <img class="m-5 max-h-84 object-contain rounded-md border-2 " src="../../Pictures/Product/comic/onepiece98.jpeg" alt="recommendBook  ">
                    </div>
                    <div class="w-full border-l-2 p-2">
                        <?php
                        $result = selectProduct('One Piece 98');
                        $row = $result->fetch_assoc();
                        echo "<h1 class='text-2xl font-bold'>" . $row['ProName'] . "</h1>";
                        echo "<p class='text-md'><b>ผู้เขียน:</b> " . $row['Author'] . "</p>";
                        echo "<p class='text-md'><b>หมวดหมู่:</b> " . $row['TypeName'] . "</p>";
                        echo "<p class='text-md'><b>เรื่องย่อ:</b> " . $row['Description'] . "</p>";
                        echo "<div class='flex mt-5 items-center w-full rounded-md bg-red-100 h-16'>";
                        echo "<span class='font-bold text-2xl ml-5'>" . $row['PricePerUnit'] . " </span>";
                        echo "<span class='font-medium text-lg ml-2'> บาท</span>";
                        echo "</div>"
                        ?>
                    </div>
                </div>
            </div>

            <section class="relative overflow-hidden pt-8">
                <div class="flex mb-5 justify-between">
                    <h1 class="font-bold text-2xl">สินค้าลดราคา</h1>
                    <h1 class="font-regular text-lg ">ดูทั้งหมด ></h1>
                </div>
                <i id='leftPointer' class='bx bxs-chevron-left absolute text-5xl z-10 hover:text-red-500 hover:cursor-pointer' style="top: 45%"></i>
                <i id='rightPointer' class='bx bxs-chevron-right absolute text-5xl z-10 hover:text-red-500 hover:cursor-pointer' style="top: 45%; right: 0px"></i>
                <div class="product-container flex overflow-x-auto scroll-smooth ml-12 mr-12">
                    <?php
                    $result = selectDiscountProduct();
                    while ($row = $result->fetch_assoc()) {
                        echo "<div class='product-card w-64 h-4/5 mr-5'>";
                        echo "<div class='product-image h-full w-full relative overflow-hidden max-h-96'>";
                        echo "<span class='productDiscount absolute top-2.5 left-2.5 px-1.5 py-1.5 bg-red-700 text-white font-medium rounded-md'>-10%</span>";

                        echo "<form id='detialProduct' method='POST' action='ProductDetail.php'>";
                        echo "<img id='imageProduct' class='productImage hover:cursor-pointer rounded-lg border-2 w-full h-96 min-h-80 object-fill' src='" . $row['ImageSource'] . "' alt='product'>";
                        echo "<input type='hidden' name='proID' value='" . $row['ProID'] . "'>";
                        echo "<input type='hidden' name='typeName' value='" . $row['TypeName'] . "'>";
                        echo "</form>";

                        // echo "<form id='addtoCard' method='POST' action='#'>";
                        echo "<button id='addtoCard' type='button' class='addCartBtn absolute bottom-2.5 left-2/4 p-2.5 w-11/12 capitalize outline-none rounded-md cursor-pointer opacity-0 '>Add to Cart <i class='bx bxs-cart'></i></button>";
                        echo "<input type='hidden' name='productID' value='" . $row['ProID'] . "'>";
                        // echo "</form>";

                        echo "</div>";
                        echo "<div class='px-2.5 w-full h-full min-h-32'>";

                        echo "<p id='productType' class='uppercase text-lg font-bold mt-2 overflow-hidden text-ellipsis whitespace-nowrap'>" . $row['TypeName'] . "</p>";

                        echo "<form id='detialProduct' method='POST' action='#'>";
                        echo "<p id='productName' class='uppercase text-md font-semibold mt-1 overflow-hidden text-ellipsis whitespace-nowrap hover:text-blue-500 cursor-pointer'>" . $row['ProName'] . "</p>";
                        echo "<input type='hidden' name='productID' value='" . $row['ProID'] . "'>";
                        echo "</form>";

                        echo "<span class='font-semibold text-lg'>ราคา: " . ($row['PricePerUnit'] - ($row['PricePerUnit'] * 10 / 100)) . " ฿</span>";
                        echo "<span class='line-through text-md opacity-50 ml-2'>" . $row['PricePerUnit'] . " ฿</span>";
                        echo "</div>";
                        echo "</div>";
                    };
                    ?>
                </div>
            </section>

            <section class="relative overflow-hidden pt-8">
                <div class="flex mb-5 justify-between">
                    <h1 class="font-bold text-2xl">สินค้ายอดนิยม</h1>
                    <h1 class="font-regular text-lg ">ดูทั้งหมด</h1>
                </div>
                <i id='leftPointer' class='bx bxs-chevron-left absolute text-5xl z-10 hover:text-red-500 hover:cursor-pointer' style="top: 45%"></i>
                <i id='rightPointer' class='bx bxs-chevron-right absolute text-5xl z-10 hover:text-red-500 hover:cursor-pointer' style="top: 45%; right: 0px"></i>
                <div class="product-container flex overflow-x-auto scroll-smooth ml-12 mr-12">
                    <?php
                    $result = selectDiscountProduct();
                    while ($row = $result->fetch_assoc()) {
                        echo "<div class='product-card w-64 h-4/5 mr-5'>";
                        echo "<div class='product-image h-full w-full relative overflow-hidden max-h-96'>";
                        // echo "<span class='productDiscount absolute top-2.5 left-2.5 px-1.5 py-1.5 bg-red-700 text-white font-medium rounded-md'>-10%</span>";

                        echo "<form id='detialProduct' method='POST' action='#'>";
                        echo "<img id='imageProduct' class='productImage hover:cursor-pointer rounded-lg border-2 w-full h-96 min-h-80 object-fill' src='" . $row['ImageSource'] . "' alt='product'>";
                        echo "<input type='hidden' name='productID' value='" . $row['ProID'] . "'>";
                        echo "</form>";

                        // echo "<form id='addtoCard' method='POST' action='#'>";
                        echo "<button id='addtoCard' type='button' class='addCartBtn absolute bottom-2.5 left-2/4 p-2.5 w-11/12 capitalize outline-none rounded-md cursor-pointer opacity-0 '>Add to Cart <i class='bx bxs-cart'></i></button>";
                        echo "<input type='hidden' name='productID' value='" . $row['ProID'] . "'>";
                        // echo "</form>";

                        echo "</div>";
                        echo "<div class='px-2.5 w-full h-full min-h-32'>";

                        echo "<form id='detialProduct' method='POST' action='#'>";
                        echo "<p id='productName' class='uppercase text-md font-semibold mt-2 overflow-hidden text-ellipsis whitespace-nowrap hover:text-blue-500 cursor-pointer'>" . $row['ProName'] . "</p>";
                        echo "<input type='hidden' name='productID' value='" . $row['ProID'] . "'>";
                        echo "</form>";

                        // echo "<span class='font-bold text-lg'>" . ($row['PricePerUnit'] - ($row['PricePerUnit'] * 10 / 100)) . " ฿</span>";
                        echo "<span class='text-lg font-bold'>" . $row['PricePerUnit'] . " ฿</span>";
                        echo "</div>";
                        echo "</div>";
                    };
                    ?>
                </div>
            </section>

            <section class="relative overflow-hidden pt-8">
                <div class="flex mb-5 justify-between">
                    <h1 class="font-bold text-2xl">สินค้าใหม่</h1>
                    <h1 class="font-regular text-lg ">ดูทั้งหมด</h1>
                </div> <i id='leftPointer' class='bx bxs-chevron-left absolute text-5xl z-10 hover:text-red-500 hover:cursor-pointer' style="top: 45%"></i>
                <i id='rightPointer' class='bx bxs-chevron-right absolute text-5xl z-10 hover:text-red-500 hover:cursor-pointer' style="top: 45%; right: 0px"></i>
                <div class="product-container flex overflow-x-auto scroll-smooth ml-12 mr-12">
                    <?php
                    $result = selectDiscountProduct();
                    while ($row = $result->fetch_assoc()) {
                        echo "<div class='product-card w-64 h-4/5 mr-5'>";
                        echo "<div class='product-image h-full w-full relative overflow-hidden max-h-96'>";
                        // echo "<span class='productDiscount absolute top-2.5 left-2.5 px-1.5 py-1.5 bg-red-700 text-white font-medium rounded-md'>-10%</span>";

                        echo "<form id='detialProduct' method='POST' action='#'>";
                        echo "<img id='imageProduct' class='productImage hover:cursor-pointer rounded-lg border-2 w-full h-96 min-h-80 object-fill' src='" . $row['ImageSource'] . "' alt='product'>";
                        echo "<input type='hidden' name='productID' value='" . $row['ProID'] . "'>";
                        echo "</form>";

                        // echo "<form id='addtoCard' method='POST' action='#'>";
                        echo "<button id='addtoCard' type='button' class='addCartBtn absolute bottom-2.5 left-2/4 p-2.5 w-11/12 capitalize outline-none rounded-md cursor-pointer opacity-0 '>Add to Cart <i class='bx bxs-cart'></i></button>";
                        echo "<input type='hidden' name='productID' value='" . $row['ProID'] . "'>";
                        // echo "</form>";

                        echo "</div>";
                        echo "<div class='px-2.5 w-full h-full min-h-32'>";

                        echo "<form id='detialProduct' method='POST' action='#'>";
                        echo "<p id='productName' class='uppercase text-md font-semibold mt-2 overflow-hidden text-ellipsis whitespace-nowrap hover:text-blue-500 cursor-pointer'>" . $row['ProName'] . "</p>";
                        echo "<input type='hidden' name='productID' value='" . $row['ProID'] . "'>";
                        echo "</form>";

                        // echo "<span class='font-bold text-lg'>" . ($row['PricePerUnit'] - ($row['PricePerUnit'] * 10 / 100)) . " ฿</span>";
                        echo "<span class='font-bold text-lg'>" . $row['PricePerUnit'] . " ฿</span>";
                        echo "</div>";
                        echo "</div>";
                    };
                    ?>
                </div>
            </section>
        </div>

        <?php 
        if (isset($_SESSION['tokenJWT']) || isset($_SESSION['tokenGoogle'])) {
            var_dump("isset");
            // var_dump("Test jwt: " +  $_SESSION['tokenJWT']);
            var_dump($_SESSION['tokenGoogle']);
        }
        
        ?>
        <script>
            const navLinks = document.querySelectorAll('.slider-nav a');
            let currentIndex = 0; // เริ่มจากภาพแรก
            const slides = document.querySelectorAll('.slider img'); // เลือกทุกภาพใน slider
            const totalSlides = slides.length; // นับจำนวนภาพทั้งหมด

            // 1. สร้างตัวแปร JavaScript สำหรับ .product-container, #leftPointer, และ #rightPointer.
            var productContainer = document.querySelector('.product-container');
            var leftPointer = document.querySelector('#leftPointer');
            var rightPointer = document.querySelector('#rightPointer');

            // 2. สร้าง event listener สำหรับการคลิกที่ #leftPointer และ #rightPointer.
            leftPointer.addEventListener('click', function() {
                // 3. ใน event listener, ใช้ scrollBy ใน .product-container เพื่อเลื่อนสินค้าไปทางซ้าย.
                productContainer.scrollBy({
                    left: -260,
                    behavior: 'smooth'
                });
            });

            rightPointer.addEventListener('click', function() {
                // 3. ใน event listener, ใช้ scrollBy ใน .product-container เพื่อเลื่อนสินค้าไปทางขวา.
                productContainer.scrollBy({
                    left: 260,
                    behavior: 'smooth'
                });
            });

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

        const imgProdcut = document.querySelectorAll('.productImage');
        imgProdcut.forEach((img) => {
            img.addEventListener('click', () => {
                var form = img.parentElement;
                form.submit();
            });
        });

        $(document).ready(function() {
            $('#addtoCard').click(function() {
            $.ajax({
                type: 'POST',
                url: '../../Backend/CartQuery/AddToCart.php',
                data: {
                proID: $('input[name="productID"]').val(),
                quantityHidden: 1
                },
                success: function() {
                // window.location.href = '../../Frontend/MainPage/Cart.php';
                alert('Add to cart success');
                }
            });
            });
        });
        </script>
</body>

</html>