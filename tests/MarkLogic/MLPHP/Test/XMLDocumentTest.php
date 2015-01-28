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
class XMLDocumentTest extends TestBaseDB
{

    function testWrite()
    {
        parent::$logger->debug('testWrite');
        $uri = '/text.xml';
        $content = '<foo baz="5">bar</foo>';
        $xmlDoc = new MLPHP\XMLDocument(parent::$client);
        $xmlDoc->setContent($content);
        $xmlDoc->write($uri);
        $response = $xmlDoc->getResponse();
        $this->assertEquals(201, $response->getHttpCode());
        return $xmlDoc;
    }

    /**
     * @depends testWrite
     */
    function testRead($xmlDoc)
    {
        parent::$logger->debug('testRead');
        $result = $xmlDoc->read($xmlDoc->getURI());
        $this->assertXmlStringEqualsXmlString($result, $xmlDoc->getContent());
    }

    /**
     * @expectedException Exception
     */
    function testWriteBadXML()
    {
        parent::$logger->debug('testWriteBadXML');
        $uri = '/not.xml';
        $content = '{"foo": "bar"}';
        $xmlDoc = new MLPHP\XMLDocument(parent::$client);
        $xmlDoc->setContent($content);
        $xmlDoc->write($uri);
    }

}

