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
 */
class SearchTest extends TestBase
{
    function testTerm()
    {
        $options = new MLPHP\Options($this->client);
        $term = new MLPHP\Term("all-results");
        $options->setTerm($term);
        
        $this->assertXmlStringEqualsXmlString('
            <options xmlns="http://marklogic.com/appservices/search">
                <term>
                    <empty apply="all-results"/> 
                </term>
            </options>
        ', $options->getAsXML());

        $wc = new MLPHP\WordConstraint("wc", "one", ""); 
        $term->setDefault($wc);
        $options->setTerm($term);
        $this->assertXmlStringEqualsXmlString('
            <options xmlns="http://marklogic.com/appservices/search">
                <term>
                    <empty apply="all-results"/> 
                    <default>
                        <word>
                            <element ns="" name="one"/>
                        </word>
                    </default>
                </term>
            </options>
        ', $options->getAsXML());
        
    }

    function setUp() {
        parent::setUp();

        $doc = new MLPHP\XMLDocument($this->client, "/one.xml");
        $doc->setContent('<Hello><one>Foo</one><two>Bar</two></Hello>');
        $doc->write("/one.xml");
    }
}

