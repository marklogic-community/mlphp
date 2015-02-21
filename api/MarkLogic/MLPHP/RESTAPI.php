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
namespace MarkLogic\MLPHP;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Represents a REST API.
 *
 * @package MLPHP
 * @author Mike Wooldridge <mike.wooldridge@marklogic.com>
 */
class RESTAPI
{
    private $name; // @var string
    private $host; // @var string
    private $db; // @var string
    private $port; // @var int
    private $username; // @var string
    private $password; // @var string
    private $logger; // @var LoggerInterface
    private $client; // @var RESTClient
    private $apiClient; // @var RESTClient
    private $response; // @var RESTResponse

    /**
     * Create a REST API object.
     *
     * @param string name The name of the API (example: 'mlphp-rest-api').
     * @param string host The host (example: '127.0.0.1', 'localhost').
     * @param string db The name of the database (example: 'mlphp-db').
     * @param int port The port of the API.
     * @param string username The username for REST authentication.
     * @param string password The password for REST authentication.
     * @param LoggerInterface logger
     */
    public function __construct(
      $name, $host, $db, $port, $username, $password, $logger = null
    )
    {
        $this->name = $name ?: 'mlphp-rest-api';
        $this->host = $host ?: '127.0.0.1';
        $this->db = $db ?: 'mlphp-db';
        $this->port = $port ?: 8234;
        $this->username = $username ?: 'admin';
        $this->password = $password ?: 'admin';
        if (is_null($logger)) {
            $this->logger = new NullLogger();
        } else {
            $this->logger = $logger;
        }
        // For creating/deleting REST API
        $this->client = new RESTClient(
            $this->host,
            8002,
            '',
            'v1',
            $this->username,
            $this->password,
            'digest',
            $this->logger
        );
        // For getting/setting REST API properties
        $this->apiClient = new RESTClient(
            $this->host,
            $this->port,
            '',
            'v1',
            $this->username,
            $this->password,
            'digest',
            $this->logger
        );
    }

    /**
     * Create a REST API via a post to the MarkLogic rest-apis endpoint.
     * It will not create an API if there already exists one with the
     * same name.
     *
     * @param RESTClient client Optional custom REST client object.
     */
    public function create($client = null)
    {
        if ($this->exists()) {
            $this->logger->debug(
                'A REST API named "' . $this->name . '" already exists'
            );
            return $this;
        }
        $this->client = $client ?: $this->client;
        $params = array();
        $headers = array('Content-type' => 'application/json');
        $body = '
            {
                "rest-api": {
                    "name": "' . $this->name . '",
                    "database": "' . $this->db . '",
                    "modules-database": "' . $this->db . '-modules",
                    "port": "' . $this->port . '",
                    "forests-per-host": 1,
                    "error-format": "json"
                }
            }
        ';
        $request = new RESTRequest('POST', 'rest-apis', $params, $body, $headers);
        $this->logger->debug(
            "Setting up REST API: " . $this->name .
            ' (' . $this->db . ') port ' . $this->port
        );
        $this->client->send($request); // Set up REST API
        return $this;
    }

    /**
     * Set a REST API property.
     * Must execute create() before setting a property.
     *
     * @param string key The property key.
     * @param string json The property value.
     */
    public function setProperty($key, $value)
    {
        $arr = array($key => $value);
        $params = array('format' => 'json');
        $request = new RESTRequest(
          'PUT', 'config/properties/' . $key,
          $params, json_encode($arr)
        );
        try {
            $this->response = $this->apiClient->send($request);
            return $this;
        } catch(Exception $e) {
            echo $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine() . PHP_EOL;
        }
    }

    /**
     * Get a REST API property.
     * Must execute create() before getting a property.
     *
     * @param string key The property key.
     */
    public function getProperty($key)
    {
        $params = array('format' => 'json');
        $request = new RESTRequest('GET', 'config/properties/' . $key, $params);
        try {
            $this->response = $this->apiClient->send($request);
            $property = json_decode($this->response->getBody());
            return $property->{$key};
        } catch(Exception $e) {
            echo $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine() . PHP_EOL;
        }
    }

    /**
     * Delete a REST API.
     *
     * @param boolean module Optional custom REST client object.
     */
    public function delete($client = null)
    {
        $this->client = $client ?: $this->client;
        // Delete content and modules as well
        $params = array('include' => array('content', 'modules'));
        $body = null;
        $headers = array();
        $request = new RESTRequest(
            'DELETE', 'rest-apis/' . $this->name, $params, $body, $headers
        );

        $this->client->send($request);

        // Wait for server reboot
        $requestWait = new RESTRequest('GET', 'rest-apis');
        $this->waitUntilSuccess($requestWait, 3, 10);
    }

    /**
     * Sleep until a request is successful.
     *
     * @param RESTRequest $request The request to send.
     * @param int $time Time in secs to wait between requests.
     * @param int $limit Cycles to complete before timing out.
     */
    protected function waitUntilSuccess($request, $secs, $limit)
    {
        sleep($secs);
        $limit--;
        try {
            $response = $this->client->send($request);
        } catch(\Exception $e) {
            if ($limit > 0) {
                $this->waitUntilSuccess($request, $secs, $limit);
            } else {
                throw new Exception('waitUntilSuccess() timed out');
            }
        }
        return;
    }
    /**
     * Check if a REST API with the same name exists on the server.
     *
     * @param string name Name of the REST API to check.
     * @return boolean true|false
     */
    public function exists()
    {
        try {
            $params = array();
            $body = null;
            $headers = array();
            $request = new RESTRequest(
                'GET', 'rest-apis/' . $this->name, $params, $body, $headers
            );
            $response = $this->client->send($request);
        } catch(\Exception $e) {
            return false;
        }
        return true;
    }

    /**
     * Get the last REST response received. Useful for testing.
     *
     * @return RESTRresponse A REST response object.
     */
    public function getResponse()
    {
        return $this->response;
    }

}
