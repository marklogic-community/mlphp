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
class FacetTest extends TestBase
{
    function testFacetTest()
    {
        parent::$logger->debug('testFacetTest');
        $xml = '
            <response total="2370" start="1" page-length="10">
              <facet name="decade">
                <facet-value name="2000s" count="240">
                 2000s</facet-value>
                <facet-value name="1990s" count="300">
                 1990s</facet-value>
                <facet-value name="1980s" count="300">
                 1980s</facet-value>
              </facet>
            </response>
        ';
        $doc = new \DOMDocument();
        $doc->loadXML($xml);
        $facet = new MLPHP\Facet($doc->getElementsByTagName('facet')->item(0));

        $this->assertEquals($facet->getName(), 'decade');
        // @todo test for $facet->getType() (?)
        $this->assertEquals(count($facet->getFacetValues()), 3);
    }
}

