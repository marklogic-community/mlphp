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
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

use DOMDocument;

/**
 * @package MLPHP\Test
 * @author Mike Wooldridge <mike.wooldridge@marklogic.com>
 */
class OptionsTest extends TestBaseDB
{

    protected $options;

    function setUp()
    {
        parent::setUp();
        $this->options = new MLPHP\Options(parent::$client);
    }

    function testWrite()
    {
        // set the debug flag so we have some content
        parent::$logger->debug('debug');
        $this->options->setDebug(true);
        $this->assertEquals($this->options->getDebug(), true);

        // write
        parent::$logger->debug('write');
        $this->options->write('test');
        $response = $this->options->getResponse();
        $this->assertEquals(201, $response->getHttpCode());
    }

    function testRead()
    {
        // read
        parent::$logger->debug('read');
        $resultAsXML = $this->options->read('test');
        $doc = new DOMDocument();
        $doc->loadXML($resultAsXML);
        $this->assertEquals(
          $doc->getElementsByTagName('debug')->item(0)->nodeValue, true
        );
    }

    function testDelete()
    {
        // delete
        parent::$logger->debug('delete');
        $result = $this->options->delete('test');
        $response = $this->options->getResponse();
        $this->assertEquals(204, $response->getHttpCode());
    }

}

