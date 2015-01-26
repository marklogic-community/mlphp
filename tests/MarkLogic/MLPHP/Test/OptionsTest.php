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

/**
 * @package MLPHP\Test
 * @author Mike Wooldridge <mike.wooldridge@marklogic.com>
 */
class OptionsTest extends TestBase
{

    function setUp()
    {
        parent::setUp();
    }

    function testWrite()
    {
        $options = new MLPHP\Options(parent::$client);

        // set the debug flag
        parent::$logger->debug('debug');
        $options->setDebug(true);
        $this->assertEquals($options->getDebug(), true);

        // write
        parent::$logger->debug('write');
        $options->write('test');
        $response = $options->getResponse();
        $this->assertEquals(201, $response->getHttpCode());

        return $options;

    }

    /**
     * @depends testWrite
     */
    function testRead($options)
    {

        // read
        parent::$logger->debug('read');
        $result = $options->read('test');
        print($result);
        $this->assertNotNull($result);

    }

    /**
     * @depends testWrite
     */
    function testOptions($options)
    {
        // delete
        parent::$logger->debug('delete');
        $result = $options->delete('test');
        $response = $options->getResponse();
        $this->assertEquals(204, $response->getHttpCode());
    }

}

