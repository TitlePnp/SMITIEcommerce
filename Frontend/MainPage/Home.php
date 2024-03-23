<?php
require '../../Components/HeaderUser.html';
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
        }
    </style>

<body>
    <div class="flex flex-col justify-center items-center h-screen">
        <h1 class="text-3xl font-bold">This page is under maintenance!!🔨</h1>
        <?php
        if (isset($_SESSION['token'])) {

        }
        ?>
    </div>
</body>

</html>