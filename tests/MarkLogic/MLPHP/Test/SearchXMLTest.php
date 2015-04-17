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
 * Search tests that run on database with XML content
 */
class SearchTestXML extends TestBaseSearch
{

    function setUp()
    {
        global $mlphp;
        if (substr($mlphp->config['mlversion'], 0, 3) < 8) {
            $this->markTestSkipped('Test requires MarkLogic 8 or greater');
        }
    }

    function testSimpleXML()
    {
        // Load docs that are used in tests that follow
        parent::loadDocsXML(parent::$client);

        parent::$logger->debug('testSimpleXML');
        $options = new MLPHP\Options(parent::$client, 'simpleXML');
        $options->write();
        $search = new MLPHP\Search(parent::$client, 1, 3);
        $results = $search->retrieve('spotsylvania', array('options' => 'simpleXML'));
        $this->assertEquals(1, $results->getTotal());
    }

    function testCollection()
    {
        parent::$logger->debug('testCollection');
        $options = new MLPHP\Options(parent::$client, 'testCollection');
        $options->write();
        $search = new MLPHP\Search(parent::$client, 1, 3);
        $results = $search->retrieve('', array(
            'options' => 'testCollection',
            'collection' => 'h'
        ));
        $this->assertEquals(15, $results->getTotal());
    }

    function testPropertyConstraint()
    {
        parent::$logger->debug('testPropertyConstraint');
        $options = new MLPHP\Options(parent::$client, 'testPropertyConstraint');
        $constraint = new MLPHP\PropertiesConstraint(
            'myprop'
        );
        $options->addConstraint($constraint)->write();
        $search = new MLPHP\Search(parent::$client, 1, 10);
        $results = $search->retrieve('myprop:110', array(
            'options' => 'testPropertyConstraint'
        ));
        $this->assertEquals(6, $results->getTotal());
    }

    function testCollectionConstraint()
    {
        parent::$logger->debug('testCollectionConstraint');
        // note: collection-lexicon db prop must be set to true
        // @todo test collection constraints with prefixes
        // http://developer.marklogic.com/blog/collection-constraints-are-cool
        $options = new MLPHP\Options(parent::$client, 'testCollectionConstraint');
        $constraint = new MLPHP\CollectionConstraint(
            'type', ''
        );
        $options->addConstraint($constraint)->write();
        $search = new MLPHP\Search(parent::$client, 1, 3);
        $results = $search->retrieve('type:h', array(
            'options' => 'testCollectionConstraint'
        ));
        $this->assertEquals(15, $results->getTotal());
    }

    function testElementQueryConstraint()
    {
        parent::$logger->debug('testElementQueryConstraint');
        // @todo element-query deprecated, use container constraint
        // http://docs.marklogic.com/guide/rest-dev/appendixb#id_96729
        $options = new MLPHP\Options(parent::$client, 'testElementQueryConstraint');
        $constraint = new MLPHP\ElementQueryConstraint(
            'blah', 'subject'
        );
        $options->addConstraint($constraint)->write();
        $search = new MLPHP\Search(parent::$client, 1, 3);
        $results = $search->retrieve('blah:Genetics', array(
            'options' => 'testElementQueryConstraint'
        ));
        $this->assertEquals(1, $results->getTotal());
    }

    function testValueConstraint()
    {
        parent::$logger->debug('testValueConstraint');
        $options = new MLPHP\Options(parent::$client, 'testValueConstraint');
        $constraint = new MLPHP\ValueConstraint(
            'blah', 'subject'
        );
        $options->addConstraint($constraint)->write();
        $search = new MLPHP\Search(parent::$client, 1, 3);
        $results = $search->retrieve('blah:"Evidence and witnesses"', array(
            'options' => 'testValueConstraint'
        ));
        // Must match entire value inside element, so the following returns
        // no results
        $this->assertEquals(1, $results->getTotal());
        $results2 = $search->retrieve('blah:Evidence', array(
            'options' => 'testValueConstraint'
        ));
        $this->assertEquals(0, $results2->getTotal());
    }

    function testWordConstraint()
    {
        parent::$logger->debug('testWordConstraint');
        $options = new MLPHP\Options(parent::$client, 'testWordConstraint');
        $constraint = new MLPHP\WordConstraint(
            'foo', 'subject'
        );
        $options->addConstraint($constraint)->write();
        $search = new MLPHP\Search(parent::$client, 1, 3);
        $results = $search->retrieve('foo:Evidence', array(
            'options' => 'testWordConstraint'
        ));
        $this->assertEquals(2, $results->getTotal());
    }

    function testDirectory()
    {
        parent::$logger->debug('testDirectory');
        $options = new MLPHP\Options(parent::$client, 'testDirectory');
        $options->write();
        $search = new MLPHP\Search(parent::$client, 1, 3);
        $results = $search->retrieve('', array(
            'options' => 'testDirectory',
            'directory' => '/bills/110'
        ));
        $this->assertEquals(6, $results->getTotal());
    }

    function testRangeElement()
    {
        parent::$logger->debug('testRangeElement');
        $options = new MLPHP\Options(parent::$client, 'testRangeElement');
        $constraint = new MLPHP\RangeConstraint(
            'status', 'xs:string', 'true', 'status'
        );
        $options->addConstraint($constraint)->write();
        $search = new MLPHP\Search(parent::$client, 1, 3);
        $results = $search->retrieve('status:introduced', array(
            'options' => 'testRangeElement'
        ));
        $this->assertEquals(12, $results->getTotal());
    }

    function testRangeAttribute()
    {
        parent::$logger->debug('testRangeAttribute');
        $options = new MLPHP\Options(parent::$client, 'testRangeAttribute');
        $constraint = new MLPHP\RangeConstraint(
            'number', 'xs:int', 'false', 'bill', '', 'number'
        );
        $options->addConstraint($constraint)->write();
        $search = new MLPHP\Search(parent::$client, 1, 3);
        $results = $search->retrieve('number:103', array(
            'options' => 'testRangeAttribute'
        ));
        $this->assertEquals(2, $results->getTotal());
    }

    function testRangeBucket()
    {
        parent::$logger->debug('testBucketConstraint');
        $options = new MLPHP\Options(parent::$client, 'testBucketConstraint');
        $constraint = new MLPHP\RangeConstraint(
          'myBucket', 'xs:int', 'true', 'bill', '', 'number'
        );
        $buck1 = new MLPHP\Bucket('low', array(
            'lt' => 1000
        ));
        $buck2 = new MLPHP\Bucket('high', array(
            'ge' => 1000,
            'lt' => 2000
        ));
        $constraint->addBuckets(array($buck1, $buck2));
        $options->addConstraint($constraint)->write();
        $search = new MLPHP\Search(parent::$client, 1, 3);
        $results = $search->setDirectory('/bills')->retrieve(
            'myBucket:high', array('options' => 'testBucketConstraint')
        );
        $this->assertEquals(4, $results->getTotal());
    }

    function testRangePath()
    {
        parent::$logger->debug('testRangePath');
        $options = new MLPHP\Options(parent::$client, 'testRangePath');
        $constraint = new MLPHP\PathRangeConstraint(
            'date', 'xs:string', 'false', 'introduced/@date'
        );
        $options->addConstraint($constraint)->write();
        $search = new MLPHP\Search(parent::$client, 1, 3);
        $results = $search->retrieve('date:2009-01-06', array(
            'options' => 'testRangePath'
        ));
        $this->assertEquals(5, $results->getTotal());
    }

    function testFacets()
    {
        parent::$logger->debug('testFacets');
        $options = new MLPHP\Options(parent::$client, 'testFacets');
        $constraint = new MLPHP\RangeConstraint(
            'subject', 'xs:string', 'true', 'subject'
        );
        $constraint->setFacetOptions(
            array('descending', 'frequency-order', 'limit=5')
        );
        $options->addConstraint($constraint)->write();
        $search = new MLPHP\Search(parent::$client, 1, 3);
        $results = $search->retrieve('', array(
            'options' => 'testFacets'
        ));
        $facetVals = $results->getFacet('subject')->getFacetValues();
        $this->assertCount(5, $facetVals);
        $this->assertEquals(
            'Commemorations',
            $facetVals[3]->getName()
        );
        $this->assertGreaterThan(
            $facetVals[4]->getCount(),
            $facetVals[0]->getCount()
        );
    }

    function testExtractConstraint()
    {
        parent::$logger->debug('testExtractConstraint');
        $options = new MLPHP\Options(parent::$client, 'testExtractConstraint');
        $constraint = new MLPHP\RangeConstraint(
            'title', 'xs:string', 'false', 'title'
        );
        $options->addConstraint($constraint);
        $extracts = new MLPHP\Extracts();
        $extracts->addConstraints(array('title'));
        $options->setExtracts($extracts)->write();
        $search = new MLPHP\Search(parent::$client, 1, 3);
        $results = $search->retrieve('Adoption Information', array(
            'options' => 'testExtractConstraint'
        ));
        $this->assertEquals(
            'Adoption Information Act',
            $results->getResultByIndex(1)->getMetadata('title')[0]
        );
    }

    function testExtractQName()
    {
        // @todo not working, https://github.com/marklogic/mlphp/issues/6
        parent::$logger->debug('testExtractQName');
        $options = new MLPHP\Options(parent::$client, 'testExtractQName');
        $constraint = new MLPHP\RangeConstraint(
            'title', 'xs:string', 'false', 'title'
        );
        $options->addConstraint($constraint);
        $extracts = new MLPHP\Extracts();
        $extracts->addQName('status');
        $extracts->addConstraints(array('title'));
        $options->setExtracts($extracts)->write();
        $search = new MLPHP\Search(parent::$client, 1, 2);
        $results = $search->retrieve('', array(
            'options' => 'testExtractQName',
            'collection' => 'h'
        ));
        $this->assertEquals(
            '',
            $results->getResultByIndex(1)->getMetadata('status')[0]
        );
    }

    function testReturnQtext()
    {
        parent::$logger->debug('testReturnQtext');
        $options = new MLPHP\Options(parent::$client, 'testReturnQtext');
        // Default is true so set false and check
        $options->setReturnQtext('false')->write();
        $search = new MLPHP\Search(parent::$client, 1, 1);
        $results = $search->retrieve('act', array(
            'options' => 'testReturnQtext'
        ));
        $this->assertNull($results->getQtext());
    }

    function testReturnQuery()
    {
        parent::$logger->debug('testReturnQuery');
        $options = new MLPHP\Options(parent::$client, 'testReturnQuery');
        $options->setReturnQuery('true')->write();
        $search = new MLPHP\Search(parent::$client, 1, 1);
        $results = $search->retrieve('bill', array(
            'options' => 'testReturnQuery'
        ));
        $this->assertNotNull($results->getQuery());
    }

    function testFieldConstraint()
    {

        parent::$logger->debug('testFieldConstraint');

        $options = new MLPHP\Options(parent::$client, 'testFieldRangeConstraint1');
        $constraint = new MLPHP\FieldRangeConstraint(
            'blah', 'xs:string', 'true', 'field1'
        );
        $options->addConstraint($constraint)->write();
        $search = new MLPHP\Search(parent::$client, 1, 3);
        $results = $search->retrieve('blah:"h 1001"', array(
            'options' => 'testFieldRangeConstraint1'
        ));
        $this->assertEquals(1, $results->getTotal());

        $options = new MLPHP\Options(parent::$client, 'testFieldRangeConstraint2');
        $constraint = new MLPHP\FieldRangeConstraint(
            'foo', 'xs:string', 'false', 'field2'
        );
        $options->addConstraint($constraint)->write();
        $search = new MLPHP\Search(parent::$client, 1, 3);
        $results = $search->retrieve('foo:"110 h 103"', array(
            'options' => 'testFieldRangeConstraint2'
        ));
        $this->assertEquals(1, $results->getTotal());

        $options = new MLPHP\Options(parent::$client, 'testFieldWordConstraint');
        $constraint = new MLPHP\FieldWordConstraint(
            'bar', 'field3'
        );
        $options->addConstraint($constraint)->write();
        $search = new MLPHP\Search(parent::$client, 1, 3);
        $results = $search->retrieve('bar:H.R.', array(
            'options' => 'testFieldWordConstraint'
        ));
        $this->assertEquals(15, $results->getTotal());
    }

}

