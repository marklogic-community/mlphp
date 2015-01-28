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
class JSONDocumentTest extends TestBaseDB
{

    function testWrite()
    {
        parent::$logger->debug('testWrite');
        $uri = '/text.json';
        $content = '{"foo": "blah", "bar": true, "baz": 10}';
        $jsonDoc = new MLPHP\JSONDocument(parent::$client);
        $jsonDoc->setContent($content);
        $jsonDoc->write($uri);
        $response = $jsonDoc->getResponse();
        $this->assertEquals(201, $response->getHttpCode());
        return $jsonDoc;
    }

    /**
     * @depends testWrite
     */
    function testRead($jsonDoc)
    {
        parent::$logger->debug('testRead');
        $result = $jsonDoc->read($jsonDoc->getURI());
        $this->assertJsonStringEqualsJsonString($result, $jsonDoc->getContent());
    }

    /**
     * @expectedException Exception
     */
    function testWriteBadJSON()
    {
        parent::$logger->debug('testWriteBadJSON');
        $uri = '/not.json';
        $content = '<not-json>blah</not-json>';
        $jsonDoc = new MLPHP\JSONDocument(parent::$client);
        $jsonDoc->setContent($content);
        $jsonDoc->write($uri);
    }

}

