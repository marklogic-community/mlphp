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
 */
class SearchTest extends TestBaseSearch
{

    function setUp()
    {
        parent::setUp();


        //parent::loadDocsXML(parent::$client);
        //parent::setIndexes(parent::$manageClient);
        //parent::setOptions(parent::$client);
    }

    function testSimpleText()
    {
        parent::$logger->debug('testSimpleText');
        $options = new MLPHP\Options(parent::$client, 'simpleText');
        $options->write();
        $search = new MLPHP\Search(parent::$client, 1, 3);
        $results = $search->retrieve('spotsylvania', array('options' => 'simpleText'));
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
        $this->assertEquals(19, $results->getTotal());
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
        $this->assertEquals(19, $results->getTotal());
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
        // Must match entire value inside element, so the following fails
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
        parent::$logger->debug('testElement');
        $options = new MLPHP\Options(parent::$client, 'testElement');
        $constraint = new MLPHP\RangeConstraint(
            'status', 'xs:string', 'true', 'status'
        );
        $options->addConstraint($constraint)->write();
        $search = new MLPHP\Search(parent::$client, 1, 3);
        $results = $search->retrieve('status:enacted', array(
            'options' => 'testElement'
        ));
        $this->assertEquals(2, $results->getTotal());
    }

    function testRangeAttribute()
    {
        parent::$logger->debug('testAttribute');
        $options = new MLPHP\Options(parent::$client, 'testAttribute');
        $constraint = new MLPHP\RangeConstraint(
            'number', 'xs:int', 'false', 'bill', '', 'number'
        );
        $options->addConstraint($constraint)->write();
        $search = new MLPHP\Search(parent::$client, 1, 3);
        $results = $search->retrieve('number:1', array(
            'options' => 'testAttribute'
        ));
        $this->assertEquals(2, $results->getTotal());
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
        $this->assertEquals(8, $results->getTotal());
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
            'Government information and archives',
            $facetVals[0]->getName()
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
        $results = $search->retrieve('', array(
            'options' => 'testExtractConstraint'
        ));
        $this->assertEquals(
            'Adoption Information Act',
            $results->getResultByIndex(1)->getMetadata('title')[0]
        );
    }

    function testExtractQName()
    {
        // NOT WORKING: https://github.com/marklogic/mlphp/issues/6
        // parent::$logger->debug('testExtractQName');
        // $options = new MLPHP\Options(parent::$client, 'testExtractQName');
        // $constraint = new MLPHP\RangeConstraint(
        //     'title', 'xs:string', 'false', 'title'
        // );
        // $options->addConstraint($constraint);
        // $extracts = new MLPHP\Extracts();
        // $extracts->addQName('status');
        // $extracts->addConstraints(array('title'));
        // $options->setExtracts($extracts)->write();
        // $search = new MLPHP\Search(parent::$client, 1, 2);
        // $results = $search->retrieve('', array(
        //     'options' => 'testExtractQName',
        //     'collection' => 'h'
        // ));
        // print_r($results);
        // $this->assertEquals(
        //     '',
        //     $results->getResultByIndex(1)->getMetadata('status')[0]
        // );
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

    function testReturnResults()
    {
        // NOT WORKING https://github.com/marklogic/mlphp/issues/7
        // parent::$logger->debug('testReturnResults');
        // $options = new MLPHP\Options(parent::$client, 'testReturnResults');
        // // Default is true so set false and check
        // $options->setReturnResults('false')->write();
        // print_r($options->getAsXML());
        // $search = new MLPHP\Search(parent::$client, 1, 2);
        // $results = $search->retrieve('act', array(
        //     'options' => 'testReturnResults'
        // ));
        // $this->assertNull($results->getQuery());
    }
        // collection search


        // directory search


        // element constraint search


        // attribute constraint search


        // extract metadata


        // $search = new MLPHP\Search(parent::$client, 1, 3);
        // $results = $search->retrieve("spotsylvania", array(
        //     'options' => 'test'
        // ));
        // print_r($results);

        // $search = new MLPHP\Search(parent::$client, 1, 5);
        // $results = $search->retrieve("services", array(
        //     'directory' => '/bills/112',
        //     'options' => 'test'
        // ));
        // print_r($results);

        // $search = new MLPHP\Search(parent::$client, 1, 5);
        // $results = $search->retrieveKeyValueElement("subject", "", "Taxation", array(
        //      'options' => 'test'
        // ));
        // print_r($results);

        // $search = new MLPHP\Search(parent::$client, 1, 3);
        // $results = $search->retrieveKeyValueElement("bill", "number", "104", array(
        //      'options' => 'test'
        // ));
        // print_r($results);

    //}


    // function testSearchJSON()
    // {

        // parent::loadDocsJSON(parent::$client);

        // $search = new MLPHP\Search(parent::$client, 1, 5);
        // $results = $search->retrieve("", array(
        //     'collection' => 'Republican'
        // ));
        // print_r($results);

        // $search = new MLPHP\Search(parent::$client, 1, 2);
        // $results = $search->retrieveKeyValue("id", "NVL000005");
        // print_r($results);

        // $search = new MLPHP\Search(parent::$client, 1, 5);
        // $results = $search->retrieveKeyValueElement("subject", "", "Taxation");
        // print_r($results);

        // $search = new MLPHP\Search(parent::$client, 1, 3);
        // $results = $search->retrieveKeyValueElement("bill", "number", "104");
        // print_r($results);

    //}
}

