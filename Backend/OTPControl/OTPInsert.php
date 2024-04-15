<?php
require '../../Components/ConnectDB.php';
require '../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//settime zone
date_default_timezone_set("Asia/Bangkok");

// $Email = $_POST['Email'];
$Email = "pphanupong.gdev@gmail.com";
$mail = new PHPMailer(true);

try {

    $stmt = $connectDB->prepare("SELECT CusID FROM `CUSTOMER_ACCOUNT` WHERE Email = ?");
    $stmt->bind_param("s", $Email);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $CusID = $row['CusID'];

    //random opt 6 digits
    $OTP = rand(100000, 999999);
    $StartTime = date("Y-m-d H:i:s");
    $EndTime = date("Y-m-d H:i:s", strtotime('+5 minutes'));


    $stmt = $connectDB->prepare("INSERT INTO OTP_RECOVER(CusID, EMAIL, OTP, START_TIME, END_TIME) VALUES(?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $CusID, $Email, $OTP, $StartTime, $EndTime);
    $stmt->execute();

    $mail->isHTML(true);
    $mail->Subject = 'Recover Password OTP';
    $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
    $mail->AltBody = "Recover Password OTP: ' . $OTP . ' Please use this OTP to recover your password.";
    $mail->send();

    //sent email to Email
    // $to = $Email;
    // $subject = "OTP Recover Password";
    // $message = "Your OTP is: " . $OTP;
    // $headers = "From: SMITI STORE(OFFICIAL)";
    // mail($to, $subject, $message, $headers);
    echo "SendOTPSuccess";
} catch (Exception $e) {
    echo "SendOTPFailed<br>";
    echo $e;
    exit;
}
