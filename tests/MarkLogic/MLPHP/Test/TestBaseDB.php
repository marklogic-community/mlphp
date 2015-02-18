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
 * Extended by test classes that ACCESS THE DATABASE.
 */
abstract class TestBaseDB extends TestBase
{
    protected static $manageClient;

    // Runs before each test class
    // https://phpunit.de/manual/current/en/fixtures.html#fixtures.variations
    public static function setUpBeforeClass()
    {
        global $mlphp;

        parent::setUpBeforeClass();

        // Create a manage client for tests
        self::$manageClient = $mlphp->getManageClient();

        // Need ML8 or greater for database operations
        if (substr($mlphp->config['mlversion'], 0, 3) >= 8) {
            $db = new MLPHP\Database(self::$manageClient, $mlphp->config['db']);
            $db->clear();
        }

    }

    // Runs after each test class
    // https://phpunit.de/manual/current/en/fixtures.html#fixtures.variations
    public static function tearDownAfterClass()
    {
        // Nothing currently
    }

}

