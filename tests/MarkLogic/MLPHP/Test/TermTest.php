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
class TermTest extends TestBase
{
    function testTerm()
    {
        parent::$logger->debug('testTerm');
        $options = new MLPHP\Options(parent::$client);
        $term = new MLPHP\Term("all-results");
        $term->setTermOptions(['unwildcarded']);
        $constraint = new MLPHP\PropertiesConstraint("myProperty");
        $term->setDefault($constraint);
        $options->setTerm($term);

        $this->assertXmlStringEqualsXmlString('
            <options xmlns="http://marklogic.com/appservices/search">
               <term>
                 <empty apply="all-results"/>
                 <term-option>unwildcarded</term-option>
                 <default>
                   <properties/>
                 </default>
               </term>
             </options>
        ', $options->getAsXML());
    }
}

