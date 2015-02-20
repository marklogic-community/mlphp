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
class SearchTest extends TestBaseSearch
{

    function testSimpleText()
    {
        // Load docs that are used in tests that follow
        parent::loadDocsText(parent::$client);

        parent::$logger->debug('testText');
        $options = new MLPHP\Options(parent::$client, 'simpleText');
        $options->write();
        $search = new MLPHP\Search(parent::$client, 1, 3);
        $results = $search->retrieve('MLPHP!!!', array('options' => 'simpleText'));
        $this->assertEquals(1, $results->getTotal());
        $results = $search->retrieve('goodbye???', array('options' => 'simpleText'));
        $this->assertEquals(0, $results->getTotal());
    }

    function testStructuredQuery()
    {
        parent::$logger->debug('testStructuredQuery');
        $search = new MLPHP\Search(parent::$client, 1, 3);
        $results = $search->retrieve('
            <query xmlns="http://marklogic.com/appservices/search">
                <term-query>
                    <text>MLPHP!!!</text>
                </term-query>
            </query>
        ', array(), true);
        $this->assertEquals(1, $results->getTotal());
        $results = $search->retrieve('
            <query xmlns="http://marklogic.com/appservices/search">
                <term-query>
                    <text>goodbye???</text>
                </term-query>
            </query>
        ', array(), true);
        $this->assertEquals(0, $results->getTotal());
    }

}

