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
 * @author Eric Bloch <eric.bloch@gmail.com>
 */
class DocumentTest extends TestBaseDB
{

    function testWrite()
    {
        $uri = '/text.txt';
        $content = 'Text content';
        $doc = new MLPHP\Document(parent::$client);
        $doc->setContent($content)->setContentType('text/text');
        $doc->write($uri);
        $response = $doc->getResponse();
        $this->assertEquals(201, $response->getHttpCode());
        return $doc;
    }

    /**
     * @depends testWrite
     */
    function testRead($doc)
    {
        $result = $doc->read($doc->getURI());
        $this->assertEquals($result, $doc->getContent());
    }

    /**
     * @depends testWrite
     */
    function testWriteMetadata($doc)
    {
        $meta = new MLPHP\Metadata(parent::$client);
        $meta->setQuality(1);
        $doc->writeMetadata($meta);
        $response = $doc->getResponse();
        $this->assertEquals(204, $response->getHttpCode());
        return $doc;
    }

    /**
     * @depends testWriteMetadata
     */
    function testReadMetadata($doc)
    {
        $meta = $doc->readMetadata();
        $this->assertEquals($meta->getQuality(), 1);
    }

    /**
     * @depends testWriteMetadata
     */
    function testDeleteMetadata($doc)
    {
        $doc->deleteMetadata();
        $meta = $doc->readMetadata();
        $this->assertEquals($meta->getQuality(), 0);
    }

    /**
     * @depends testWrite
     */
    function testSetContentFile($doc)
    {
        $doc->setContentFile(__DIR__ . DIRECTORY_SEPARATOR . 'example.json');
        $obj = json_decode($doc->getContent());
        // check for known JSON property
        $this->assertEquals($obj->planet, 'Earth');
    }

    /**
     * @depends testWrite
     */
    function testDelete($doc)
    {
        $doc->delete();
        // non-existent file returns false
        $this->assertEquals($doc->read(), false);
    }

}

