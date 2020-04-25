<?php
require_once "vendor/autoload.php";
// init configuration
$clientID = 'http://158115967922-0g334aa81m1bk7e09a3go97oiquo80cs.apps.googleusercontent.com';
$clientSecret = 'A9pF_X-KAWrnfIZwfJLaf_uE';
$redirectUri = 'https://matcha.fun/redirect.php';

// create Client Request to access Google API
$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope("email");
$client->addScope("profile");

// authenticate code from Google OAuth Flow
if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token['access_token']);

    // get profile info
    $google_oauth = new Google_Service_Oauth2($client);
    $google_account_info = $google_oauth->userinfo->get();
    $email =  $google_account_info->email;
    $name =  $google_account_info->name;

    // now you can use this profile info to create account in your website and make user logged in.
} else {
    echo "<a href='".$client->createAuthUrl()."'>Google Login</a>";
}
?>