<?php

/**
 * Client library for the When I Work scheduling and attendance platform.
 * 
 * Uses curl to request JSON responses from the When I Work API.
 * This probably has more comments than code.
 *
 * Contributors:
 * Daniel Olfelt <daniel@thisclicks.com>
 * 
 * @author Daniel Olfelt <daniel@thisclicks.com> 
 * @version 0.1
 */
class Wheniwork {

    const METHOD_GET = 'get';
    const METHOD_POST = 'post';
    const METHOD_PUT = 'put';
    const METHOD_PATCH = 'patch';
    const METHOD_DELETE = 'delete';

    private $api_key;
    private $api_token;
    private $api_endpoint = 'https://api.wheniwork.com/2';
    private $verify_ssl   = false;

    /**
     * Create a new instance
     * @param string $api_token The user WhenIWork API token
     */
    function __construct($api_token = null)
    {
        $this->api_token = $api_token;
    }

    /**
     * Set the user token for all requests
     * @param string $api_token The user WhenIWork API token
     */
    public function setToken($api_token) {
        $this->api_token = $api_token;
    }

    /**
     * Get the user token to save for future requests
     * @return string The user WhenIWork API token
     */
    public function getToken() {
        return $this->api_token;
    }

    /**
     * Get an object or list.
     * @param  string $method  The API method to call, e.g. '/users/'
     * @param  array  $params  An array of arguments to pass to the method.
     * @param  array  $headers Array of custom headers to be passed
     * @return array           Object of json decoded API response.
     */
    public function get($method, $params = array(), $headers = array()) {
        return $this->makeRequest($method, self::METHOD_GET, $params, $headers);
    }

    /**
     * Create an object.
     * @param  string $method  The API method to call, e.g. '/users/'
     * @param  array  $params  An array of data used to create the object.
     * @param  array  $headers Array of custom headers to be passed
     * @return array           Object of json decoded API response.
     */
    public function create($method, $params = array(), $headers = array()) {
        return $this->makeRequest($method, self::METHOD_POST, $params, $headers);
    }

    /**
     * Update an object. Must include the ID.
     * @param  string $method  The API method to call, e.g. '/users/1'
     * @param  array  $params  An array of data to update the object. Only changed fields needed.
     * @param  array  $headers Array of custom headers to be passed
     * @return array           Object of json decoded API response.
     */
    public function update($method, $params = array(), $headers = array()) {
        return $this->makeRequest($method, self::METHOD_PUT, $params, $headers);
    }

    /**
     * Delete an object. Must include the ID.
     * @param  string $method  The API method to call, e.g. '/users/1'
     * @param  array  $params  An array of arguments to pass to the method.
     * @param  array  $headers Array of custom headers to be passed
     * @return array           Object of json decoded API response.
     */
    public function delete($method, $params = array(), $headers = array()) {
        return $this->makeRequest($method, self::METHOD_DELETE, $params, $headers);
    }

    
    /**
     * Performs the underlying HTTP request. Exciting stuff happening here. Not really.
     * @param  string $method  The API method to be called
     * @param  string $request The type of request
     * @param  array  $params  Assoc array of parameters to be passed
     * @param  array  $headers Assoc array of custom headers to be passed
     * @return array           Assoc array of decoded result
     */
    private function makeRequest($method, $request, $params = array(), $headers = array()) {

        $url = $this->api_endpoint.'/'.$method;

        if ($params && ($request == self::METHOD_GET || $request == self::METHOD_DELETE)) {
            $url .= '?'.http_build_query($params);
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, 'WhenIWork-PHP/0.1');

        $headers['Content-Type'] = 'application/json';
        if ($this->api_token) {
            $headers['W-Token'] = $this->api_token;
        }

        $header_data = array();
        foreach ($headers as $k=>$v) {
            $headers_data[] = $k.': '.$v;
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers_data);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($request));
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->verify_ssl);

        if (in_array($request, array(self::METHOD_POST, self::METHOD_PUT, self::METHOD_PATCH))) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        }

        $result = curl_exec($ch);
        curl_close($ch);

        return $result ? json_decode($result) : false;
    }



    /**
     * Login helper using developer key and credentials to get back a login response
     * @param  $key      Developer API key 
     * @param  $email    Email of the user logging in
     * @param  $password Password of the user
     * @return    
     */
    public static function login($key, $email, $password) {
        $params = array(
            "username" => $email,
            "password" => $password
        );
        $headers = array(
            'W-Key' => $key
        );

        $login = new self();
        $response = $login->makeRequest("login", self::METHOD_POST, $params, $headers);

        return $response;
    }

}
