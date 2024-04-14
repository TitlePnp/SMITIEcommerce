<?php

require_once "../vendor/autoload.php";

// $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../Components', 'config.env');
// $dotenv->load();

// use Firebase\JWT\Key;
// use \Firebase\JWT\jwt;

// $key = $_ENV['JWT_KEY'];

// // ข้อมูลที่ต้องการเข้ารหัส
// $data = "123Testadsji";

// // สร้างคีย์สำหรับการเข้ารหัส (ตัวอย่างใช้ขนาด 256 บิต)
// $encryptionKey = $_ENV['ENCRYPT_KEY'];

// // สร้าง IV (Initialization Vector)
// $iv = $_ENV['IV'];

// // สร้างแท็กสำหรับการตรวจสอบความถูกต้องของข้อมูล
// // $tag = $_ENV['TAG'];

// $tag = "";

// // เข้ารหัสข้อมูล
// $ciphertext = openssl_encrypt($data, 'aes-256-gcm', $encryptionKey, $options = 0, $iv, $tag);

// // ตรวจสอบว่าการเข้ารหัสสำเร็จหรือไม่
// if ($ciphertext === false) {
//     die('การเข้ารหัสล้มเหลว');
// }


// echo "ข้อมูลที่เข้ารหัส: " . $ciphertext . "<br>";
// echo "IV: " . bin2hex($iv) . "<br>";
// echo "แท็ก bin: " . bin2hex($tag) . "<br>";
// echo "แท็ก hex: " . hex2bin($tag) . "<br>";
// echo "คีย์: " . bin2hex($encryptionKey) . "<br>";

// echo "ข้อมูลที่เข้ารหัส: " . $ciphertext . "<br>";
// echo "IV: " . $iv . "<br>";
// echo "แท็ก: " . bin2hex($tag) . "<br>";
// echo "คีย์: " . $encryptionKey . "<br>";

$ciphertext = "idUCLJUVGG+hnrauYg==";

// คีย์สำหรับการถอดรหัส (ต้องตรงกับที่ใช้เข้ารหัส)
$decryptionKey = "a732e4d6120e788227b613711adc58bf0c1a196282a43329d57748db9e62d36f";

$tag = "de6fc071440e50a33194359b58290371";

$tag = hex2bin($tag);

$iv = "4209b82e49dcb7ef719f0577";

// ถอดรหัสข้อมูล
$originalData = openssl_decrypt($ciphertext, 'aes-256-gcm', $decryptionKey, $options = 0, $iv, $tag);

// ตรวจสอบว่าการถอดรหัสสำเร็จหรือไม่
if ($originalData === false) {
    die('การถอดรหัสล้มเหลว');
}

echo "ข้อมูลต้นฉบับ: " . $originalData . "\n";

// if () {

// }

// <?php
// require_once "../../vendor/autoload.php";

// $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../Components', 'config.env');
// $dotenv->load();

// use Firebase\JWT\Key;
// use \Firebase\JWT\jwt;

// $key = $_ENV['JWT_KEY'];

// $taxID ="1234567891235";

// $encryptionKey = $_ENV['ENCRYPT_KEY'];
// $iv = $_ENV['IV'];
// $tag = $_ENV['TAG'];
// $ciphertext = openssl_encrypt($taxID, 'aes-256-gcm', $encryptionKey, $options = 0, $iv, $tag);

// echo $ciphertext;
