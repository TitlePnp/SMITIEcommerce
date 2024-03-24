<?php
require_once '../../vendor/autoload.php';
$client_ID = '62789548374-1jkuhe614c9usvbugpqhbid2keebmqm2.apps.googleusercontent.com';
$client_Secret = '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';
$redirect_URI = 'http://localhost:8765/SmitiShop/Backend/GoogleAuthen/GoogleManage.php';

$client = new Google_Client();
$client->setClientId($client_ID);
$client->setClientSecret($client_Secret);
$client->setRedirectUri($redirect_URI);
$client->addScope("email");
$client->addScope("profile");


