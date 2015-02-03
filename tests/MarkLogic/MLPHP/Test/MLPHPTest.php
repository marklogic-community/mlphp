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
class MLPHPTest extends TestBaseDB
{

    function testConstructMerge()
    {
        parent::$logger->debug('testMLPHP');
        $name = 'mlphp-rest-api';
        $mlphp = new MLPHP\MLPHP(array(
            'api' => $name
        ));
        $this->assertEquals($mlphp->config['api'], $name);
        $this->assertEquals($mlphp->config['managePort'], 8002);
        $mlphp->mergeConfig(array(
            'api' => $name . '-new'
        ));
        $this->assertEquals($mlphp->config['api'], $name . '-new');
        return $mlphp;
    }

    /**
     * @depends testConstructMerge
     */
    function testNewAPI($mlphp)
    {
        $api = $mlphp->newAPI();
        $this->assertTrue($api->exists());
        $this->assertInstanceOf('MarkLogic\MLPHP\RESTAPI', $api);
        $api->delete();
    }

    /**
     * @depends testConstructMerge
     */
    function testNewClient($mlphp)
    {
        $client = $mlphp->newClient();
        $req = new MLPHP\RESTRequest('GET', 'config/properties');
        $this->assertInstanceOf('MarkLogic\MLPHP\RESTClient', $client);
    }

    /**
     * @depends testConstructMerge
     */
    function testNewManageClient($mlphp)
    {
        $manageClient = $mlphp->newManageClient();
        $this->assertInstanceOf('MarkLogic\MLPHP\RESTClient', $manageClient);
    }

}

