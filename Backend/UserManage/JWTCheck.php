<?php
require '../../vendor/autoload.php';
require 'SessionManage.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../Components', 'config.env');
$dotenv->load();

use Firebase\JWT\Key;
use \Firebase\JWT\JWT;

$key = $_ENV['JWT_KEY'];

function validate_jwt($jwt) {
    global $key;
    try {
        $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
        return true;
    } catch (\Firebase\JWT\ExpiredException $e) {
        return false;
    } catch (\Exception $e) {
        // An error occurred.
        return false;
    }
}
?>