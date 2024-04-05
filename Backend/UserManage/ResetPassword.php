<?php
$otp = mt_rand(100000, 999999);

// Save OTP in database...

// The message
$msg = "Your OTP is: $otp";

// Use wordwrap() if lines are longer than 70 characters
$msg = wordwrap($msg, 70);

// Send email
mail("someone@example.com", "OTP for password reset", $msg);
