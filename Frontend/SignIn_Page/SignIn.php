<?php
require "../../Backend/GoogleAuthen/Google_OAuth.php";
require '../../Backend/Authorized/UserAuthorized.php';
require '../../Backend/Authorized/ManageHeader.php';
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

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Kodchasan;
        }

        body {
            background-color: #f3f4f6;
        }

        .input-group {
            position: relative;
            /* margin: 20px 0; */
        }

        .input-group label {
            position: absolute;
            top: 50%;
            left: 5px;
            transform: translateY(-50%);
            font-size: 14px;
            font-weight: 500;
            color: black;
            padding: 0px 5px;
            pointer-events: none;
            transition: .4s;
        }

        .input-group input {
            width: 320px;
            height: 40px;
            font-size: 16px;
            color: black;
            padding: 0 10px;
            background: transparent;
            border: 1.2px solid black;
            outline: none;
            border-radius: 5px;
        }

        .input-group input:focus~label,
        .input-group input:valid~label {
            top: 0;
            font-size: 12px;
            color: black;
            background-color: white;
            padding: 0 5px;
        }
    </style>
</head>

<body>
    <div class="flex flex-col justify-center items-center min-h-screen">
        <div class="bg-white p-10 rounded-md shadow-lg">
            <div class="text-center">
                <p class="font-medium text-3xl mb-5">ยินดีต้อนรับกลับมา!</p>
                <p class="font-medium text-xl mb-5">เข้าสู่ระบบ</p>
            </div>
            <form id="loginForm" method="POST" action="../../Backend/UserManage/LoginCustomer.php">
                <div class="input-group mb-5">
                    <input type="text" id="usernameBox" name="username" required>
                    <label for="">ชื่อผู้ใช้</label>
                </div>
                <div class="input-group">
                    <input type="password" id="passwordBox" name="userpassword" required>
                    <label for="">รหัสผ่าน</label>
                    <div class="absolute text-lg right-2 top-1/2 transform -translate-y-1/2 cursor-pointer">
                        <i id="showPass" class='bx bxs-show block' style='color: black;'></i>
                        <i id="hidePass" class='bx bxs-hide' style="color: black; display: none;"></i>
                    </div>
                </div>
            </form>

            <div class="mt-2 h-5">
                <p id="errorBar" class="text-sm text-red-500"></p>
            </div>

            <div>
                <p class="text-right text-sm mt-2"><a href="#" class="text-black hover:text-blue-500">ลืมรหัสผ่าน?</a></p>
            </div>

            <button type="button" onclick="validateForm()" class="rounded-md bg-black w-full text-white px-4 py-2 mt-5 hover:shadow-xl">ลงชื่อเข้าใช้</button>

            <div class="flex items-center justify-center mt-4">
                <hr class="border-gray-300 border-t w-6/12">
                <span class="mx-2 text-gray-500">Or</span>
                <hr class="border-gray-300 border-t w-6/12">
            </div>
            <div class="flex justify-center mt-4">
                <a href="<?php echo $authUrl; ?>" class="flex items-center justify-center rounded-md border-2 bg-white border-gray-400 w-full text-black px-4 py-2 hover:shadow-xl">
                    <i class='bx bxl-google text-md mr-2' style='color: black'></i> ลงชื่อเข้าใช้ด้วย Google
                </a>
            </div>

            <div>
                <p class="text-center mt-5">ยังไม่มีบัญชีผู้ใช้? <a href="../SignUp_Page/Signup.php" class="text-blue-500 hover:underline">สมัครสมาชิก</a></p>
            </div>

        </div>
    </div>

    <script>
        const passwordInput = document.querySelector('input[type="password"]');
        const showIcon = document.getElementById('showPass');
        const hideIcon = document.getElementById('hidePass');

        showIcon.addEventListener('click', () => {
            passwordInput.setAttribute('type', 'text');
            showIcon.style.display = 'none';
            hideIcon.style.display = 'block';
        });

        hideIcon.addEventListener('click', () => {
            passwordInput.setAttribute('type', 'password');
            showIcon.style.display = 'block';
            hideIcon.style.display = 'none';
        });

        function validateForm() {
            const username = document.getElementById('usernameBox').value;
            const password = document.getElementById('passwordBox').value;
            const usernameBox = document.getElementById('usernameBox');
            const passwordBox = document.getElementById('passwordBox');
            const errorBar = document.getElementById('errorBar');

            const LoginForm = document.getElementById('loginForm');

            if (username === '' && password === '') {
                errorBar.style.display = 'block';
                errorBar.innerHTML = '*กรุณากรอกข้อมูลให้ครบถ้วน';
                errorBar.style.color = 'red';
                usernameBox.style.borderColor = 'rgb(244 63 94)';
                passwordBox.style.borderColor = 'rgb(244 63 94)';
            } else if (username === '') {
                errorBar.style.display = 'block';
                errorBar.innerHTML = '*กรุณากรอกชื่อผู้ใช้';
                errorBar.style.color = 'red';
                usernameBox.style.borderColor = 'rgb(244 63 94)';
                passwordBox.style.borderColor = 'rgb(107 114 128)';
            } else if (password === '') {
                errorBar.style.display = 'block';
                errorBar.innerHTML = '*กรุณากรอกรหัสผ่าน';
                errorBar.style.color = 'red';
                usernameBox.style.borderColor = 'rgb(107 114 128)';
                passwordBox.style.borderColor = 'rgb(244 63 94)';
            }

            if (username != '' && password != '') {
                errorBar.style.display = 'none';
                usernameBox.style.borderColor = 'rgb(107 114 128)';
                passwordBox.style.borderColor = 'rgb(107 114 128)';
                $.ajax({
                    type: 'POST',
                    url: '../../Backend/UserManage/checkUserAccount.php',
                    data: {
                        action: 'checkUsername',
                        username: username
                    },
                    success: function(response) {
                        if (response === 'username exists') {
                            $.ajax({
                                type: 'POST',
                                url: '../../Backend/UserManage/ClearSession.php',
                                success: function() {
                                    LoginForm.submit();
                                }
                            });
                        } else {
                            errorBar.style.display = 'block';
                            errorBar.innerHTML = '*ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง';
                            errorBar.style.color = 'red';
                            usernameBox.style.borderColor = 'rgb(244 63 94)';
                            passwordBox.style.borderColor = 'rgb(244 63 94)';

                        }
                    }
                });
            }
        }
    </script>
</body>

</html>