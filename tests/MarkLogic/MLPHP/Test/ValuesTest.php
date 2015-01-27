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
class ValuesTest extends TestBase
{
    function testValuesRange()
    {
        parent::$logger->debug('testValuesRange');
        $options1 = new MLPHP\Options(parent::$client);

        $values1 = new MLPHP\Values("myValues1");
        $values1->setUpRange(
          'foo', 'http://example.com/foo', 'bar',
          'http://example.com/bar', 'float'
        );
        $values1->setAggregate('sum');
        $options1->addValues($values1);

        $this->assertXmlStringEqualsXmlString('
          <options xmlns="http://marklogic.com/appservices/search">
              <values name="myValues1">
                  <range type="float">
                      <element ns="http://example.com/foo" name="foo"/>
                      <attribute ns="http://example.com/bar" name="bar"/>
                  </range>
                  <aggregate apply="sum"/>
              </values>
          </options>
        ', $options1->getAsXML());
    }

    function testValuesUri()
    {
        parent::$logger->debug('testValuesUri');
        $options2 = new MLPHP\Options(parent::$client);

        $values2 = new MLPHP\Values("myValues2");
        $values2->setUpUri();
        $values2->setValuesOptions(['limit=10']);
        $options2->addValues($values2);

        $this->assertXmlStringEqualsXmlString('
          <options xmlns="http://marklogic.com/appservices/search">
              <values name="myValues2">
                 <uri/>
                 <values-option>limit=10</values-option>
              </values>
          </options>
        ', $options2->getAsXML());
    }

    function testValuesCollection()
    {
        parent::$logger->debug('testValuesCollection');
        $options3 = new MLPHP\Options(parent::$client);

        $values3 = new MLPHP\Values("myValues3");
        $values3->setUpCollection();
        $values3->setAggregate('sum');
        $values3->setValuesOptions(['limit=10']);
        $options3->addValues($values3);

        $this->assertXmlStringEqualsXmlString('
          <options xmlns="http://marklogic.com/appservices/search">
            <values name="myValues3">
              <collection/>
              <aggregate apply="sum"/>
              <values-option>limit=10</values-option>
            </values>
          </options>
        ', $options3->getAsXML());
    }
}

