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

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

/**
 * @package MLPHP
 * @author Eric Bloch <eric.bloch@gmail.com>
 */
class DocumentTest extends \PHPUnit_Framework_TestCase
{
    private $apiclient;
    private $client;

    function createAPI() 
    {
        $method = 'post';
        $resource = "rest-apis";
        $params = array();
        $headers = array(
            'Content-type' => 'application/json'
        );
        $body = '
            {
                "rest-api": {
                    "name": "test-mlphp-rest-api",
                    "database": "mlphp-test",
                    "modules-database": "mlphp-test-modules",
                    "port": "8234"
                }
            }
        ';
        $request = new RESTRequest($method, $resource, $params, $body, $headers);

        $this->apiclient->post($request);

        $this->client = new RESTClient('localhost', '8234', '', 'v1', 'admin', 'adm1n', 'digest', $logger);

        installExtensions();
    }

    function installExtensions() 
    {
        $method = 'put';
        $resource = "config/resources/clear-db";
        $params = array(
            'method' => 'get'
        );
        $headers = array(
            'Content-type' => 'application/xquery'
        );
        $body = readfile(__DIR__ . "clear-db.xqy");
        $request = new RESTRequest($method, $resource, $params, $body, $headers);
        $this->apiclient->put($request);
    }

    function clearDB() 
    {
        $method = 'get';
        $resource = "resources/clear-db";
        $params = array();
        $headers = array();
        $body = null;

        $request = new RESTRequest($method, $resource, $params, $body, $headers);
        $this->client->get($request);

    }

    function deleteAPI()
    {
        $this->apiclient->getLogger()->debug("delete API");
        $method = 'delete';
        $resource = "rest-apis/test-mlphp-rest-api";
        $params = array();
        $body = null;
        $headers = array();
        $request = new RESTRequest($method, $resource, $params, $body, $headers);

        $this->apiclient->delete($request);
    }

    function setup()
    {
        $logger = new Logger('test');
        // $logger->pushHandler(new StreamHandler('test.log', Logger::DEBUG));
        $logger->pushHandler(new StreamHandler('php://stderr', Logger::DEBUG));

        $logger->debug("setup");

        $this->apiclient = new RESTClient('localhost', '8002', '', 'v1', 'admin', 'adm1n', 'digest', $logger);

        /* Create a fresh REST API instance for us */
        $this->createAPI();

        /* Clear the attached DB */
        $this->clearDB();
        
    }

    function testWrite()
    {
        $doc = new Document($client);
        $doc->setContent('<hello/>');
        $doc->write("/hello.xml");
        /* Read it back */
    }

    function tearDown()
    {
        $this->apiclient->getLogger()->debug("tearDown");
        $this->deleteAPI();
    }
}

