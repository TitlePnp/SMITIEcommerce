<?php
require '../../Backend/Authorized/SuperAdminAuthorized.php';
require '../../Backend/Authorized/ManageHeader.php';
require '../../Backend/SuperAdmin/UserManage.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link type="text/css" rel="stylesheet" href="https://cdn.jsdelivr.net/gh/alumuko/vanilla-datetimerange-picker@latest/dist/vanilla-datetimerange-picker.css">
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/gh/alumuko/vanilla-datetimerange-picker@latest/dist/vanilla-datetimerange-picker.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kodchasan:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>SMITI:Admin Manage</title>

    <style>
        * {
            font-family: 'Kodchasan';
            padding: 0px;
            margin: 0px;
        }
    </style>
</head>

<body>
    <div class="px-28">
        <div class="flex-col">
            <div class="flex justify-center">
                <h1 class="font-bold text-2xl">การจัดการผู้ดูแล</h1>
            </div>
            <div class="flex justify-end items-center h-2 mt-10">
                <div id="AddAdmin" class="bg-green-500 hover:bg-green-600 rounded-lg flex items-center p-3 w-32 hover:cursor-pointer hover:shadow-lg">
                    <button type="button" class="w-32 font-semibold text-white ">เพิ่มผู้ดูแล</button>
                    <i class='bx bxs-user-plus text-white text-lg'></i>
                </div>
            </div>
            <div class="my-10">
                <table class="w-full rounded-lg border border-collapse">
                    <tr class="bg-gray-400 ">
                        <th class="p-5">รหัสประจำตัว</th>
                        <th class="p-5">อีเมล</th>
                        <th class="p-5">ชื่อผู้ใช้</th>
                        <th class="p-5">หน้าที่</th>
                        <th class="p-5">ลบ</th>
                    </tr>
                    <?php
                    $result = getAllAdmin();
                    while ($row = $result->fetch_assoc()) {
                    ?>
                        <tr class="text-center hover:bg-gray-300">
                            <td class="p-6"><?php echo $row['CusID'] ?></td>
                            <td class="p-6"><?php echo $row['Email'] ?></td>
                            <td class="p-6"><?php echo $row['UserName'] ?></td>
                            <td class="p-6"><?php echo $row['Role'] ?></td>
                            <td class="p-6"><button id="delRole" type="button" class="p-3 bg-red-500 rounded-lg hover:bg-red-600 w-16 font-semibold text-white hover:shadow-lg" onclick="deleteUser('<?php echo $row['CusID']; ?>', '<?php echo $row['Email']; ?>', '<?php echo $row['UserName']; ?>')"><i class='bx bx-trash'></i></button></td>
                        </tr>
                    <?php
                    }
                    ?>

                </table>
            </div>
            <div id="Input" class="flex flex-col" style="display: none;">
                <div class="flex">
                    <input id="EmailInput" type="text" class="border p-2 mr-2 rounded-lg" placeholder="กรุณากรอกอีเมล"></input>
                    <input id="UserNameInput" type="text" class="border p-2 mx-2 rounded-lg" placeholder="กรุณากรอกชื่อผู้ใช้"></input>
                    <input id="RoleInput" type="text" class="border p-2 ml-2 rounded-lg" placeholder="กรุณากรอกตำแหน่ง"></input>
                    <button id="OKbtn" class="ml-10 px-5 bg-green-500 rounded-lg text-white">ตกลง</button>
                </div>
                <span id="AddAdminError" class="my-3 text-sm text-red-500"></span>
            </div>
            <div class="flex h-14 items-center">

                <div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var AddAdmin = document.getElementById('AddAdmin');
        var Input = document.getElementById('Input');
        var OKbtn = document.getElementById('OKbtn');
        var AddAdminError = document.getElementById('AddAdminError');

        var EmailInput = document.getElementById('EmailInput');
        var UserNameInput = document.getElementById('UserNameInput');
        var RoleInput = document.getElementById('RoleInput');

        AddAdmin.addEventListener('click', function() {
            Input.style.display = 'flex';
        });

        OKbtn.addEventListener('click', function() {
            var Email = EmailInput.value;
            var UserName = UserNameInput.value;
            var Role = RoleInput.value;
            if (Email == '' || UserName == '' || Role == '') {
                AddAdminError.innerHTML = '*กรุณากรอกข้อมูลให้ครบถ้วน';
            } else if (Role != 'Admin' && Role != 'SuperAdmin') {
                AddAdminError.innerHTML = '*ตำแหน่งไม่ถูกต้อง(Admin, SuperAdmin)';
            } else {
                $.post('../../Backend/SuperAdmin/AdminManage.php', {
                    Email: Email,
                    UserName: UserName,
                    Role: Role,
                    action: 'Add'
                }, function(data) {
                    if (data == 'Success') {
                        location.reload();
                    } else {
                        AddAdminError.innerHTML = data;
                    }
                });
            }
        });

        function deleteUser(CusID, Email, UserName, Role) {
            $.post('../../Backend/SuperAdmin/AdminManage.php', {
                CusID: CusID,
                Email: Email,
                UserName: UserName,
                Role: Role,
                action: 'Delete'
            }, function(data) {
                if (data == 'Success') {
                    location.reload();
                } else {
                    alert(data);
                }
            });
        }
    </script>
</body>

</html>