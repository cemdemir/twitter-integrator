<?php

include 'Twitter.php';

$twitter 	= Twitter::make();
$response 	= $twitter->requestToken();
$authorize 	= $twitter->authorizeUrl($response['oauth_token']);

header("Location: $authorize"); exit;

?>