<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kodchasan:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    <!-- Css link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <title>Register Page</title>

    <style>
        .coreContainer {
            background: rgb(2, 0, 36);
            background: linear-gradient(90deg, rgba(2, 0, 36, 1) 0%, rgba(9, 9, 121, 1) 35%, rgba(0, 212, 255, 1) 100%);
            padding: 50px;
            font-family: Kodchasan;
            font-weight: 700;
        }
    </style>
</head>

<body class="coreContainer flex items-center justify-center h-screen">
    <div class="bg-white flex flex-row rounded shadow-2xl w-2/3">
        <div class="flex-1">
            <img src="./Pictures/Banner.png" alt="logo" class="w-full h-full object-cover" style="border-top-left-radius: 10px; border-bottom-left-radius: 10px;">
        </div>
        <div class="flex-1" style="padding: 30px">
            <h2 class="mb-5 text-center" style="font-size: 40px;">Sign-Up</h2>
            <form>
                <div class="mb-5">
                    <!-- <label class="block mb-2">Username</label> -->
                    <input name="UserName" type="text" class="w-full p-2 border rounded" placeholder="Username">
                </div>
                <div class="mb-5">
                    <!-- <label class="block mb-2">Email</label> -->
                    <input name="Email" type="email" class="w-full p-2 border rounded" placeholder="Email">
                </div>
                <div class="mb-5">
                    <!-- <label class="block mb-2">Password</label> -->
                    <input name="Password" type="password" class="w-full p-2 border rounded" placeholder="Password">
                </div>
                <div class="mb-5">
                    <!-- <label class="block mb-2">Confirm Password</label -->
                    <input name="ConfirmPassword" type="password" class="w-full p-2 border rounded" placeholder="Password">
                </div>
                <button type="submit" class="w-full p-2 bg-black text-white rounded flex justify-center items-center">
                    Register
                    <i class="fas fa-arrow-right ml-1"></i>
                </button>
            </form>
            <div class="my-5 item-center" style="text-align: center;">
                <!-- <div class="absolute left-0 right-0 w-full h-px bg-gray-300 mx-auto"></div> -->
                <p>Or</p>
                <!-- <div class="absolute left-0 right-0 w-full h-px bg-gray-300 mx-auto"></div> -->
            </div>
            <button type="submit" class="w-full p-2 bg-black text-white rounded flex justify-center items-center mb-2 mt-5">
                <i class="fab fa-google mr-2"></i>
                Google Account
            </button>
        </div>
    </div>
</body>

</html>