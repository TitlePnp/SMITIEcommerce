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
    <div class="flex flex-col justify-center items-center min-h-3/6">
        <div class="bg-white p-10 rounded-md shadow-lg">
            <div>
                <?php
                echo '<a href="../SignIn_Page/SignIn.php" class="hover:text-blue-800 text-blue-500 font-md"><i class="bx bx-arrow-back mr-2"></i>ย้อนกลับ</a>';
                ?>
            </div>
            <div class="text-center">
                <p class="font-medium text-lg mb-5">กรุณากรอกอีเมลสำหรับกู้คืนรหัสผ่าน</p>
            </div>
            <form id="loginForm" method="POST" action="../../Backend/UserManage/LoginCustomer.php">
                <div class="input-group">
                    <input type="text" id="Email" name="RecoverMail" required>
                    <label for="">อีเมล</label>
                </div>
                <div class="flex justify-between mt-2">
                    <span id="RecoverMailError" class="text-sm text-red-500 mb-2"></span>
                    <div class="flex justify-end">
                        <button id="SendOTPButton" type="button" class="text-sm text-blue-500 hover:text-blue-700">ส่งรหัส OTP</button>
                        <button id="ResendOTPButton" type="button" style="display: none;" class="text-sm text-blue-500 hover:text-blue-700">ส่งรหัสอีกครั้ง</button>
                    </div>
                </div>
                <div id="OTPInput" class="input-group mt-5" style="display: none;">
                    <input type="text" id="OTP" name="OTPcode" required>
                    <label for="">OTP</label>
                </div>

                <button type="button" onclick="validateForm()" class="rounded-md w-full bg-gray-300 text-white px-4 py-2 mt-5 hover:shadow-xl" disabled>ยืนยันรหัส</button>
        </div>
    </div>

    <script>
        var SendOTPButton = document.getElementById('SendOTPButton');

        SendOTPButton.addEventListener('click', function() {
            var Email = document.getElementById('Email').value;
            // Check value email and check pattern email from regex
            if (Email == "") {
                document.getElementById('RecoverMailError').style.display = 'block';
                document.getElementById('RecoverMailError').innerHTML = '*กรุณากรอกอีเมล';
                document.getElementById('Email').style.border = '1px solid red';
            } else if (!Email.match(/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/g)) {
                document.getElementById('RecoverMailError').style.display = 'block';
                document.getElementById('RecoverMailError').innerHTML = '*รูปแบบอีเมลไม่ถูกต้อง';
                document.getElementById('Email').style.border = '1px solid red';
            } else {
                document.getElementById('RecoverMailError').innerHTML = '';
                document.getElementById('Email').style.border = '1px solid black';
                this.style.display = 'none';
                document.getElementById('ResendOTPButton').style.display = 'block';
                document.getElementById('OTPInput').style.display = 'block';
                startTimer();
                $.ajax({
                    type: "POST",
                    url: "../../Backend/UserManage/checkUserAccount.php",
                    data: {
                        email: Email,
                        action: 'checkEmail'
                    },
                    success: function(response) {
                        if (response == "email exists") {
                            $.ajax({
                                type: "POST",
                                url: "../../Backend/OTPControl/OTPInsert.php",
                                data: {
                                    email: Email
                                },
                                success: function(response) {
                                    if (response == "SendOTPSuccess") {
                                        alert("ส่งรหัส OTP สำเร็จ");
                                    } else {
                                        alert("ส่งรหัส OTP ไม่สำเร็จ");
                                    }

                                },
                                error: function(jqXHR, textStatus, errorThrown) {
                                    document.getElementById('RecoverMailError').innerHTML = '*ไม่สามารถส่งรหัส OTP';
                                } 
                            });
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        document.getElementById('RecoverMailError').innerHTML = '*เกิดข้อผิดพลาดในการส่งรหัส OTP';
                    }
                });
            }
        });

        var resendButton = document.getElementById('ResendOTPButton');
        var countDownTime = 60; // 60 seconds

        function startTimer() {
            var timer = setInterval(function() {
                countDownTime--;
                resendButton.textContent = `ส่งรหัสอีกครั้ง(${countDownTime})`;

                if (countDownTime <= 0) {
                    clearInterval(timer);
                    resendButton.textContent = 'ส่งรหัสอีกครั้ง';
                    resendButton.disabled = false;
                    countDownTime = 60;
                }
            }, 1000);
        }

        resendButton.addEventListener('click', function() {
            OTPInput.style.display = 'block';
            this.disabled = true;
            startTimer();
        });


        // var RecoverMail = document.getElementById('Email');
        // var RecoverMailError = document.getElementById('RecoverMailError');

        // const validateForm = () => {
        //     let email = document.getElementById('Email').value;
        //     if (email == "") {
        //         RecoverMailError.style.display = 'block';
        //         RecoverMailError.innerHTML = '*กรุณากรอกอีเมล';
        //         RecoverMail.style.border = '1px solid red';
        //     } //check pattern email from regex
        //     else if (!email.match(/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/g)) {
        //         RecoverMailError.style.display = 'block';
        //         RecoverMailError.innerHTML = '*รูปแบบอีเมลไม่ถูกต้อง';
        //         RecoverMail.style.border = '1px solid red';
        //     } else {
        //         RecoverMailError.style.display = 'none';
        //         RecoverMail.style.border = '1px solid black';

        //         $.ajax({
        //             type: "POST",
        //             url: "../../Backend/UserManage/checUserAccount.php",
        //             data: {
        //                 email: email,
        //                 action: 'checkEmails'
        //             },
        //             success: function(response) {
        //                 if (response == "email exists") {
        //                     alert("ส่งรหัสผ่านสำเร็จ");
        //                     window.location.href = "../SignIn_Page/SignIn.php";
        //                 }
        //             }
        //         });
        //     }
        // }

        // var countDownDate = new Date().getTime() + 15 * 60000; // 15 minutes from now

        // var x = setInterval(function() {
        //     var now = new Date().getTime();
        //     var distance = countDownDate - now;

        //     var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        //     var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        //     document.getElementById("timer").innerHTML = minutes + "m " + seconds + "s ";

        //     if (distance < 0) {
        //         clearInterval(x);
        //         document.getElementById("timer").innerHTML = "EXPIRED";
        //     }
        // }, 1000);

        // var OTPInput = document.getElementById('OTPInput');
    </script>
</body>

</html>