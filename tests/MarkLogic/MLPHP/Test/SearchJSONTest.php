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
 *
 * Search tests that run on database with JSON content
 */
class SearchJSONTest extends TestBaseSearch
{

    function setUp()
    {
        global $mlphp;
        if (substr($mlphp->config['mlversion'], 0, 3) < 8) {
            $this->markTestSkipped('Test requires MarkLogic 8 or greater');
        }
    }

    function testSimpleJSON()
    {
        // Load docs that are used in tests that follow
        parent::loadDocsJSON(parent::$client);

        parent::$logger->debug('testSimpleJSON');
        $options = new MLPHP\Options(parent::$client, 'simpleJSON');
        $options->write();
        $search = new MLPHP\Search(parent::$client, 1, 3);
        $results = $search->retrieve('Amodei', array('options' => 'simpleJSON'));
        $this->assertEquals(1, $results->getTotal());
    }

    /**
     * @todo Refactor ValueConstraint class to support constraints on JSON content
     */
    // function testValueConstraint()
    // {
    //     parent::$logger->debug('testValueConstraint');
    //     $options = new MLPHP\Options(parent::$client, 'testValueConstraint');
    //     $constraint = new MLPHP\ValueConstraint(
    //         'blah', 'id'
    //     );
    //     $options->addConstraint($constraint)->write();
    //     $search = new MLPHP\Search(parent::$client, 1, 3);
    //     $results = $search->retrieve('blah:NVL000001', array(
    //         'options' => 'testValueConstraint'
    //     ));
    //     $this->assertEquals(1, $results->getTotal());
    // }

    /**
     * @todo Refactor WordConstraint class to support constraints on JSON content
     */
    // function testWordConstraint()
    // {
    //     parent::$logger->debug('testWordConstraint');
    //     $options = new MLPHP\Options(parent::$client, 'testWordConstraint');
    //     $constraint = new MLPHP\WordConstraint(
    //         'foo', 'address'
    //     );
    //     $options->addConstraint($constraint)->write();
    //     $search = new MLPHP\Search(parent::$client, 1, 3);
    //     $results = $search->retrieve('foo:"Carson City"', array(
    //         'options' => 'testWordConstraint'
    //     ));
    //     $this->assertEquals(1, $results->getTotal());
    // }

    // Misc tests, relevant?
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

