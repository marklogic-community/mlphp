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
class SearchResultsTest extends TestBase
{

    protected $sr;

    function setUp() {
        $xml = TestData::getSearchResult();
        $this->sr = new MLPHP\SearchResults($xml);
    }

    function testGetResults()
    {
        $results = $this->sr->getResults();
        $this->assertEquals(5, count($results));
    }

    function testGetResultByURI()
    {
        $results = $this->sr->getResults();
        $result = $this->sr->getResultByURI('/bills/111/h1258.xml');
        $this->assertEquals($results[0], $result);
    }

    function testGetResultByIndex()
    {
        $results = $this->sr->getResults();
        $result = $this->sr->getResultByIndex(12);
        $this->assertEquals($results[1], $result);
    }

    function testGetTotal()
    {
        $this->assertEquals(37, $this->sr->getTotal());
    }

    function testGetStart()
    {
        $this->assertEquals(11, $this->sr->getStart());
    }

    function testGetEnd()
    {
        $this->assertEquals(15, $this->sr->getEnd());
    }

    function testGetCurrentPage()
    {
        $this->assertEquals(3, $this->sr->getCurrentPage());
    }

    function testGetTotalPages()
    {
        $this->assertEquals(8, $this->sr->getTotalPages());
    }

    function testGetPageLength()
    {
        $results = $this->sr->getResults();
        $this->assertEquals(count($results), $this->sr->getPageLength());
    }

    function testGetPreviousStart()
    {
        $this->assertEquals(6, $this->sr->getPreviousStart());
    }

    function testGetNextStart()
    {
        $this->assertEquals(16, $this->sr->getNextStart());
    }

    function testHasFacets()
    {
        $this->assertTrue($this->sr->hasFacets());
    }

    function testGetFacets()
    {
        $facets = $this->sr->getFacets();
        $this->assertEquals(2, count($facets));
        $this->assertEquals('status', $facets[0]->getName());
    }

    function testGetFacet()
    {
        $facet = $this->sr->getFacet('subject');
        $this->assertNotNull($facet);
        $this->assertEquals('subject', $facet->getName());
    }
}

