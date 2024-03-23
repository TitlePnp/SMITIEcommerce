<?php
require_once '../vendor/autoload.php';

$client_ID = '62789548374-1jkuhe614c9usvbugpqhbid2keebmqm2.apps.googleusercontent.com';
$client_Secret = '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';
$redirect_URI = 'http://localhost:8765/SmitiShop/Frontend/MainPage/Home.php';

$client = new Google_Client();
$client->setClientId($client_ID);
$client->setClientSecret($client_Secret);
$client->setRedirectUri($redirect_URI);
$client->addScope("email");
$client->addScope("profile");

if (isset($_GET['code'])) {

    if (isset($token['error'])) {
        // Redirect to login page
        header('Location: http://localhost:8765/SmitiShop/Frontend/SignIn_Page/SignIn.html');
        exit;
    }


    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token['access_token']);

    $google_oauth = new Google_Service_Oauth2($client);

    $google_oauth_account_info = $google_oauth->userinfo->get();
    $email = $google_oauth_account_info->email;
    $name = $google_oauth_account_info->name;
    $userToken = $google_oauth_account_info->id;

    session_start();
    $_SESSION['email'] = $email;
    $_SESSION['name'] = $name;
    $_SESSION['userToken'] = $userToken;
}
