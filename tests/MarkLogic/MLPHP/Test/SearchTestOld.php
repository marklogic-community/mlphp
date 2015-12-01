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
class SearchTestOld extends TestBaseSearch
{

    function setUp()
    {
        parent::setUp();
    }

    function testSearchOld()
    {

        // $search = new MLPHP\Search(parent::$client, 0, 100);

        // // results
        // $results = $search->retrieve("world");
        // $this->assertEquals($results->getTotal(), 1);

        // // no results
        // $results = $search->retrieve("universe");
        // $this->assertEquals($results->getTotal(), 0);

        // // results, structured query
        // $results = $search->retrieve('
        //     <query xmlns="http://marklogic.com/appservices/search">
        //         <term-query>
        //             <text>world</text>
        //         </term-query>
        //     </query>
        // ', array(), true);
        // $this->assertEquals($results->getTotal(), 1);

        // // no results, structured query
        // $results = $search->retrieve('
        //     <query xmlns="http://marklogic.com/appservices/search">
        //         <term-query>
        //             <text>universe</text>
        //         </term-query>
        //     </query>
        // ', array(), true);
        // $this->assertEquals($results->getTotal(), 0);

        /* highlight extension is broken in ML7
        $search = new MLPHP\Search($this->client, 0, 100);
        $results = $search->highlight('<hello>World</hello>', 'text/plain', 'hit', 'world');
        $this->assertEquals('<hello><span class="hit">World</span></hello>', $results);

        $search = new MLPHP\Search($this->client, 0, 100);
        $results = $search->highlight('I like spinach pie<br>', 'text/plain', 'hot', 'liked');
        $this->assertEquals('I <span class="hot">like</span> spinach pie<br>', $results);

        $search = new MLPHP\Search($this->client, 0, 100);
        $results = $search->highlight('<g>I like spinach pie</g>', 'text/xml', 'hot', 'liked');
        $this->assertXmlStringEqualsXmlString('<g>I <span class="hot">like</span> spinach pie</g>', $results);
        */
    }
}

