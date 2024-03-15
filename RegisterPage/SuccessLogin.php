<?php
session_start();

?>
<!DOCTYPE html>
<html>

<head>
    <title>Success Login</title>
    <link rel="stylesheet" type="text/css" href="SuccessLogin.css" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <style>
        body h1 {
            text-align: center;
        }
    </style>
</head>

<body>
    <H1>Login Success</H1>
    <form method="POST" action="./admin.php">
    <button type="submit">Admin Control</button>
    </form>
    <form action="./customer.php">
        <button type="submit">Home</button>
    </form>
    <p id="showError"></p>

</body>

</html>