twitter-integrator
------------------

twitter-integrator is a client library to use Twitter Rest API.

Basic Usage
==================

URL
    
    $url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';

Params
    
    
    $params = array(
        'screen_name'   => 'php_net',
        'count'         => 100
    );
    
    

Request
    
    $response = Twitter::make()->get($url, $params);
