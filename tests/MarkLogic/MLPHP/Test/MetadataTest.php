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
 * @author Eric Bloch <eric.bloch@gmail.com>
 */
class MetadataTest extends TestBase
{

    protected $m;

    function setUp() {
        $this->m = new MLPHP\Metadata();
    }

    function testCollections()
    {
        parent::$logger->debug('testCollections');
        // Add 2 collections, then 1 more
        $this->m->addCollections(['foo', 'bar']);
        $this->m->addCollections('baz');
        $coll = $this->m->getCollections();
        $this->assertEquals(3, count($coll));
        // Delete 2
        $this->m->deleteCollections(['bar', 'baz']);
        $coll = $this->m->getCollections();
        $this->assertEquals($coll[0], 'foo');
        // Delete 1
        $this->m->deleteCollections('foo');
        $coll = $this->m->getCollections();
        $this->assertCount(0, $coll);
    }

    function testPermissions()
    {
        parent::$logger->debug('testPermissions');
        // Add 2 permissions, then 1 more
        $perm1 = new MLPHP\Permission('myRole1', ['cap1', 'cap2']);
        $perm2 = new MLPHP\Permission('myRole2', ['cap1']);
        $perm3 = new MLPHP\Permission('myRole3', ['cap2']);
        $this->m->addPermissions([$perm1, $perm2]);
        $this->m->addPermissions($perm3);
        $perms = $this->m->getPermissions();
        $this->assertEquals(3, count($perms));
        // Delete 2
        $this->m->deletePermissions(['myRole2', 'myRole3']);
        $perms = $this->m->getPermissions();
        $this->assertEquals($perms[0]->getRoleName(), 'myRole1');
        // Delete 1
        $this->m->deletePermissions('myRole1');
        $perms = $this->m->getPermissions();
        $this->assertCount(0, $perms);
    }

    function testProperties()
    {
        parent::$logger->debug('testProperties');
        // Add 2 properties, then 1 more
        $this->m->addProperties(array('prop1' => 'val1', 'prop2' => 'val2'));
        $this->m->addProperties(array('prop3' => 'val3'));
        $props = $this->m->getProperties();
        $this->assertEquals(3, count($props));
        // Delete 2
        $this->m->deleteProperties(['prop2', 'prop3']);
        $props = $this->m->getProperties();
        $this->assertEquals($props['prop1'], 'val1');
        // Delete 1
        $this->m->deleteProperties('prop1');
        $props = $this->m->getProperties();
        $this->assertCount(0, $props);
    }

    function testQuality()
    {
        parent::$logger->debug('testQuality');
        $this->m->setQuality(3);
        $qual = $this->m->getQuality();
        $this->assertEquals(3, $qual);
    }

    function testLoadFromXML()
    {
        parent::$logger->debug('testLoadFromXML');
        $this->m->loadFromXML(TestData::getMetadata());
        $colls = $this->m->getCollections();
        $perms = $this->m->getPermissions();
        $props = $this->m->getProperties();
        $this->assertEquals($colls[0], 'coll1');
        $caps = $perms[0]->getCapabilities();
        $this->assertEquals($caps[0], 'myCap');
        $this->assertEquals($props['propKey'], 'propVal');
        $this->assertEquals($this->m->getQuality(), 1);
        // Object as XML should be equal to original XML
        $this->assertXmlStringEqualsXmlString(
            $this->m->getAsXML(), TestData::getMetadata()
        );
        // Change object, XML no longer equal
        $this->m->setQuality(9);
        $this->assertXmlStringNotEqualsXmlString(
            $this->m->getAsXML(), TestData::getMetadata()
        );
    }
}

