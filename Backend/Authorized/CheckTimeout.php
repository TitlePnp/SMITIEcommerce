<?php
require '../../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../Components', 'config.env');
$dotenv->load();

use Firebase\JWT\Key;
use \Firebase\JWT\JWT;

$key = $_ENV['JWT_KEY'];

if (isset($_SESSION['tokenJWT'])) {
  $jwt = $_SESSION['tokenJWT'];
  try {
    $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
  } catch (Exception $e) {
    session_unset();
    session_destroy();
    header('Location: ../../Frontend/MainPage/Home.php');
    exit();
  }
}