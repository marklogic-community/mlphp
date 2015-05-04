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
class RangeConstraintTest extends TestBase
{
    function testRangeElementConstraint()
    {
        parent::$logger->debug('testRangeElementConstraint');
        $options1 = new MLPHP\Options(parent::$client);
        $constraint1 = new MLPHP\RangeConstraint(
          'myConstr1', 'float', 'false', 'foo', 'http://example.com/foo'
        );
        $options1->addConstraint($constraint1);

        $this->assertXmlStringEqualsXmlString('
            <options xmlns="http://marklogic.com/appservices/search">
              <constraint name="myConstr1">
               <range type="float" facet="false">
                   <element ns="http://example.com/foo" name="foo"/>
               </range>
              </constraint>
            </options>
        ', $options1->getAsXML());

    }

    function testRangeAttributeConstraint()
    {
        parent::$logger->debug('testRangeAttributeConstraint');
        $options2 = new MLPHP\Options(parent::$client);
        $constraint2 = new MLPHP\RangeConstraint(
          'myConstr2', 'unsignedInt', 'true', 'foo',
          'http://example.com/foo', 'barAttr', 'http://example.com/bar'
        );
        $options2->addConstraint($constraint2);

        $this->assertXmlStringEqualsXmlString('
            <options xmlns="http://marklogic.com/appservices/search">
              <constraint name="myConstr2">
               <range type="unsignedInt" facet="true">
                   <element ns="http://example.com/foo" name="foo"/>
                   <attribute ns="http://example.com/bar" name="barAttr"/>
               </range>
              </constraint>
            </options>
        ', $options2->getAsXML());

    }

    function testRangeBucketConstraint()
    {
        parent::$logger->debug('testRangeBucketConstraint');
        $options3 = new MLPHP\Options(parent::$client);
        $constraint3 = new MLPHP\RangeConstraint(
          'myConstr3', 'string', 'true', 'foo'
        );
        $buck1 = new MLPHP\Bucket('low', array(
            'lt' => 10
        ));
        $buck2 = new MLPHP\Bucket('high', array(
            'ge' => 10,
            'lt' => 20
        ));
        $constraint3->addBuckets(array($buck1, $buck2));
        $options3->addConstraint($constraint3);
        $this->assertXmlStringEqualsXmlString('
            <options xmlns="http://marklogic.com/appservices/search">
              <constraint name="myConstr3">
               <range type="string" facet="true">
                    <element ns="" name="foo"/>
                    <bucket name="low" lt="10" />
                    <bucket name="high" ge="10" lt="20" />
               </range>
              </constraint>
            </options>
        ', $options3->getAsXML());

    }
}

