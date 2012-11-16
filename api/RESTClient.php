<?php
/*
Copyright 2002-2012 MarkLogic Corporation.  All Rights Reserved.

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

     http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
*/

require_once 'RESTResponse.php';

/**
 * Represents a REST client.
 *
 * @package mlphp\rest
 * @author Mike Wooldridge <mike.wooldridge@marklogic.com>
 */
class RESTClient
{
    private $host; // @var string
    private $port; // @var int
    private $version; // @var string
    private $username; // @var string
    private $password; // @var string
    private $auth; // @var string
    private $prefix; // @var string
    private $request; // @var RESTRequest

    /*
     * Create a REST client object and configure REST server and authentication information.
     *
     * @param string $host The host (examples: 'localhost', 'test.marklogic.com', '192.162.5.1').
     * @param int $port The port (example: 8003). Set to 0 for no port.
     * @param string $path An additional path prefix.
     * @param string $version The API version (example: 'v1').
     * @param string $username The username for REST authentication.
     * @param string $password The password for REST authentication.
     * @param string $auth The authentication scheme (examples: 'basic', 'digest').
     */
    public function __construct($host = '', $port = 0, $path = '', $version = '', $username = '', $password = '', $auth = '')
    {
        $this->host = (string)$host;
        $this->port = (int)$port;
        $this->path = (string)$path;
        $this->version = (string)$version;
        $this->username = (string)$username;
        $this->password = (string)$password;
        switch ($auth) {
            case 'basic':
                $this->auth = CURLAUTH_BASIC;
                break;
            case 'digest':
                $this->auth = CURLAUTH_DIGEST;
                break;
            default:
                   $this->auth = CURLAUTH_ANY;
        }
        $this->prefix = '';
        // Construct: http://<host>:<port>/<path>/<version>/
        if (!empty($this->host)) {
            $this->prefix = 'http://' . $this->host;
            $this->prefix .= (!empty($this->port)) ? ':' . $this->port : '';
            $this->prefix .= (!empty($this->path)) ? '/' . $this->path : '';
            $this->prefix .= (!empty($this->version)) ? '/' . $this->version : '';
            $this->prefix .= '/';
        }
    }

    /**
     * Set the host.
     *
     * @param string $host The host (examples: 'localhost', 'test.marklogic.com', '192.162.5.1').
     */
    public function setHost($host)
    {
        $this->host = $host;
    }

    /**
     * Set the port.
     *
     * @param string $port The port (example: 8003).
     */
    public function setPort($port)
    {
        $this->port = $port;
    }

    /**
     * Set the path.
     *
     * @param string $path The path (example: 'api').
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * Set the version.
     *
     * @param string $version The API version (example: 'v1').
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }

    /**
     * Set the username.
     *
     * @param string $username The username for REST authentication.
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * Set the password.
     *
     * @param string $password The password for REST authentication.
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Set the authentication scheme.
     *
     * @param string $auth The authentication scheme (examples: 'basic', 'digest').
     */
    public function setAuth($auth)
    {
        $this->auth = $auth;
    }

    /**
     * Set the URL prefix. Allows you to create a custom URL when no arguments are passed at construction.
     *
     * @param string $prefix The URL prefix.
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
    }

  /**
     * Send a REST request.
     *
     * @param RESTRequest $request A RESTRequest object
     * @result RESTResponse A RESTResponse object.
     */
    public function send($request)
    {

        $this->request = $request;

        switch (strtoupper($request->getVerb())) {
            case 'GET':
                $this->response = $this->get($request);
                break;
            case 'PUT':
                $this->response = $this->put($request);
                break;
            case 'DELETE':
                $this->response = $this->delete($request);
                break;
            case 'POST':
                $this->response = $this->post($request);
                break;
            case 'HEAD':
                $this->response = $this->head($request);
                break;
            default:
                throw new Exception($verb . ' is an invalid or unsupported REST verb.');
        }
    return $this->response;
    }

    /**
     * Set cURL options common to all requests
     *
     * @param resource $ch The cURL handle.
     * @param string $url The REST URL string (example: 'documents').
     * @result array An array of cURL options.
     */
    protected function setOptions(&$ch, $urlStr, $headers)
    {
        $url = $this->prefix . $urlStr; // Build full URL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPAUTH, $this->auth);
        curl_setopt($ch, CURLOPT_USERPWD, $this->username . ':' . $this->password);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_URL, $url);
        foreach ($headers as $key => $val) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, array($key . ': ' . $val));
        }
    }

  /**
     * Perform a GET request with cURL
     *
     * @param RESTRequest $request A REST request.
     * @result RESTResponse A REST response.
     */
    public function get($request)
    {

        $ch = curl_init();

        $this->setOptions($ch, $request->getUrlStr(), $request->getHeaders());

        // Options specific to GET
        curl_setopt($ch, CURLOPT_HTTPGET, true);

        return $this->execute($ch);
    }

    /**
     * Perform a PUT request with cURL
     *
     * @param RESTRequest $request A REST request.
     * @result RESTResponse A REST response.
     */
    public function put($request)
    {

        $ch = curl_init();

        // Handle PUT body
        $requestLength = strlen($request->getBody());
        $fh = fopen('php://memory', 'rw');
        fwrite($fh, $request->getBody());
        rewind($fh);

        $this->setOptions($ch, $request->getUrlStr(), $request->getHeaders());

        // Options specific to PUT
        curl_setopt($ch, CURLOPT_INFILE, $fh);
        curl_setopt($ch, CURLOPT_INFILESIZE, $requestLength);
        curl_setopt($ch, CURLOPT_PUT, true);

        return $this->execute($ch);
    }

    /**
     * Perform a DELETE request with cURL
     *
     * @param RESTRequest $request A REST request.
     * @result RESTResponse A REST response.
     */
    public function delete($request)
    {

        $ch = curl_init();

        $this->setOptions($ch, $request->getUrlStr(), $request->getHeaders());

        // Options specific to DELETE
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');

        return $this->execute($ch);
    }

    /**
     * Perform a POST request with cURL
     *
     * @param RESTRequest $request A REST request.
     * @result RESTResponse A REST response.
     */
    public function post($request)
    {
        $ch = curl_init();

      $requestBody = http_build_query($requestBody, '', '&');

        $this->setOptions($ch, $request->getUrlStr(), $request->getHeaders());

        // Options specific to POST
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $requestBody);

        return $this->execute($ch);
    }

    /**
     * Perform a HEAD request with cURL
     *
     * @param RESTRequest $request A REST request.
     * @result RESTResponse A REST response.
     */
    public function head($request)
    {
        $ch = curl_init();

        $this->setOptions($ch, $request->getUrlStr(), $request->getHeaders());

        // Options specific to POST
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'HEAD');

        return $this->execute($ch);
    }

    /**
     * Execute a cURL request.
     *
     * @todo Handle more response codes
     *
     * @param resource $ch The REST URL string (example: 'documents')
     * @result bool true if successful, false otherwise.
     */
    public function execute(&$ch)
    {
        $response = new RESTResponse();
        $response->setBody(curl_exec($ch));
        $response->setInfo(curl_getinfo($ch));
        //print_r($response);
        if ($response->getHttpCode() == 301) {
            // Redirect to specified URL
            curl_setopt($ch, CURLOPT_URL, $response->getRedirectUrl());
            return $this->execute($ch);
        } else if ($response->getHttpCode() >= 400) {
            throw new Exception($response->getErrorMessage(), $response->getHttpCode());
        } else {
            curl_close ($ch);
            return $response;
        }
    }
}