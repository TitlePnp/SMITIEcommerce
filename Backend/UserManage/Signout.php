<?php
session_start();
session_unset();
session_destroy();
header("Location: ../../Frontend/SignIn_Page/SignIn.php");
?>