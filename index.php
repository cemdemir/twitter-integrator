<?php

session_start();
include 'Twitter.php';

// URL
$url = 'https://api.twitter.com/1.1/users/lookup.json';

// Params
$params = array(
	'screen_name' => 'goddamnclever',
);

$config = array(

);

$config = array(
	'oauth_token' 			=> $_SESSION['oauth_token'],
	'oauth_token_secret' 	=> $_SESSION['oauth_token_secret'],
);

// Request
$response = Twitter::make()->get($url, $params, $config);

// Response
print_r(json_decode($response));

?>