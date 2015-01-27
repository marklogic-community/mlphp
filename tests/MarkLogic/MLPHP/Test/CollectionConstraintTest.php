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
class CollectionConstraintTest extends TestBase
{
    function testCollectionConstraint()
    {
        parent::$logger->debug('testCollectionConstraint');
        $options = new MLPHP\Options(parent::$client);
        $constraint = new MLPHP\CollectionConstraint("cat", "category/");
        $options->addConstraint($constraint);

        $this->assertXmlStringEqualsXmlString('
            <options xmlns="http://marklogic.com/appservices/search">
              <constraint name="cat">
                <collection prefix="category/"/>
              </constraint>
            </options>
        ', $options->getAsXML());

    }
}

