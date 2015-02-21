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
 * @author Eric Bloch <eric.bloch@gmail.com>
 * @author Mike Wooldridge <mike.wooldridge@marklogic.com>
 *
 * Database index setup and a simple test of REST server connectivity.
 * Filename prefixed with "1" so it is run first. This ensures the
 * database indexes will be accessible for later tests.
 */
class SmokeTest extends TestBaseSearch
{

    function setUp()
    {
        global $mlphp;
        if (substr($mlphp->config['mlversion'], 0, 3) >= 8) {
            parent::setIndexesXML(parent::$manageClient);
            parent::setIndexesJSON(parent::$manageClient);
        }
    }

    function testTimestamp()
    {
        global $mlphp;
        $client = new MLPHP\RESTClient(
          $mlphp->config['host'], 8001, 'admin', $mlphp->config['version'],
          $mlphp->config['username'], $mlphp->config['password'],
          $mlphp->config['auth'], $mlphp->config['logger']
        );
        $req = new MLPHP\RESTRequest('GET', 'timestamp');
        $resp = $client->send($req);
        // match a timestamp
        $this->assertStringMatchesFormat('%d-%s-%sT%d:%s:%s.%s-%s:%s', $resp->getBody());
    }
}

