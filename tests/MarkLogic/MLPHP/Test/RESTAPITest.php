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
class RESTAPITest extends TestBase
{

    function testCreate()
    {
        parent::$logger->debug('testCreate');
        global $mlphp;
        $api = new MLPHP\RESTAPI(
            $mlphp->config['api'] . '-1',
            $mlphp->config['host'],
            $mlphp->config['db'] . '-1',
            $mlphp->config['port'] + 1,
            $mlphp->config['username'],
            $mlphp->config['password'],
            parent::$logger
        );
        $api->create();
        $this->assertTrue($api->exists());
        return $api;
    }

    /**
     * @depends testCreate
     */
    function testSetProperty($api)
    {
        parent::$logger->debug('testSetProperty');
        $api->setProperty('debug', 'true');
        $response = $api->getResponse();
        $this->assertEquals(204, $response->getHttpCode());
    }

    /**
     * @depends testCreate
     */
    function testGetProperty($api)
    {
        parent::$logger->debug('testGetProperty');
        $property = $api->getProperty('debug');
        $this->assertTrue($property);
    }

    /**
     * @depends testCreate
     */
    function testDelete($api)
    {
        parent::$logger->debug('testDelete');
        $api->delete();
        $this->assertFalse($api->exists());
    }

}

