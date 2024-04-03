<?php
// ข้อมูลที่ต้องการเข้ารหัส
$data = "123564844646";

// สร้างคีย์สำหรับการเข้ารหัส (ตัวอย่างใช้ขนาด 256 บิต)
$encryptionKey = openssl_random_pseudo_bytes(32);

// สร้าง IV (Initialization Vector)
$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-gcm'));

// สร้างแท็กสำหรับการตรวจสอบความถูกต้องของข้อมูล
$tag = 'SDFhiohsfihafsjizzsdf';

// เข้ารหัสข้อมูล
$ciphertext = openssl_encrypt($data, 'aes-256-gcm', $encryptionKey, $options = 0, $iv, $tag);

// ตรวจสอบว่าการเข้ารหัสสำเร็จหรือไม่
if ($ciphertext === false) {
    die('การเข้ารหัสล้มเหลว');
}

echo "ข้อมูลที่เข้ารหัส: " . $ciphertext . "<br>";
echo "IV: " . bin2hex($iv) . "<br>";
echo "แท็ก: " . bin2hex($tag) . "<br>";
echo "คีย์: " . bin2hex($encryptionKey) . "<br>";

// คีย์สำหรับการถอดรหัส (ต้องตรงกับที่ใช้เข้ารหัส)
$decryptionKey = $encryptionKey;

// ถอดรหัสข้อมูล
$originalData = openssl_decrypt($ciphertext, 'aes-256-gcm', $decryptionKey, $options = 0, $iv, $tag);

// ตรวจสอบว่าการถอดรหัสสำเร็จหรือไม่
if ($originalData === false) {
    die('การถอดรหัสล้มเหลว');
}

echo "ข้อมูลต้นฉบับ: " . $originalData . "\n";
