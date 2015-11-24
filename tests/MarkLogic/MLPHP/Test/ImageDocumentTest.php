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
class ImageDocumentTest extends TestBaseDB
{

    function testSetContentFile()
    {
        parent::$logger->debug('testSetContentFile');
        if (function_exists('exif_read_data')) {
            $imageDoc = new MLPHP\ImageDocument(parent::$client);
            $imageDoc->setContentFile(__DIR__ . DIRECTORY_SEPARATOR . 'example2.jpg');
            $exif = $imageDoc->getExif();
            $this->assertNotFalse($exif);
        } else {
            parent::$logger->debug('exif_read_data function not available');
        }
    }

    function testWrite()
    {
        parent::$logger->debug('testWrite');
        $imageDoc = new MLPHP\ImageDocument(parent::$client);
        $imageDoc->setContentFile(__DIR__ . DIRECTORY_SEPARATOR . 'example.jpg');
        $uri = '/example.jpg';
        $imageDoc->write($uri);
        $response = $imageDoc->getResponse();
        $this->assertEquals(201, $response->getHttpCode());
        return $imageDoc;
    }

    /**
     * @depends testWrite
     */
    function testRead($imageDoc)
    {
        parent::$logger->debug('testRead');
        $imageDoc->read($imageDoc->getURI());
        $this->assertEquals($imageDoc->getContentType(), 'image/jpeg');
    }

}

