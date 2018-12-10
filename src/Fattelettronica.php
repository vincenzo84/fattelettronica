<?php

namespace Syriaweb\Fattelettronica;

use Config;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\RequestException;

class Fattelettronica
{
    public static $api_endpoint_demo = 'https://demows.fatturazioneelettronica.aruba.it/';
    public static $api_endpoint_live = 'https://ws.fatturazioneelettronica.aruba.it/';
    public static $api_endpoint_credential_demo = 'https://demoauth.fatturazioneelettronica.aruba.it/';
    public static $api_endpoint_credential_live = 'https://auth.fatturazioneelettronica.aruba.it/';
    public $token;

    public function __construct() {

        $demo = Config::get('fattelettronica.options.DEMO');
        if($demo) {
            $endpoint = self::$api_endpoint_credential_demo;
        }else{
            $endpoint = self::$api_endpoint_credential_live;
        }

        $this->username = Config::get('fattelettronica.options.USERNAME');
        $this->password = Config::get('fattelettronica.options.PASSWORD');

        $this->http_client = new HttpClient( [
            'base_uri' => $endpoint,
            'timeout'  => 5,
            'curl' => array( CURLOPT_SSL_VERIFYPEER => false, ),
        ]);

        $request =  array(
            'grant_type'  =>  'password',
        );

        $final_request = array(
            'form_params' => $request
        );

        $final_request['form_params']['username'] = $this->username;
        $final_request['form_params']['password'] = $this->password;

        try {

            $http_response = $this->http_client->request('POST', 'auth/signin', $final_request);
            $response = json_decode($http_response->getBody(), false, 512, JSON_BIGINT_AS_STRING);
            $this->token = $response->access_token;

        } catch ( RequestException $e ) {
            $this->token = false;
            return false;
        }
    }

    function call($uri, $data, $method = 'POST' ) {

        $demo = Config::get('fattelettronica.options.DEMO');
        if($demo) {
            $endpoint = self::$api_endpoint_demo;
        }else{
            $endpoint = self::$api_endpoint_live;
        }

        $http_client = new HttpClient( [
            'base_uri' => $endpoint,
            'timeout'  => 5,
            'curl' => array( CURLOPT_SSL_VERIFYPEER => false, ),
        ]);

        $headers = [
            'Authorization' => 'Bearer ' . $this->token,
            'Accept'        => 'application/json',
            'Content-Type'  =>  'application/json;charset=UTF-8',
        ];

        $final_request = array(
            'debug' =>  Config::get('fattelettronica.options.DEBUG'),
            'headers' => $headers,
            'json' => $data
        );

        try {
            $http_response = $http_client->request($method, $uri, $final_request);
        } catch ( RequestException $e ) {
            return false;
        }

        $response = json_decode($http_response->getBody(), false, 512, JSON_BIGINT_AS_STRING);

        if( null === $response ) {
            return false;
        }

        return $response;
    }
}
