<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

require __DIR__ . '/vendor/autoload.php';

use Abraham\TwitterOAuth\TwitterOAuth;

require __DIR__ . '/TwitterAuth.php';

if (isset($_GET['oauth_token'])) {

    $signup = false;
    $twitterAppKey = "yMtKLR8fIg7bDpQmOtOSWF9PU";
    $twitterAppSecret = "ShtNINtmEZd93YGWlRPZw5rGQk6poWORze5HZSLeljqNq5L20b";

    $twitterOAuth = new TwitterOAuth($twitterAppKey, $twitterAppSecret);
    $twitter = new TwitterAuth($twitterOAuth);

    $payload = $twitter->getPayload();

    $_SESSION['auth_login'] = true;
    $_SESSION['auth_type'] = 'twitter';
    $_SESSION['access_token'] = "False";

    $_SESSION['payload'] = json_encode($payload);

    require __DIR__ . '/Database.php';
    $database = new Database;

    $payload = json_decode($_SESSION['payload'], true);
    $email = $payload['email'];
    $ifExits = $database->checkIfEmailExist($email);
    $param = '';
    
    if (is_bool($ifExits)) {
        
        if ($ifExits === true) $signup = true;
    } else {
        $signup = true;
        $param = "?error=" . $ifExits;
    }

    $redirectTo = $signup ? 'process-signup' : 'dashboard';

    header("Location: /{$redirectTo}.php{$param}");
    exit;
}

if (isset($_GET['denied'])) {
    header("Location: /");
    exit;
}

die(json_encode([
    'error' => false,
    'message' => "This file should be return from Twitter!"
]));