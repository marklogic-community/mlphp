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
class DatabaseTest extends TestBase
{

    function setUp() {
      $uri = '/text.xml';
      $content = '<doc><foo bar="baz">hello</foo><honk sheba="rabies">wipe</honk></doc>';
      $doc = new MLPHP\Document(parent::$client);
      $doc->setContent($content)->setContentType('application/xml');
      $doc->write($uri);
    }

    function testGetConfig()
    {
        parent::$logger->debug('testGetConfig');
        $db = new MLPHP\Database('mlphp-test', parent::$manageClient);
        $config = $db->getConfig();
        $this->assertObjectHasAttribute('database-config', $config);
        return $db;
    }

    function testGetCounts()
    {
        parent::$logger->debug('testGetCounts');
        $db = new MLPHP\Database('mlphp-test', parent::$manageClient);
        $counts = $db->getCounts();
        $this->assertObjectHasAttribute('database-counts', $counts);
       return $db;
    }

    function testNumDocs()
    {
        parent::$logger->debug('testNumDocs');
        $db = new MLPHP\Database('mlphp-test', parent::$manageClient);
        $this->assertEquals($db->numDocs(), 1);
        return $db;
    }

    function testGetStatus()
    {
        parent::$logger->debug('testGetStatus');
        $db = new MLPHP\Database('mlphp-test', parent::$manageClient);
        $status = $db->getStatus();
        $this->assertObjectHasAttribute('database-status', $status);
        return $db;
    }

    function testGetProperties()
    {
        parent::$logger->debug('testGetProperties');
        $db = new MLPHP\Database('mlphp-test', parent::$manageClient);
        $properties = $db->getProperties();
        $this->assertEquals($properties->{'database-name'}, 'mlphp-test');
        return $db;
    }

    function testSetProperties()
    {
        parent::$logger->debug('testSetProperties');
        $db = new MLPHP\Database('mlphp-test', parent::$manageClient);
        $json = '{"word-searches":false}';
        $properties = $db->setProperties($json)->getProperties();
        $this->assertEquals($properties->{'word-searches'}, false);
        return $db;
    }

    function testClear()
    {
        parent::$logger->debug('testClear');
        $db = new MLPHP\Database('mlphp-test', parent::$manageClient);
        $counts = $db->clear()->getCounts();
        $this->assertEquals($counts->{'database-counts'}->{'count-properties'}->documents->value, 0);
        return $db;
    }

    function testAddRangeElementIndex()
    {
        parent::$logger->debug('testAddRangeElementIndex');
        $db = new MLPHP\Database('mlphp-test', parent::$manageClient);
        $scalarType = 'string';
        $localname = 'foo';
        $namespaceURI = '';
        $rangeValuePositions = true;
        $invalidValues = 'ignore';
        $collation = '';
        $db->addRangeElementIndex(
            $scalarType, $localname, $namespaceURI, $rangeValuePositions,
            $invalidValues, $collation
        );
        $properties = $db->getProperties();
        // cycle through indexes, look for new one
        $indexExists = false;
        foreach ($properties->{'range-element-index'} as $index) {
            if ($index->localname == 'foo') {
                $indexExists = true;
                break;
            }
        }
        $this->assertTrue($indexExists);
        return $db;
    }

    function testAddRangeAttributeIndex()
    {
        parent::$logger->debug('testAddRangeAttributeIndex');
        $db = new MLPHP\Database('mlphp-test', parent::$manageClient);
        $scalarType = 'string';
        $parentLocalname = 'foo';
        $localname = 'bar';
        $parentNamespaceURI = '';
        $namespaceURI = '';
        $rangeValuePositions = true;
        $invalidValues = 'ignore';
        $collation = '';
        $db->addRangeAttributeIndex(
            $scalarType, $parentLocalname, $localname, $parentNamespaceURI,
            $namespaceURI, $rangeValuePositions, $invalidValues, $collation
        );
        $properties = $db->getProperties();
        // cycle through indexes, look for new one
        $indexExists = false;
        foreach ($properties->{'range-element-attribute-index'} as $index) {
            if ($index->localname == 'bar') {
                $indexExists = true;
                break;
            }
        }
        $this->assertTrue($indexExists);
        return $db;
    }

}

