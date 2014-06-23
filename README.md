Twitter Integrator
==================

twitter-integrator is a PHP client library to use Twitter Rest API and Oauth system.

Basic Usage
------------------

You can make a basic request without access tokens. It means, the request is made by application itself.

    $url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';

    $params = array(
        'screen_name'   => 'php_net',
        'count'         => 100
    );

    $response = Twitter::make()->get($url, $params);

Login with Twitter Workflow 
------------------

1. Generate temporary tokens from Twitter.
2. Generate autorize URL.
3. Redirect user to the Twitter for Authorization.

asd

    $twitter    = Twitter::make();
    $response   = $twitter->requestToken();
    $authorize  = $twitter->authorizeUrl($response['oauth_token']);
    
    header("Location: $authorize"); exit;


4. Get the Twitter response from Authorization.


    $tempTokens = array(
        'oauth_token'       => $_GET['oauth_token'],
        'oauth_verifier'    => $_GET['oauth_verifier']
    );


5. Generate real access tokens from Twitter.


    $twitter    = Twitter::make();
    $response   = $twitter->accessToken($tempTokens);


6. Store the tokens.


    $_SESSION['oauth_token']        = $response['oauth_token'];
    $_SESSION['oauth_token_secret'] = $response['oauth_token_secret'];
    $_SESSION['user_id']            = $response['user_id'];
    $_SESSION['screen_name']        = $response['screen_name'];


7. Make a request.


    $url = 'https://api.twitter.com/1.1/users/lookup.json';
    
    $params = array(
        'screen_name' => 'goddamnclever',
    );
    
    $config = array(
        'oauth_token'           => $_SESSION['oauth_token'],
        'oauth_token_secret'    => $_SESSION['oauth_token_secret'],
    );
    
    $response = Twitter::make()->get($url, $params, $config);

