<?php
include __DIR__ . '/config.php';

if (isset($_GET['code'])) {

    $code = $_GET['code'];
    $token = $googleClient->fetchAccessTokenWithAuthCode($code);

    if (!isset($token['error'])) {

        $signup = false;
        $accessToken = $token['access_token'];
        $googleClient->setAccessToken($accessToken);

        $_SESSION['auth_login'] = true;
        $_SESSION['auth_type'] = 'gmail';
        $_SESSION['access_token'] = $accessToken;

        $googleService = new Google_Service_Oauth2($googleClient);
        $profile = $googleService->userinfo->get();

        $_SESSION['payload'] = json_encode($profile);

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
}

die(json_encode([
    'error' => false,
    'message' => "This file should be return from Google!"
]));