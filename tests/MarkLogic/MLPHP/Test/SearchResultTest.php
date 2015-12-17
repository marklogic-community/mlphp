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
class SearchResultTest extends TestBase
{

    protected $sr;

    function setUp() {
        $xml = TestData::getSearchResult();
        $doc = new \DOMDocument();
        $doc->loadXML($xml);
        $elems = $doc->getElementsByTagName('result');
        $this->sr = new MLPHP\SearchResult($elems->item(1));
    }

    function testGetIndex()
    {
        $index = $this->sr->getIndex();
        $this->assertEquals(12, $index);
    }

    function testGetURI()
    {
        $uri = $this->sr->getURI();
        $this->assertEquals('/bills/111/h1262.xml', $uri);
    }

    function testGetPath()
    {
        $path = $this->sr->getPath();
        $this->assertEquals('fn:doc("/bills/111/h1262.xml")', $path);
    }

    function testGetScore()
    {
        $this->assertEquals(50688, $this->sr->getScore());
    }

    function testGetConfidence()
    {
        $this->assertEquals(0.4909869, $this->sr->getConfidence());
    }

    function testGetFitness()
    {
        $this->assertEquals(0.8105518, $this->sr->getFitness());
    }

    function testGetMatches()
    {
        $this->assertEquals(1, count($this->sr->getMatches()));
    }

    function testGetMetadata()
    {
        $meta1 = $this->sr->getMetadata('subject');
        $this->assertEquals(4, count($meta1));
        $meta2 = $this->sr->getMetadata('foo');
        $this->assertNull($meta2);
    }

    function testGetMetadataQName()
    {
        $meta1 = $this->sr->getMetadataQName('bar');
        $this->assertEquals(1, count($meta1));
        $this->assertEquals('baz', $meta1[0]);
        $meta2 = $this->sr->getMetadataQName('baz');
        $this->assertNull($meta2);
        $meta3 = $this->sr->getMetadataQName('bar', 'http://marklogic.com/appservices/search');
        $this->assertEquals(1, count($meta3));
        $this->assertEquals('baz', $meta3[0]);
    }

    function testGetMetadataKeys()
    {
        $keys = $this->sr->getMetadataKeys();
        $this->assertEquals(7, count($keys));
        $this->assertTrue(in_array('link', $keys));
        $this->assertFalse(in_array('bar', $keys));
    }

    function testGetSimilar()
    {
        $similar = $this->sr->getSimilar();
        $this->assertEquals(2, count($similar));
        $this->assertEquals($similar[1], '/bills/112/h1189.xml');
    }
}

