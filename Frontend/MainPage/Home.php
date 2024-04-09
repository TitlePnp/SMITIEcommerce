<?php
require '../../Backend/Authorized/UserAuthorized.php';
require '../../Backend/Authorized/ManageHeader.php';
require '../../vendor/autoload.php';
require_once "../../Backend/ProductQuery/ProductInfo.php";
require_once "../../Backend/ProductQuery/ProductDetail.php";

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

        /*.productImage:hover {
             box-shadow: 0 5px 15px black; 
        }*/
        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
        }

        input[type="number"] {
        -moz-appearance: textfield;
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
                        $result = selectProduct('One piece 98');
                        $row = $result->fetch_assoc();
                        $stockOnOrder = sumProductOnOrder($row['ProID']);
                        $stockAvailable = $row['StockQty'] - $stockOnOrder;
                        echo "<h1 class='text-2xl font-bold'>" . $row['ProName'] . "</h1>";
                        echo "<p class='text-md'><b>ผู้เขียน:</b> " . $row['Author'] . "</p>";
                        echo "<p class='text-md'><b>หมวดหมู่:</b> " . $row['TypeName'] . "</p>";
                        echo "<p class='text-md'><b>เรื่องย่อ:</b> " . $row['Description'] . "</p>";
                        echo "<div class='p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50' role='alert'/>";
                        echo "<span class='text-3xl font-bold'>{$row['PricePerUnit']}</span> บาท";
                        echo "</div>";
                        echo "<div class='quantity-controls'>";
                        echo "<div class='flex items-center'>";
                        echo "<p class='text-base font-normal mr-3'>จำนวน: </p>";
                        echo "<button type='button' class='decrease hover:bg-slate-200 border border-gray-300 h-8 w-8 border-r-0 flex items-bottom justify-center'>-</button>";
                        echo "<input type='number' min='1' max='{$stockAvailable}' value='1' class='quantity bg-white text-gray-900 text-sm w-16 h-8 border border-gray-300  text-center'>";
                        echo "<button type='button' class='increase hover:bg-slate-200 border border-gray-300 h-8 w-8 border-l-0 flex items-bottom justify-center'>+</button>";
                        echo "</div>";
                        echo "<p class='text-sm font-normal ml-14 mt-3 text-neutral-600'>มีสินค้าทั้งหมด {$stockAvailable} เล่ม</p>";
                        echo "<div class='flex'>";
                        echo "<input type='hidden' name='proID' value='{$row['ProID']}'>";
                        echo "<input type='hidden' name='quantityHidden' value=''>";
                        echo "<button id='add-to-cart-button' class='bg-red-500/25 hover:bg-red-500/50 text-red-700 text-base font-normal py-2 px-4 rounded mt-3 border border-red-700 flex items-center'>เพิ่มลงในตะกร้า";
                        echo "<img src='../../Pictures/shopping-cart.png' alt='cart icon' class='w-6 h-6 ml-2'
              style='filter: grayscale(100%) contrast(0);'></button>";
                        echo "</div>";
                        echo "</div>"
                        ?>
                    </div>
                </div>
            </div>

            <section class="relative overflow-hidden pt-8">
                <div class="flex mb-5 justify-between">
                    <h1 class="font-bold text-2xl">สินค้าแนะนำ</h1>
                    <h1 class="font-regular text-lg ">ดูทั้งหมด ></h1>
                </div>
                <i id='leftPointer' class='leftPointer bx bxs-chevron-left absolute text-5xl z-10 hover:text-red-500 hover:cursor-pointer' style="top: 45%"></i>
                <i id='rightPointer' class='rightPointer bx bxs-chevron-right absolute text-5xl z-10 hover:text-red-500 hover:cursor-pointer' style="top: 45%; right: 0px"></i>
                <div class="product-container flex overflow-x-auto scroll-smooth ml-12 mr-12">
                    <?php
                    $result = selectShowProduct(7);
                    while ($row = $result->fetch_assoc()) {
                        echo "<div class='product-card w-64 h-4/5 mr-5'>";
                        echo "<div class='product-image h-full w-full relative overflow-hidden max-h-96'>";
                        // echo "<span class='productDiscount absolute top-2.5 left-2.5 px-1.5 py-1.5 bg-red-700 text-white font-medium rounded-md'>-10%</span>";

                        echo "<form id='detialProduct' method='POST' action='ProductDetail.php'>";
                        echo "<img id='imageProduct' class='productImage hover:cursor-pointer rounded-lg border-2 w-full h-96 min-h-80 object-fill' src='" . $row['ImageSource'] . "' alt='product'>";
                        echo "<input type='hidden' name='proID' value='" . $row['ProID'] . "'>";
                        echo "<input type='hidden' name='typeName' value='" . $row['TypeName'] . "'>";
                        echo "</form>";

                        // echo "<form id='addtoCart' method='POST' action='#'>";
                        echo "<button id='addtoCart' type='button' class='addCartBtn absolute bottom-2.5 left-2/4 p-2.5 w-11/12 capitalize outline-none rounded-md cursor-pointer opacity-0 '>Add to Cart <i class='bx bxs-cart'></i></button>";
                        echo "<input type='hidden' name='productID' value='" . $row['ProID'] . "'>";
                        // echo "</form>";

                        echo "</div>";
                        echo "<div class='px-2.5 w-full h-full min-h-32'>";

                        echo "<p id='productType' class='uppercase text-lg font-bold mt-2 overflow-hidden text-ellipsis whitespace-nowrap'>" . $row['TypeName'] . "</p>";

                        echo "<form id='detialProduct' method='POST' action='ProductDetail.php'>";
                        echo "<p id='productName' class='nameToDetail uppercase text-md font-semibold mt-1 overflow-hidden text-ellipsis whitespace-nowrap hover:text-blue-500 cursor-pointer'>" . $row['ProName'] . "</p>";
                        echo "<input type='hidden' name='proID' value='" . $row['ProID'] . "'>";
                        echo "<input type='hidden' name='typeName' value='" . $row['TypeName'] . "'>";
                        echo "</form>";

                        // echo "<span class='font-semibold text-lg'>ราคา: " . ($row['PricePerUnit'] - ($row['PricePerUnit'] * 10 / 100)) . " </span>";
                        // echo "<span class='line-through text-md opacity-50 ml-2'>" . $row['PricePerUnit'] . " ฿</span>";
                        echo "<span class='font-bold text-lg'>ราคา: " . $row['PricePerUnit'] . " บาท</span>";
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
                <i id='leftPointer' class='leftPointer bx bxs-chevron-left absolute text-5xl z-10 hover:text-red-500 hover:cursor-pointer' style="top: 45%"></i>
                <i id='rightPointer' class='rightPointer bx bxs-chevron-right absolute text-5xl z-10 hover:text-red-500 hover:cursor-pointer' style="top: 45%; right: 0px"></i>
                <div class="product-container flex overflow-x-auto scroll-smooth ml-12 mr-12">
                    <?php
                    $result = selectShowProduct(7);
                    while ($row = $result->fetch_assoc()) {
                        echo "<div class='product-card w-64 h-4/5 mr-5'>";
                        echo "<div class='product-image h-full w-full relative overflow-hidden max-h-96'>";
                        // echo "<span class='productDiscount absolute top-2.5 left-2.5 px-1.5 py-1.5 bg-red-700 text-white font-medium rounded-md'>-10%</span>";

                        echo "<form id='detialProduct' method='POST' action='ProductDetail.php'>";
                        echo "<img id='imageProduct' class='productImage hover:cursor-pointer rounded-lg border-2 w-full h-96 min-h-80 object-fill' src='" . $row['ImageSource'] . "' alt='product'>";
                        echo "<input type='hidden' name='proID' value='" . $row['ProID'] . "'>";
                        echo "<input type='hidden' name='typeName' value='" . $row['TypeName'] . "'>";
                        echo "</form>";

                        // echo "<form id='addtoCart' method='POST' action='#'>";
                        echo "<button id='addtoCart' type='button' class='addCartBtn absolute bottom-2.5 left-2/4 p-2.5 w-11/12 capitalize outline-none rounded-md cursor-pointer opacity-0 '>Add to Cart <i class='bx bxs-cart'></i></button>";
                        echo "<input type='hidden' name='productID' value='" . $row['ProID'] . "'>";
                        // echo "</form>";

                        echo "</div>";
                        echo "<div class='px-2.5 w-full h-full min-h-32'>";

                        echo "<p id='productType' class='uppercase text-lg font-bold mt-2 overflow-hidden text-ellipsis whitespace-nowrap'>" . $row['TypeName'] . "</p>";


                        echo "<form id='detialProduct' method='POST' action='#'>";
                        echo "<p id='productName' class='nameToDetail uppercase text-md font-semibold mt-2 overflow-hidden text-ellipsis whitespace-nowrap hover:text-blue-500 cursor-pointer'>" . $row['ProName'] . "</p>";
                        echo "<input type='hidden' name='productID' value='" . $row['ProID'] . "'>";
                        echo "</form>";

                        // echo "<span class='font-bold text-lg'>" . ($row['PricePerUnit'] - ($row['PricePerUnit'] * 10 / 100)) . " ฿</span>";
                        echo "<span class='font-bold text-lg'>ราคา: " . $row['PricePerUnit'] . " บาท</span>";
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
                </div>
                <i id='leftPointer' class='leftPointer bx bxs-chevron-left absolute text-5xl z-10 hover:text-red-500 hover:cursor-pointer' style="top: 45%"></i>
                <i id='rightPointer' class='rightPointer bx bxs-chevron-right absolute text-5xl z-10 hover:text-red-500 hover:cursor-pointer' style="top: 45%; right: 0px"></i>
                <div class="product-container flex overflow-x-auto scroll-smooth ml-12 mr-12">
                    <?php
                    $result = selectShowProduct(7);
                    while ($row = $result->fetch_assoc()) {
                        echo "<div class='product-card w-64 h-4/5 mr-5'>";
                        echo "<div class='product-image h-full w-full relative overflow-hidden max-h-96'>";
                        // echo "<span class='productDiscount absolute top-2.5 left-2.5 px-1.5 py-1.5 bg-red-700 text-white font-medium rounded-md'>-10%</span>";

                        echo "<form id='detialProduct' method='POST' action='ProductDetail.php'>";
                        echo "<img id='imageProduct' class='productImage hover:cursor-pointer rounded-lg border-2 w-full h-96 min-h-80 object-fill' src='" . $row['ImageSource'] . "' alt='product'>";
                        echo "<input type='hidden' name='proID' value='" . $row['ProID'] . "'>";
                        echo "<input type='hidden' name='typeName' value='" . $row['TypeName'] . "'>";
                        echo "</form>";

                        // echo "<form id='addtoCart' method='POST' action='#'>";
                        echo "<button id='addtoCart' type='button' class='addCartBtn absolute bottom-2.5 left-2/4 p-2.5 w-11/12 capitalize outline-none rounded-md cursor-pointer opacity-0 '>Add to Cart <i class='bx bxs-cart'></i></button>";
                        echo "<input type='hidden' name='productID' value='" . $row['ProID'] . "'>";
                        // echo "</form>";

                        echo "</div>";
                        echo "<div class='px-2.5 w-full h-full min-h-32'>";

                        echo "<p id='productType' class='uppercase text-lg font-bold mt-2 overflow-hidden text-ellipsis whitespace-nowrap'>" . $row['TypeName'] . "</p>";

                        echo "<form id='detialProduct' method='POST' action='ProductDetail.php'>";
                        echo "<p id='productName' class='nameToDetail uppercase text-md font-semibold mt-2 overflow-hidden text-ellipsis whitespace-nowrap hover:text-blue-500 cursor-pointer'>" . $row['ProName'] . "</p>";
                        echo "<input type='hidden' name='productID' value='" . $row['ProID'] . "'>";
                        echo "</form>";

                        // echo "<span class='font-bold text-lg'>" . ($row['PricePerUnit'] - ($row['PricePerUnit'] * 10 / 100)) . " ฿</span>";
                        // echo "<span class='font-bold text-lg'>" . $row['PricePerUnit'] . " ฿</span>";
                        echo "<span class='font-bold text-lg'>ราคา: " . $row['PricePerUnit'] . " บาท</span>";
                        echo "</div>";
                        echo "</div>";
                    };
                    ?>
                </div>
            </section>
            
        </div>

        <?php
        // if (isset($_SESSION['tokenJWT']) || isset($_SESSION['tokenGoogle'])) {
        //     // var_dump("isset");
        //     // var_dump("Test jwt: " +  $_SESSION['tokenJWT']);
        //     // var_dump($_SESSION['tokenGoogle']);
        // }

        ?>
        <script>
            const navLinks = document.querySelectorAll('.slider-nav a');
            let currentIndex = 0; // เริ่มจากภาพแรก
            const slides = document.querySelectorAll('.slider img'); // เลือกทุกภาพใน slider
            const totalSlides = slides.length; // นับจำนวนภาพทั้งหมด

            $(document).ready(function() {
                var scrollAmount = 200; // จำนวนที่ต้องการเลื่อน
                var scrollDuration = 50; // ระยะเวลาที่ต้องการให้การเลื่อนเสร็จสิ้น (มิลลิวินาที)

                $('.leftPointer').each(function() {
                    $(this).click(function() {
                        $(this).siblings('.product-container').animate({
                            scrollLeft: "-=" + scrollAmount + "px"
                        }, scrollDuration);
                    });
                });

                $('.rightPointer').each(function() {
                    $(this).click(function() {
                        $(this).siblings('.product-container').animate({
                            scrollLeft: "+=" + scrollAmount + "px"
                        }, scrollDuration);
                    });
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

            const nameProduct = document.querySelectorAll('.nameToDetail');
            nameProduct.forEach((name) => {
                name.addEventListener('click', () => {
                    var form = name.parentElement;
                    form.submit();
                });
            });

            $(document).ready(function() {
                $('.addCartBtn').click(function() {
                    var productID = $(this).next('input[name="productID"]').val();
                    $.ajax({
                        type: 'POST',
                        url: '../../Backend/CartQuery/AddToCart.php',
                        data: {
                            proID: productID,
                            quantityHidden: 1
                        },
                        success: function() {
                            location.reload();
                        }
                    });
                });
            });

            document.querySelectorAll('.quantity-controls').forEach(function(control) {
                var decreaseButton = control.querySelector('.decrease');
                var increaseButton = control.querySelector('.increase');
                var quantityInput = control.querySelector('.quantity');
                var quantityHidden = control.querySelector('input[name="quantityHidden"]');

                decreaseButton.addEventListener('click', function() {
                    var currentQuantity = parseInt(quantityInput.value, 10);
                    if (currentQuantity > 1) {
                        quantityInput.value = currentQuantity - 1;
                        quantityHidden.value = quantityInput.value;
                    }
                });

                increaseButton.addEventListener('click', function() {
                    var currentQuantity = parseInt(quantityInput.value, 10);
                    var maxQuantity = parseInt(quantityInput.max, 10);
                    if (currentQuantity < maxQuantity) {
                        quantityInput.value = currentQuantity + 1;
                        quantityHidden.value = quantityInput.value;
                    }
                });
                quantityInput.addEventListener('input', function() {
                    var currentQuantity = parseInt(quantityInput.value, 10);
                    if (isNaN(currentQuantity)) {
                        quantityInput.value = "1";
                    } else {
                        var maxQuantity = parseInt(quantityInput.max, 10);
                        if (currentQuantity > maxQuantity) {
                            quantityInput.value = maxQuantity;
                            quantityHidden.value = quantityInput.value;
                        } else if (currentQuantity === 0) {
                            quantityInput.value = "1";
                            quantityHidden.value = quantityInput.value;
                        }
                    }
                    quantityHidden.value = quantityInput.value;
                });
                quantityHidden.value = quantityInput.value;
            });

            $(document).ready(function() {
                $('#add-to-cart-button').click(function() {
                    $.ajax({
                        type: 'POST',
                        url: '../../Backend/CartQuery/AddToCart.php',
                        data: {
                            proID: $('input[name="proID"]').val(),
                            quantityHidden: $('input[name="quantityHidden"]').val()
                        },
                        success: function() {
                            window.location.href = '../../Frontend/MainPage/Cart.php';
                        }
                    });
                });
            });
        </script>
</body>

</html>