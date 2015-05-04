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
class ExtractsTest extends TestBase
{
    function testExtracts()
    {
        parent::$logger->debug('testExtracts');
        $options = new MLPHP\Options(parent::$client);
        $extracts = new MLPHP\Extracts();
        $extracts->addConstraints('foo');
        $extracts->addQName(
            'bar',
            'http://example.com/bar',
            'baz',
            'http://example.com/baz'
        );
        $options->setExtracts($extracts);

        $this->assertXmlStringEqualsXmlString('
          <options xmlns="http://marklogic.com/appservices/search">
            <extract-metadata>
              <constraint-value ref="foo"/>
              <qname elem-name="bar" elem-ns="http://example.com/bar"
                attr-name="baz" attr-ns="http://example.com/baz"/>
            </extract-metadata>
          </options>
        ', $options->getAsXML());

    }
}

