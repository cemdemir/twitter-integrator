<?php

class Twitter
{
    // API Keys
    const CONSUMER_KEY          = 'nfYXJKlIm29DDDu0fW7jr7bZu';
    const CONSUMER_SECRET       = '7e2NTHr5f56IPvCJFcnHQjyax2lH1BCgr934vEhSGSLPuUeMqJ';
    const CALLBACK_URL          = 'http://31.210.54.102/response.php';

    // Token URLs
    const REQUEST_TOKEN_URL = 'https://api.twitter.com/oauth/request_token';
    const AUTHORIZE_URL     = 'https://api.twitter.com/oauth/authorize';
    const ACCESS_TOKEN_URL  = 'https://api.twitter.com/oauth/access_token';

    private static $instance;
    private $base;
    private $key;
    private $header;
    private $oauth;

    private $requestUrl;
    private $requestConfig;
    private $requestParams;
    private $requestType;
    
    /*
     * Constructer
     */

    private function __construct() {}

    /*
     * Return an instance with singleton design pattern
     */

    public static function make()
    {
        if (!self::$instance)
        {
            self::$instance = new static();
        }

        return self::$instance;
    }

    /*
     * Build the oauth array
     */

    private function buildOauth()
    {
        $this->oauth = array(
            'oauth_consumer_key'        => self::CONSUMER_KEY,
            'oauth_nonce'               => time(),
            'oauth_signature_method'    => 'HMAC-SHA1',
            'oauth_timestamp'           => time(),
            'oauth_version'             => '1.0'
        );

        if ($this->requestParams)
        {
            $this->oauth += $this->requestParams; 
        }

        if ($this->requestConfig['oauth_token'])
        {
            $this->oauth += array('oauth_token' => $this->requestConfig['oauth_token']);
        }

        ksort($this->oauth);
    }

    /*
     * Build the base string
     */

    private function buildBaseString()
    {
        $this->base = $this->requestType . '&'
            . rawurlencode($this->requestUrl) . '&' 
            . rawurlencode(http_build_query($this->oauth));
    }

    /*
     * Build the key
     */

    private function buildKey()
    {
        $this->key  = rawurlencode(self::CONSUMER_SECRET) . '&';
        $this->key .= rawurlencode($this->requestConfig['oauth_token_secret']);
    }

    /*
     * Build the signature
     */

    private function buildSignature()
    {
        $this->oauth['oauth_signature'] = base64_encode(hash_hmac('sha1', $this->base, $this->key, true));
        ksort($this->oauth);
    }

    /*
     * Build request header
     */

    private function buildHeader()
    {
        $oauthHeader = null;

        foreach($this->oauth as $key => $value)
        {
            $oauthHeader .= $key . '="' . rawurlencode($value) . '", ';
        }

        $this->header = array("Authorization: OAuth " . substr($oauthHeader, 0, -2), 'Expect:');
    }

    /*
     * Build request url
     */

    private function buildUrl()
    {
        if (isset($this->requestParams))
        {
            ksort($this->requestParams);
            $this->requestUrl .= '?' . http_build_query($this->requestParams);
        }
    }

    /*
     * Request to the Twitter API
     */

    private function curl()
    {
        $request = curl_init();

        if ($this->requestType == 'GET')
        {
            $this->buildUrl();
        }

        curl_setopt($request, CURLOPT_HTTPHEADER,       $this->header);
        curl_setopt($request, CURLOPT_HEADER,           false);
        curl_setopt($request, CURLOPT_URL,              $this->requestUrl);
        curl_setopt($request, CURLOPT_RETURNTRANSFER,   true);
        curl_setopt($request, CURLOPT_SSL_VERIFYPEER,   false);

        if ($this->requestType == 'POST')
        {
            curl_setopt($request, CURLOPT_POST,         count($this->requestParams));
            curl_setopt($request, CURLOPT_POSTFIELDS,   $this->requestParams);
        }
        
        $response = curl_exec($request);
        curl_close($request);

        return $response;
    }

    // -----------------------------------------------------------------------------
    // -----------------------------------------------------------------------------
    // -----------------------------------------------------------------------------

    /*
     * Request for temp tokens
     */

    public function requestToken()
    {
        $url    = self::REQUEST_TOKEN_URL;
        $config = null;
        $params = array('oauth_callback' => self::CALLBACK_URL);
        $type   = 'POST';

        $response = $this->get($url, $params, $config, $type);

        parse_str($response, $parsedString);
        return $parsedString;
    }

    /*
     * Return autorization URL
     */

    public function authorizeUrl($oauthToken)
    {
        return self::AUTHORIZE_URL . '?oauth_token=' . $oauthToken;
    }

    /*
     * Request for real access tokens
     */

    public function accessToken($input)
    {
        $url    = self::ACCESS_TOKEN_URL;
        $config = null;
        $params = $input;
        $type   = 'POST';

        $response = $this->get($url, $params, $config, $type);

        parse_str($response, $parsedString);
        return $parsedString;
    }

    // -----------------------------------------------------------------------------
    // -----------------------------------------------------------------------------
    // -----------------------------------------------------------------------------

    /*
     * Set given parameteres to the class properties
     * Build header
     * Call curl
     * Every request passes from here
     */

    public function get($url = null, $params = null, $config = null, $type = 'GET')
    {
        $this->requestUrl       = $url;
        $this->requestConfig    = $config;
        $this->requestParams    = $params;
        $this->requestType      = $type;

        $this->buildOauth();
        $this->buildBaseString();
        $this->buildKey();
        $this->buildSignature();
        $this->buildHeader();
        
        $response = $this->curl();

        return $response;
    }

}