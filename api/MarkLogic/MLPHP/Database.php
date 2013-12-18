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
 * Represents a Database
 *
 * @package MLPHP
 * @author Eric Bloch <eric.bloch@gmail.com>
 */
class Database
{
    private $restClient; // @var RESTclient

    /**
     * Create a Search object.
     *
     * @param RESTClient $restClient A REST client object.
     */
    public function __construct($restClient)
    {
        $this->restClient = $restClient;
    }

    /**
     *
     * Clear the database of all content
     *
     *    Installs and uses a resource extension
     */
    public function clear()
    {
        // Install the API extension
        $resource = "resources/clear-db";
        $this->restClient->installExtension("config/" . $resource, array(
            'method' => 'get'
        ), "clear-db.xqy");
        
        $request = new RESTRequest('GET', $resource, array(), "", array());

        try {
            $response = $this->restClient->send($request);
        } catch(Exception $e) {
            echo $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine() . PHP_EOL;
        }
    }

    /**
     *
     * Gets the database name
     *
     *    Installs and uses a resource extension
     *
     * @return str database name
     */
    public function getName()
    {
        // Install the API extension
        $resource = "resources/get-db-name";
        $this->restClient->installExtension("config/" . $resource, array(
            'method' => 'get'
        ), "get-db-name.xqy");
        
        $request = new RESTRequest('GET', $resource, array(), "", array());

        try {
            $response = $this->restClient->send($request);
            return $response->getBody();
        } catch(Exception $e) {
            echo $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine() . PHP_EOL;
        }
    }
}

