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
 *
 * Similar to DocumentTest but mocks the REST client to avoid database access
 * https://phpunit.de/manual/current/en/test-doubles.html
 */
class DocumentMockedTest extends TestBase
{

    public $mockClient;
    public $response;

    function setUp()
    {
        global $mlphp;
        $this->mockClient = $this->getMockBuilder('MarkLogic\MLPHP\RESTClient')
        ->setConstructorArgs(array(
            $mlphp->config['host'],
            $mlphp->config['port'],
            $mlphp->config['path'],
            $mlphp->config['version'],
            $mlphp->config['username'],
            $mlphp->config['password'],
            $mlphp->config['auth'],
            $mlphp->config['logger']
        ))
        ->getMock();
        $this->response = new MLPHP\RESTResponse(); // Used by mock client
    }

    function testWrite()
    {
        // Set up response for mock client
        $this->response->setInfo(array(
            'http_code' => 201
        ));
        $this->mockClient->expects($this->any())
             ->method('send')
             ->will($this->returnValue($this->response));

        $uri = '/text.txt';
        $content = 'Text content';
        $doc = new MLPHP\Document($this->mockClient);
        $doc->setContent($content)->setContentType('text/text');
        $doc->write($uri);
        $response = $doc->getResponse();
        $this->assertEquals(201, $response->getHttpCode());
    }

    function testRead()
    {
        // Set up response for mock client
        $this->response->setBody('Text content');
        $this->response->setInfo(array(
            'content_type' => 'text/text'
        ));
        $this->mockClient->expects($this->any())
             ->method('send')
             ->will($this->returnValue($this->response));

        $doc = new MLPHP\Document($this->mockClient);
        $result = $doc->read($doc->getURI());
        $this->assertEquals($result, $doc->getContent());
        $this->assertEquals('text/text', $doc->getContentType());
    }

    /**
     * @depends testWrite
     */
    // function testWriteMetadata($doc)
    // {
    //     // Set up response for mock client
    //     $this->response->setBody('');
    //     $this->response->setInfo(array(
    //         'http_code' => 201
    //     ));
    //     $this->mockClient->expects($this->any())
    //          ->method('send')
    //          ->will($this->returnValue($this->response));

    //     $meta = new MLPHP\Metadata(parent::$client);
    //     $meta->setQuality(1);
    //     $doc->writeMetadata($meta);
    //     $response = $doc->getResponse();
    //     $this->assertEquals(204, $response->getHttpCode());
    //     return $doc;
    // }

    // /**
    //  * @depends testWriteMetadata
    //  */
    // function testReadMetadata($doc)
    // {
    //     $meta = $doc->readMetadata();
    //     $this->assertEquals($meta->getQuality(), 1);
    // }

    // /**
    //  * @depends testWriteMetadata
    //  */
    // function testDeleteMetadata($doc)
    // {
    //     $doc->deleteMetadata();
    //     $meta = $doc->readMetadata();
    //     $this->assertEquals($meta->getQuality(), 0);
    // }

    // /**
    //  * @depends testWrite
    //  */
    // function testSetContentFile($doc)
    // {
    //     $doc->setContentFile(__DIR__ . DIRECTORY_SEPARATOR . 'example.json');
    //     $obj = json_decode($doc->getContent());
    //     // check for known JSON property
    //     $this->assertEquals($obj->planet, 'Earth');
    // }

    // /**
    //  * @depends testWrite
    //  */
    // function testDelete($doc)
    // {
    //     $doc->delete();
    //     // non-existent file returns false
    //     $this->assertEquals($doc->read(), false);
    // }

}

