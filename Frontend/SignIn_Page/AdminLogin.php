<?php
require "../../Backend/GoogleAuthen/Google_OAuth.php";
require '../../Backend/Authorized/UserAuthorized.php';
// require '../../Backend/Authorized/ManageHeader.php';
$authUrl = $client->createAuthUrl();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kodchasan:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;1,200;1,300;1,400;1,500;1,600;1,700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        * {
            margin: 0;
            padding: 0;
            font-family: Kodchasan;
        }
    </style>

    <title>Admin Login</title>
</head>

<body class="flex flex-col justify-center items-center bg-gray-200 min-h-screen">
    <div class="p-5 bg-white rounded-lg shadow-lg flex flex-col">
        <div class="flex justify-center">
            <p class="text-lg font-semibold">Admin Portal</p>
        </div>
        <div class="flex justify-center mt-4 ">
            <a href="<?php echo htmlspecialchars($authUrl); ?>" class="flex items-center justify-center rounded-md border-2 bg-white border-gray-400 w-full text-black px-4 py-2 hover:shadow-xl">
                <i class='bx bxl-google text-md mr-2' style='color: black'></i> ลงชื่อเข้าใช้ด้วย Google
            </a>
        </div>
    </div>
</body>

</html>