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
 */
abstract class TestBaseDB extends TestBase
{
    private static $api;
    protected static $client;
    protected static $manageClient;
    private static $config = array(
        'host' => '127.0.0.1',
        'port' => 8234,
        'db' => 'mlphp-test',
        'username' => 'admin',
        'password' => 'admin',
        'apiName' => 'test-mlphp-rest-api'
    );

    // Runs before each test class
    // https://phpunit.de/manual/current/en/fixtures.html#fixtures.variations
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        // Create a REST API for tests
        self::$api = new MLPHP\RESTAPI(
            self::$config['apiName'],
            self::$config['host'],
            self::$config['db'],
            self::$config['port'],
            self::$config['username'],
            self::$config['password'],
            parent::$logger
        );
        if (!self::$api->exists()) {
            self::$api->create();
        } else {
            parent::$logger->debug(
              'REST API ' . self::$config['apiName'] . ' already exists'
            );
        }

        // Create a REST client for tests
        self::$client = new MLPHP\RESTClient(
            self::$config['host'],
            self::$config['port'],
            '',
            'v1',
            self::$config['username'],
            self::$config['password'],
            'digest',
            parent::$logger
        );

        // Create a manage client for tests
        self::$manageClient = new MLPHP\RESTClient(
            self::$config['host'],
            8002,
            'manage',
            'v2',
            self::$config['username'],
            self::$config['password'],
            'digest',
            parent::$logger
        );

        // Clear the REST API database
        $db = new MLPHP\Database(self::$config['db'], self::$manageClient);
        $db->clear();

    }

    // Runs after each test class
    // https://phpunit.de/manual/current/en/fixtures.html#fixtures.variations
    public static function tearDownAfterClass()
    {
        // self::$api->delete();
        // $db = new MLPHP\Database(self::$config['db'], self::$manageClient);
        // $db->delete();
    }

    public static function loadDocs($client)
    {
        $doc = new MLPHP\Document($client);
        $count = 0;
        foreach(scandir(__DIR__ . '/docs/') as $filename) {
            print(__DIR__ . '/docs/' . $filename . PHP_EOL);
            $doc->setContentFile(__DIR__ . '/docs/' . $filename);
            $doc->write($filename);
            $count++;
            //parent::$logger->debug('Loaded: ' . $filename . PHP_EOL);
        }
        parent::$logger->debug('Files loaded: ' . $count . PHP_EOL);
    }

    public static function setIndexes($manageClient)
    {
        parent::$logger->debug('setIndexes');
        $db = new MLPHP\Database('mlphp-test', $manageClient);
        $props1 = array(
            'scalar-type' => 'int',
            'parent-localname' => 'bill',
            'localname' => 'session'
        );
        $db->addRangeAttributeIndex($props1);
        $props2 = array(
            'scalar-type' => 'string',
            'parent-localname' => 'bill',
            'localname' => 'type'
        );
        $db->addRangeAttributeIndex($props2);
        $props3 = array(
            'scalar-type' => 'string',
            'localname' => 'status'
        );
        $db->addRangeElementIndex($props3);
        $props4 = array(
            'scalar-type' => 'string',
            'localname' => 'title'
        );
        $db->addRangeElementIndex($props4);
    }

    public static function setOptions($client)
    {
        parent::$logger->debug('setOptions');
        $options = new MLPHP\Options($client);
        $constr1 = new MLPHP\RangeConstraint(
            'sess', 'xs:int', 'true', 'bill', '', 'session'
        );
        $options->addConstraint($constr1);
        $constr2 = new MLPHP\RangeConstraint(
            'type', 'xs:string', 'true', 'bill', '', 'type'
        );
        $options->addConstraint($constr2);
        $constr3 = new MLPHP\RangeConstraint(
            'title', 'xs:string', 'false', 'title'
        );
        $options->addConstraint($constr3);
        $constr4 = new MLPHP\RangeConstraint(
            'status', 'xs:string', 'true', 'status'
        );
        $options->addConstraint($constr4);

        $extracts = new MLPHP\Extracts();
        $extracts->addConstraints('title');
        $options->setExtracts($extracts);

        $transform = new MLPHP\TransformResults('snippet');
        $pref1 = new MLPHP\PreferredElement('title', '');
        $pref2 = new MLPHP\PreferredElement('summary', '');
        $transform->addPreferredElements(array($pref1, $pref2));
        $options->setTransformResults($transform);

        $options->setReturnFacets('true');

        $options->write('test');
    }
}

