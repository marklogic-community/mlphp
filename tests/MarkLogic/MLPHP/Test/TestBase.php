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
 * @author Eric Bloch <eric.bloch@gmail.com>
 * @author Mike Wooldridge <mike.wooldridge@marklogic.com>
 *
 * Extended by test classes that DO NOT access the database.
 *
 */
abstract class TestBase extends \PHPUnit_Framework_TestCase
{
    protected static $client;
    protected static $logger;

    // Runs before each test class
    // https://phpunit.de/manual/current/en/fixtures.html#fixtures.variations
    public static function setUpBeforeClass()
    {
        global $mlphp;

        parent::setUpBeforeClass();

        // Create a REST client for tests
        // Some classes require a client for testing even though they do not
        // access the database
        self::$client = $mlphp->getClient();

        // Create a logger for tests
        self::$logger = $mlphp->config['logger'];
    }

}

