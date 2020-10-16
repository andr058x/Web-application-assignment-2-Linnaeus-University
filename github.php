<?php
session_start();

if (isset($_GET['code'])) {

    $signup = false;
    $callbackCode = $_GET['code'];
    $clientId = 'fcba84f661d5a301a1c4';
    $clientSecret = '0113e313c0d04a930cad36017ec4afbf3158ae95';
    $redirectUrl = 'http://socialauth.live/github.php';

    $post = http_build_query([
        'client_id' => $clientId,
        'client_secret' => $clientSecret,
        'redirect_url' => $redirectUrl,
        'code' => $callbackCode
    ]);

    $accessData = file_get_contents("https://github.com/login/oauth/access_token?{$post}");
    $explode = explode('access_token=', $accessData);
    $explode = explode('&scope=user', $explode[1]);
    $accessToken = $explode[0];
    
    $opts = [
        'http' => [
            'method' => 'GET',
            'header' => [
                'User-Agent: PHP',
                "Authorization: Bearer {$accessToken}"
            ]
        ]
    ];
    $context = stream_context_create($opts);

    // Fetching User Data
    $apiUserUrl = "https://api.github.com/user";
    $userInfo = file_get_contents($apiUserUrl, false, $context);
    $userInfo = json_decode($userInfo);
    
    $_SESSION['auth_login'] = true;
    $_SESSION['auth_type'] = 'github';
    $_SESSION['access_token'] = $accessToken;
    $_SESSION['payload'] = json_encode($userInfo);

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

die(json_encode([
    'error' => false,
    'message' => "This file should be return from GitHub!"
]));