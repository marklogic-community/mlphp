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

    protected $db;

    function setUp() {
      $uri = '/text.xml';
      $content = '<doc>
                    <foo bar="baz">hello</foo>
                    <one two="3">world</one>
                    <a b="c">!</one>
                  </doc>';
      $doc = new MLPHP\Document(parent::$client);
      $doc->setContent($content)->setContentType('application/xml');
      $doc->write($uri);
      $this->db = new MLPHP\Database('mlphp-test', parent::$manageClient);
    }

    function testGetConfig()
    {
        parent::$logger->debug('testGetConfig');
        $config = $this->db->getConfig();
        $this->assertObjectHasAttribute('database-config', $config);
    }

    function testGetCounts()
    {
        parent::$logger->debug('testGetCounts');
        $counts = $this->db->getCounts();
        $this->assertObjectHasAttribute('database-counts', $counts);
    }

    function testNumDocs()
    {
        parent::$logger->debug('testNumDocs');
        $this->assertEquals($this->db->numDocs(), 1);
    }

    function testGetStatus()
    {
        parent::$logger->debug('testGetStatus');
        $status = $this->db->getStatus();
        $this->assertObjectHasAttribute('database-status', $status);
    }

    function testGetProperties()
    {
        parent::$logger->debug('testGetProperties');
        $properties = $this->db->getProperties();
        $this->assertEquals($properties->{'database-name'}, 'mlphp-test');
    }

    function testSetProperties()
    {
        parent::$logger->debug('testSetProperties');
        $arr = array('word-searches' => false);
        $properties = $this->db->setProperties($arr)->getProperties();
        $this->assertEquals($properties->{'word-searches'}, false);
    }

    function testGetProperty()
    {
        parent::$logger->debug('testGetProperty');
        $property = $this->db->getProperty('database-name');
        $this->assertEquals($property, 'mlphp-test');
    }

    function testSetProperty()
    {
        parent::$logger->debug('testSetProperty');
        $key = 'word-searches';
        $value = $this->db->setProperty($key, true)->getProperty($key);
        $this->assertEquals($value, true);
    }

    function testClear()
    {
        parent::$logger->debug('testClear');
        $counts = $this->db->clear()->getCounts();
        $this->assertEquals($counts->{'database-counts'}->{'count-properties'}->documents->value, 0);
    }

    function testPropertyExists()
    {
        parent::$logger->debug('testPropertyExists');
        $result = $this->db->propertyExists(
            'range-element-index',
            array('localname' => 'created')
        );
        $this->assertTrue($result);
    }

    function testAddRangeElementIndex()
    {
        parent::$logger->debug('testAddRangeElementIndex');
        $properties1 = array(
            'scalar-type' => 'string',
            'localname' => 'foo',
            'range-value-positions' => true,
            'invalid-values' => 'ignore',
        );
        $properties2 = array(
            'scalar-type' => 'string',
            'localname' => 'one',
            'range-value-positions' => false,
            'invalid-values' => 'reject',
        );
        $this->db->addRangeElementIndex($properties1);
        $this->db->addRangeElementIndex($properties2);
        $this->assertTrue($this->db->propertyExists(
            'range-element-index',
            array('localname' => 'foo')
        ));
        $this->assertTrue($this->db->propertyExists(
            'range-element-index',
            array('localname' => 'one')
        ));
    }

    function testRemoveRangeElementIndex()
    {
        parent::$logger->debug('testRemoveRangeElementIndex');
        $this->db->removeRangeElementIndex(array('localname' => 'foo'));
        $this->assertFalse($this->db->propertyExists(
            'range-element-index',
            array('localname' => 'foo')
        ));
    }

    function testAddRangeAttributeIndex()
    {
        parent::$logger->debug('testAddRangeAttributeIndex');
        $properties = array(
            'scalar-type' => 'string',
            'parent-localname' => 'foo',
            'localname' => 'bar'
        );
        $this->db->addRangeAttributeIndex($properties);
        $this->assertTrue($this->db->propertyExists(
            'range-element-attribute-index',
            array('localname' => 'bar')
        ));
    }

    function testRemoveRangeAttributeIndex()
    {
        parent::$logger->debug('testRemoveRangeAttributeIndex');
        $this->db->removeRangeAttributeIndex(array('localname' => 'bar'));
        $this->assertFalse($this->db->propertyExists(
            'range-element-attribute-index',
            array('localname' => 'bar')
        ));
    }

    function testAddField()
    {
        parent::$logger->debug('testAddField');
        $path = new MLPHP\FieldPath(
            array(
              'path' => 'myPath',
              'weight' => 1.5
            )
        );
        $included = new MLPHP\FieldElementIncluded(
            array(
              'localname' => 'foo'
            )
        );
        $excluded = new MLPHP\FieldElementExcluded(
            array(
              'localname' => 'one',
              'attribute-localname' => 'two',
              'attribute-value' => 3
            )
        );
        $excluded2 = new MLPHP\FieldElementExcluded(
            array(
              'localname' => 'a'
            )
        );
        $field = new MLPHP\Field(array(
            'field-name' => 'myField',
            'field-path' => array($fieldPath->properties),
            'included-element' => array($included->properties),
            'excluded-element' => $excluded->properties
        ));
        //print_r($field);
        $db->addField($field);
        $response = $db->getResponse();
        //print_r($response);
        $properties = $db->getProperties();
        //print_r($properties);
        // cycle through fields, look for new one
        $fieldExists = false;
        print('num fields: ' . count($properties->{'field'}));
        foreach ($properties->{'field'} as $field) {
            if ($field->{'field-name'} == 'myField') {
                $fieldExists = true;
                break;
            }
        }
        $this->assertTrue($fieldExists);
        return $db;
    }

    function testAddRangeFieldIndex()
    {
        parent::$logger->debug('testAddRangeFieldIndex');
        $properties = array(
            'scalar-type' => 'string',
            'field-name' => 'myFieldIndex'
        );
        $this->db->addRangeFieldIndex($properties);
        $this->assertTrue($this->db->propertyExists(
            'range-field-index',
            array('field-name' => 'myFieldIndex')
        ));
    }

    function testAddPathNamespace()
    {
        parent::$logger->debug('testAddPathNamespace');
        $properties = array(
            'prefix' => 'myNS',
            'namespace-uri' => 'http://www.example.com/mlphp'
        );
        $this->db->addPathNamespace($properties);
        $this->assertTrue($this->db->propertyExists(
        $this->db->removePathNamespace(array('prefix' => 'myNS'));
        $this->assertFalse($this->db->propertyExists(
            'path-namespace',
            array('prefix' => 'myNS')
        ));
    }

    function testAddRangePathIndex()
    {
        parent::$logger->debug('testAddRangePathIndex');
        $properties = array(
            'scalar-type' => 'string',
            'path-expression' => 'one/@two'
        );
        $this->db->addRangePathIndex($properties);
        $this->assertTrue($this->db->propertyExists(
            'range-path-index',
            array('path-expression' => 'one/@two')
        ));
    }

}

