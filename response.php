<?php

session_start();
include 'Twitter.php';

$tempTokens = array(
	'oauth_token' 		=> $_GET['oauth_token'],
	'oauth_verifier' 	=> $_GET['oauth_verifier']
);

$twitter 	= Twitter::make();
$response 	= $twitter->accessToken($tempTokens);

$_SESSION['oauth_token'] 		= $response['oauth_token'];
$_SESSION['oauth_token_secret'] = $response['oauth_token_secret'];
$_SESSION['user_id'] 			= $response['user_id'];
$_SESSION['screen_name'] 		= $response['screen_name'];

?>