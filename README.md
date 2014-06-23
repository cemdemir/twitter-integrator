Twitter Integrator
==================

twitter-integrator is a PHP client library to use Twitter Rest API.

Basic Usage
------------------

You can make a basic request without access tokens. It means, the request is made by application itself.

**URL**
    
    $url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';

**Parameters**

    $params = array(
        'screen_name'   => 'php_net',
        'count'         => 100
    );

**Request**
    
    $response = Twitter::make()->get($url, $params);

Login with Twitter 
------------------

    $twitter    = Twitter::make();
    $response   = $twitter->requestToken();
    $authorize  = $twitter->authorizeUrl($response['oauth_token']);
    header("Location: $authorize"); exit;

How to Get Real Tokens
------------------

**Twitter Response**

    $tempTokens = array(
        'oauth_token'       => $_GET['oauth_token'],
        'oauth_verifier'    => $_GET['oauth_verifier']
    );

**Get access tokens**

    $twitter    = Twitter::make();
    $response   = $twitter->accessToken($tempTokens);

**Store the tokens**

    $_SESSION['oauth_token']        = $response['oauth_token'];
    $_SESSION['oauth_token_secret'] = $response['oauth_token_secret'];
    $_SESSION['user_id']            = $response['user_id'];
    $_SESSION['screen_name']        = $response['screen_name'];

D
