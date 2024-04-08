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
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <title>SMITI Shop: Sign Up</title>

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
            font-weight: 600;
            color: black;
            padding: 0px 5px;
            pointer-events: none;
            transition: .4s;
        }

        .input-group input {
            width: 320px;
            height: 40px;
            font-size: 15px;
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
                <p class="font-medium text-3xl mb-5">ยินดีต้อนรับ!</p>
                <p class="font-medium text-xl mb-5">สมัครสมาชิก</p>
            </div>
            <form id="insertForm" method="POST" action="../../Backend/UserManage/InsertUser.php">
                <div class="input-group ">
                    <input type="text" id="usernameBox" name="username" required>
                    <label for="">ชื่อผู้ใช้</label>
                </div>

                <div class=" mb-2 h-4">
                    <p id="usernameError" class="text-sm text-red-500"></p>
                </div>

                <div class="input-group">
                    <input type="text" id="emailBox" name="useremail" required>
                    <label for="">อีเมลล์</label>
                </div>

                <div class=" mb-2 h-4">
                    <p id="emailError" class="text-sm text-red-500"></p>
                </div>

                <div class="input-group">
                    <input type="password" id="passwordBox" name="userpassword" required>
                    <label for="">รหัสผ่าน</label>
                    <div class="absolute text-lg right-2 top-1/2 transform -translate-y-1/2 cursor-pointer">
                        <i id="showPass" class='bx bxs-show block' style='color: black;'></i>
                        <i id="hidePass" class='bx bxs-hide' style="color: black; display: none;"></i>
                    </div>
                    <div id="popoverContent" class="hidden absolute z-10 p-4 font-sans text-sm font-normal break-words whitespace-normal bg-white border rounded-lg shadow-lg w-max border-blue-gray-50 text-blue-gray-500 shadow-blue-gray-500/10 focus:outline-none" style="right: 100%; top: 50%; transform: translate(0%, -50%); margin-right: 10px;">
                        <p class="mb-1"><b>เพื่อความปลอดภัยรหัสผ่านควรมี:</b></p>
                        <hr>
                        <li class="mt-1" style="list-style-type: none;">
                            <span id="lengthPassCheck" style="display: none;"><i class='bx bxs-check-circle text-green-500'></i>
                                รหัสผ่านมากกว่า 8 ตัวอักษร</span>
                            <span id="lengthPassX"><i class='bx bxs-x-circle text-red-500'"></i> รหัสผ่านมากกว่า 8 ตัวอักษร</span>
                </li>
                <li style=" list-style-type: none;">
                                    <span id="upperLowerPassCheck" style="display: none;"><i class='bx bxs-check-circle text-green-500'></i> ตัวอักษรตัวเล็กและตัวใหญ่</span>
                                    <span id="upperLowerPassX"><i class='bx bxs-x-circle text-red-500'"></i> ตัวอักษรตัวเล็กและตัวใหญ่</span>
                </li>
                <li style=" list-style-type: none;">
                                            <span id="symbolPassCheck" style="display: none;"><i class='bx bxs-check-circle text-green-500'></i> อักขระพิเศษ</span>
                                            <span id="symbolPassX"><i class='bx bxs-x-circle text-red-500'></i>
                                                อักขระพิเศษ</span>
                        </li>
                        <li style="list-style-type: none;">
                            <span id="numberPassCheck" style="display: none;"><i class='bx bxs-check-circle text-green-500'></i>
                                ตัวเลข</span>
                            <span id="numberPassX"><i class='bx bxs-x-circle text-red-500'></i> ตัวเลข</span>
                        </li>
                    </div>
                </div>

                <div class="flex items-center mb-1 mt-2 ">
                    <div id="lowIndicator" class="w-1/2 h-1 bg-gray-300 rounded-md mr-1"></div>
                    <div id="mediumIndicator" class="w-1/2 h-1 bg-gray-300 rounded-md mr-1"></div>
                    <div id="highIndicator" class="w-1/2 h-1 bg-gray-300 rounded-md"></div>
                </div>

                <div class=" mb-2 h-4">
                    <p id="passwordError" class="text-sm text-red-500"></p>
                </div>

                <div class="input-group">
                    <input type="password" id="confirmPasswordBox" required>
                    <label for="">ยืนยันรหัสผ่าน</label>
                    <div class="absolute text-lg right-2 top-1/2 transform -translate-y-1/2 cursor-pointer">
                        <i id="showConfrimPass" class='bx bxs-show block' style='color: black;'></i>
                        <i id="hideConfrimPass" class='bx bxs-hide' style="color: black; display: none;"></i>
                    </div>
                </div>

                <div class=" mb- h-4">
                    <p id="confirmPasswordError" class="text-sm text-red-500"></p>
                </div>

            </form>
            <button type="button" onclick="validateForm()" class="rounded-md bg-black w-full text-white px-4 py-2 mt-5 hover:shadow-xl">สมัครสมาชิก</button>

            <div class="flex items-center justify-center mt-4">
                <hr class="border-gray-300 border-t w-6/12">
                <span class="mx-2 text-gray-500">Or</span>
                <hr class="border-gray-300 border-t w-6/12">
            </div>

            <div class="flex justify-center mt-4">
                <a href="<?php echo htmlspecialchars($authUrl); ?>" class="flex items-center justify-center rounded-md border-2 bg-white border-gray-400 w-full text-black px-4 py-2 hover:shadow-xl">
                    <i class='bx bxl-google text-md mr-2' style='color: black'></i> ลงชื่อเข้าใช้ด้วย Google
                </a>
            </div>

            <div>
                <p class="text-center mt-5">มีบัญชีผู้ใช้แล้ว? <a href="../SignIn_Page/SignIn.php" class="text-blue-500 hover:underline">เข้าสู่ระบบ</a></p>
            </div>

        </div>
    </div>

    <p id="ShowUserEntropy"></p>

    <script>
        const usernameBox = document.getElementById('usernameBox');
        const userNameError = document.getElementById('usernameError');

        const emailBox = document.getElementById('emailBox');
        const emailError = document.getElementById('emailError');

        const passwordBox = document.getElementById('passwordBox');
        const showIcon = document.getElementById('showPass');
        const hideIcon = document.getElementById('hidePass');
        const passwordError = document.getElementById('passwordError');

        const confirmPassBox = document.getElementById('confirmPasswordBox');
        const showConfirmIcon = document.getElementById('showConfrimPass');
        const hideConfirmIcon = document.getElementById('hideConfrimPass');
        const confirmPasswordError = document.getElementById('confirmPasswordError');

        //Password policy pop-over
        const popover = document.getElementById('popoverContent');
        const lengthPassCheck = document.getElementById('lengthPassCheck');
        const lengthPassX = document.getElementById('lengthPassX');
        const upperLowerPassCheck = document.getElementById('upperLowerPassCheck');
        const upperLowerPassX = document.getElementById('upperLowerPassX');
        const symbolPassCheck = document.getElementById('symbolPassCheck');
        const symbolPassX = document.getElementById('symbolPassX');
        const numberPassCheck = document.getElementById('numberPassCheck');
        const numberPassX = document.getElementById('numberPassX');

        var userEntropyPass = false;

        //Password policy
        passwordBox.addEventListener('input', () => {
            const password = passwordBox.value;
            const lengthPass = password.length >= 8;
            const upperLowerPass = password.match(/[a-z]/) && password.match(/[A-Z]/);
            const symbolPass = password.match(/[!@#$%^&*?]/);
            const numberPass = password.match(/[0-9]/);

            if (lengthPass) {
                lengthPassCheck.style.display = 'block';
                lengthPassX.style.display = 'none';
            } else {
                lengthPassCheck.style.display = 'none';
                lengthPassX.style.display = 'block';
            }

            if (upperLowerPass) {
                upperLowerPassCheck.style.display = 'block';
                upperLowerPassX.style.display = 'none';
            } else {
                upperLowerPassCheck.style.display = 'none';
                upperLowerPassX.style.display = 'block';
            }

            if (symbolPass) {
                symbolPassCheck.style.display = 'block';
                symbolPassX.style.display = 'none';
            } else {
                symbolPassCheck.style.display = 'none';
                symbolPassX.style.display = 'block';
            }

            if (numberPass) {
                numberPassCheck.style.display = 'block';
                numberPassX.style.display = 'none';
            } else {
                numberPassCheck.style.display = 'none';
                numberPassX.style.display = 'block';
            }
        });

        //password entropy indicator
        const lowIndicator = document.getElementById('lowIndicator');
        const mediumIndicator = document.getElementById('mediumIndicator');
        const highIndicator = document.getElementById('highIndicator');

        passwordBox.addEventListener('input', () => {
            const password = passwordBox.value;
            const entropy = Math.log2(Math.pow(94, password.length));

            if (password.length === 0) {
                lowIndicator.style.backgroundColor = 'rgb(107 114 128)';
                mediumIndicator.style.backgroundColor = 'rgb(107 114 128)';
                highIndicator.style.backgroundColor = 'rgb(107 114 128)';

                userEntropyPass = false;
            } else if (entropy < 48) {
                lowIndicator.style.backgroundColor = 'rgb(239 68 68)';
                mediumIndicator.style.backgroundColor = 'rgb(107 114 128)';
                highIndicator.style.backgroundColor = 'rgb(107 114 128)';

                userEntropyPass = false;
            } else if (entropy < 79) {
                lowIndicator.style.backgroundColor = 'rgb(239 68 68)';
                mediumIndicator.style.backgroundColor = 'rgb(249 115 22)';
                highIndicator.style.backgroundColor = 'rgb(107 114 128)';

                userEntropyPass = false;
            } else {
                lowIndicator.style.backgroundColor = 'rgb(239 68 68)';
                mediumIndicator.style.backgroundColor = 'rgb(249 115 22)';
                highIndicator.style.backgroundColor = 'rgb(34 197 94)';

                userEntropyPass = true;
            }
        });


        showIcon.addEventListener('click', () => {
            passwordBox.setAttribute('type', 'text');
            showIcon.style.display = 'none';
            hideIcon.style.display = 'block';
        });

        hideIcon.addEventListener('click', () => {
            passwordBox.setAttribute('type', 'password');
            showIcon.style.display = 'block';
            hideIcon.style.display = 'none';
        });

        showConfirmIcon.addEventListener('click', () => {
            confirmPassBox.setAttribute('type', 'text');
            showConfirmIcon.style.display = 'none';
            hideConfirmIcon.style.display = 'block';
        });

        hideConfirmIcon.addEventListener('click', () => {
            confirmPassBox.setAttribute('type', 'password');
            showConfirmIcon.style.display = 'block';
            hideConfirmIcon.style.display = 'none';
        });

        //Pop over indicator
        const popoverContent = document.getElementById('popoverContent');

        passwordBox.addEventListener('focus', () => {
            popoverContent.classList.remove('hidden');
        });

        passwordBox.addEventListener('blur', () => {
            popoverContent.classList.add('hidden');
        });

        function validateForm() {
            userNameStatus = false;
            emailStatus = false;
            passwordStatus = false;
            confirmPasswordStatus = false;

            if (usernameBox.value === '') {
                userNameError.innerHTML = '*กรุณากรอกชื่อผู้ใช้';
                usernameBox.style.borderColor = 'rgb(244 63 94)';
            } else {
                userNameError.innerHTML = '';
                usernameBox.style.borderColor = 'black';
                userNameStatus = true;
            }

            if (emailBox.value === '') {
                emailError.innerHTML = '*กรุณากรอกอีเมลล์';
                emailBox.style.borderColor = 'rgb(244 63 94)';
            } else {
                const emailFormat = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
                if (!emailBox.value.match(emailFormat)) {
                    emailError.innerHTML = '*รูปแบบอีเมลล์ไม่ถูกต้อง';
                    emailBox.style.borderColor = 'rgb(244 63 94)';
                } else {
                    emailError.innerHTML = '';
                    emailBox.style.borderColor = 'black';
                    emailStatus = true;
                }
            }

            if (passwordBox.value === '') {
                passwordError.innerHTML = '*กรุณากรอกรหัสผ่าน';
                passwordBox.style.borderColor = 'rgb(244 63 94)';
            } else {
                //check length password > 8
                const lengthPass = passwordBox.value.length >= 8;
                if (!lengthPass) {
                    passwordError.innerHTML = '*รหัสผ่านต้องมากกว่า 8 ตัวอักษร';
                    passwordBox.style.borderColor = 'rgb(244 63 94)';
                } else if (userEntropyPass === false) {
                    passwordError.innerHTML = '*รหัสผ่านไม่ปลอดภัย กรุณาทำตามคำแนะนำ';
                    passwordBox.style.borderColor = 'rgb(244 63 94)';
                } else {
                    passwordError.innerHTML = '';
                    passwordBox.style.borderColor = 'black';
                    passwordStatus = true;
                }

            }

            if (confirmPassBox.value === '') {
                confirmPasswordError.innerHTML = '*กรุณายืนยันรหัสผ่าน';
                confirmPassBox.style.borderColor = 'rgb(244 63 94)';
            } else {
                if (confirmPassBox.value !== passwordBox.value) {
                    confirmPasswordError.innerHTML = '*รหัสผ่านไม่ตรงกัน';
                    confirmPassBox.style.borderColor = 'rgb(244 63 94)';
                    passwordBox.style.borderColor = 'rgb(244 63 94)';
                } else {
                    confirmPasswordError.innerHTML = '';
                    confirmPassBox.style.borderColor = 'black';
                    confirmPasswordStatus = true;
                }
            }

            if (userNameStatus && emailStatus && passwordStatus && confirmPasswordStatus && userEntropyPass) {
                $.ajax({
                    type: 'POST',
                    url: '../../Backend/UserManage/checkUserAccount.php',
                    data: {
                        action: 'checkUsername',
                        username: usernameBox.value
                    },
                    success: function(response) {
                        if (response === 'username exists') {
                            userNameError.innerHTML = '*ชื่อผู้ใช้นี้ถูกใช้งานแล้ว';
                            usernameBox.style.borderColor = 'rgb(244 63 94)';
                        } else {
                            userNameError.innerHTML = '';
                            usernameBox.style.borderColor = 'black';
                            $.ajax({
                                type: 'POST',
                                url: '../../Backend/UserManage/checkUserAccount.php',
                                data: {
                                    action: 'checkEmail',
                                    email: emailBox.value
                                },
                                success: function(response) {
                                    if (response === 'email exists') {
                                        emailError.innerHTML = '*อีเมลล์นี้ถูกใช้งานแล้ว';
                                        emailBox.style.borderColor = 'rgb(244 63 94)';
                                    } else {
                                        emailError.innerHTML = '';
                                        emailBox.style.borderColor = 'black';
                                        document.getElementById('insertForm').submit();
                                    }
                                }
                            })
                        }
                    }
                })

            }
        }
    </script>
</body>

</html>