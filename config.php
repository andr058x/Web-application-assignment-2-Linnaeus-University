<?php
// opening all errors for website
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require __DIR__ . '/vendor/autoload.php';

// declare variables for authenticate redirect
$googleAuthUrl = '#!';
$twitterAuthUrl = '#!';
$githubAuthUrl = '#!';

// Gmail Authentication Setting
$googleClient = new Google_Client();
$googleClient->setClientId('257764872986-sl9fcfnofs681hvq947qe366ko561asl.apps.googleusercontent.com');
$googleClient->setClientSecret('EdY6tfEf1O4yoGcK2cMfqUIY');
$googleClient->setRedirectUri('http://www.webapplication.website/google.php');
$googleClient->addScope('email');
$googleClient->addScope('profile');


// Twitter Authentication setting
use Abraham\TwitterOAuth\TwitterOAuth;

require __DIR__ . '/TwitterAuth.php';

$twitterAppKey = "yMtKLR8fIg7bDpQmOtOSWF9PU";
$twitterAppSecret = "ShtNINtmEZd93YGWlRPZw5rGQk6poWORze5HZSLeljqNq5L20b";

$twitterOAuth = new TwitterOAuth($twitterAppKey, $twitterAppSecret);
$twitter = new TwitterAuth($twitterOAuth);
$twitterAuthUrl = $twitter->getUrl();


// Github Authentication setting
$githubClientId = 'fcba84f661d5a301a1c4';
$githubRedirectUri = 'http://www.webapplication.website/github.php';
$githubAuthUrl = "https://github.com/login/oauth/authorize?client_id={$githubClientId}&redirect_url={$githubRedirectUri}&scope=user";
