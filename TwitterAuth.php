<?php

use Abraham\TwitterOAuth\TwitterOAuth;

class TwitterAuth {
    
    // Declaring Variables
    protected $client;
    protected $callBack = "http://www.webapplication.website/twitter.php";
    protected $appKey = "yMtKLR8fIg7bDpQmOtOSWF9PU";
    protected $appSecret = "ShtNINtmEZd93YGWlRPZw5rGQk6poWORze5HZSLeljqNq5L20b";

    public function __construct(TwitterOAuth $client)
    {
        $this->client = $client;
    }

    public function generateAccessToken()
    {
        if (!isset($_SESSION['twitter_auth'])) {
            return $this->client->oauth('oauth/request_token', ['oauth_callback' => $this->callBack]);
        }

        return "Cannot generate token.";
    }

    public function storeToken()
    {
        if (!isset($_SESSION['twitter_auth'])) {
            // storing the token into session
            $accessToken = $this->generateAccessToken();
            $_SESSION['twitter_auth'] = true;
            $_SESSION['oauth_token'] = $accessToken['oauth_token'];
            $_SESSION['oauth_token_secret'] = $accessToken['oauth_token_secret'];

            return $accessToken;
        }

        return false;
    }

    public function getUrl()
    {
        $this->storeToken();
        return $this->client->url('oauth/authorize', ['oauth_token' => $_SESSION['oauth_token']]);
    }

    protected function verifyToken()
    {
        $requestToken = [];
        $requestToken['oauth_token'] = $_SESSION['oauth_token'];
        $requestToken['oauth_token_secret'] = $_SESSION['oauth_token_secret'];

        unset($_SESSION['twitter_auth']);

        if (isset($_REQUEST['oauth_token']) && $requestToken['oauth_token'] !== $_REQUEST['oauth_token']) {
            return false;
        }

        return $requestToken;
    }

    public function getPayload()
    {
        $requestToken = $this->verifyToken();
        if (!$requestToken) {
            return false;
        }

        $connection = new TwitterOAuth($this->appKey, $this->appSecret, $requestToken['oauth_token'], $requestToken['oauth_token_secret']);
        $accessToken = $connection->oauth('oauth/access_token', ['oauth_verifier' => $_REQUEST['oauth_verifier']]);

        $connection = new TwitterOAuth($this->appKey, $this->appSecret, $accessToken['oauth_token'], $accessToken['oauth_token_secret']);

        $payload = $connection->get('account/verify_credentials', ['include_email' => 'true']);
        return $payload;
    }

    public function setPayload($payload)
    {
        $_SESSION['payload'] = $payload;
        return;
    }
}