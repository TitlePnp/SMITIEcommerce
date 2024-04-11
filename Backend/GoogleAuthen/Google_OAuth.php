<?php
require_once '../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../Components', 'config.env');
$dotenv->load();

$client_ID = $_ENV['Google_Client_ID'];
$client_Secret = $_ENV['Google_Client_Secret'];
$redirect_URI = $_ENV['Google_Redirect'];

$client = new Google_Client();
$client->setClientId($client_ID);
$client->setClientSecret($client_Secret);
$client->setRedirectUri($redirect_URI);
$client->addScope("email");
$client->addScope("profile");


