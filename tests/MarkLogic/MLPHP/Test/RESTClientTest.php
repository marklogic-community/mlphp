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
namespace MarkLogic\MLPHP\Test;

use MarkLogic\MLPHP;

/**
 * @package MLPHP\Test
 * @author Mike Wooldridge <mike.wooldridge@marklogic.com>
 */
class RESTClientTest extends TestBaseDB
{

    // Test using the /v1/config/namespaces endpoint since it is simple,
    // untested, and handles GET, POST, PUT, DELETE

    function testRESTClient()
    {
        parent::$logger->debug('testRESTClient');

        $json = '{
            "namespace-bindings": [
              {
                "prefix": "will",
                "uri": "http://marklogic.com/examples/shakespeare"
              }
            ]
        }';

        $xml = '<namespace-bindings xmlns="http://marklogic.com/rest-api">
                  <namespace>
                    <prefix>bill</prefix>
                    <uri>http://marklogic.com/examples/shakespeare</uri>
                  </namespace>
                </namespace-bindings>';

        // PUT namespace as JSON
        $params = array('format' => 'json');
        $req = new MLPHP\RESTRequest('PUT', 'config/namespaces', $params, $json);
        $resp = self::$client->send($req);

        // GET namespaces as JSON
        $params = array('format' => 'json');
        $req = new MLPHP\RESTRequest('GET', 'config/namespaces', $params);
        $resp = self::$client->send($req);

        $this->assertEquals('json', $resp->getBodyType());
        $resp_parsed = json_decode($resp->getBody());
        $prefix = $resp_parsed->{'namespace-bindings'}[0]->prefix;
        $this->assertEquals('will', $prefix);

        // GET namespaces as XML via param
        $params = array('format' => 'xml');
        $req = new MLPHP\RESTRequest('GET', 'config/namespaces', $params);
        $resp = self::$client->send($req);

        $this->assertEquals('xml', $resp->getBodyType());
        $dom = new \DOMDocument();
        $dom->loadXML($resp->getBody());
        $prefix = $dom->getElementsByTagName('prefix')->item(0)->nodeValue;
        $this->assertEquals('will', $prefix);

        // GET namespaces as JSON via header
        // Default Accept is XML if setting by header fails, so test with JSON
        $params = array();
        $headers = array('Accept' => 'application/json', 'Foo' => 'bar');
        $req = new MLPHP\RESTRequest('GET', 'config/namespaces', $params, '' , $headers);
        $resp = self::$client->send($req);
        $this->assertEquals('json', $resp->getBodyType());

        // POST additional namespace as XML
        $headers = array('Content-type' => 'application/xml');
        $req = new MLPHP\RESTRequest('POST', 'config/namespaces', $params, $xml, $headers);
        $resp = self::$client->send($req);

        // GET namespaces as XML
        $params = array('format' => 'xml');
        $req = new MLPHP\RESTRequest('GET', 'config/namespaces', $params);
        $resp = self::$client->send($req);

        $dom = new \DOMDocument();
        $dom->loadXML($resp->getBody());
        $elems = $dom->getElementsByTagName('prefix');

        $this->assertEquals(2, $elems->length);

        // DELETE namespaces
        $req = new MLPHP\RESTRequest('DELETE', 'config/namespaces');
        $resp = self::$client->send($req);

        $this->assertEquals('204', $resp->getHttpCode());

        // HEAD via the Management API
        $manageClient = new MLPHP\RESTClient(
            '127.0.0.1',
            '8001',
            'admin',
            'v1',
            'admin',
            'admin',
            'digest',
            parent::$logger
        );
        $req = new MLPHP\RESTRequest('HEAD', 'timestamp');
        $resp = $manageClient->send($req);

        $this->assertEquals('200', $resp->getHttpCode());
    }

}

