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
class FieldTest extends TestBase
{
    function testField()
    {
        parent::$logger->debug('testField');
        $fieldPath = new MLPHP\FieldPath(['path' => 'myPath', 'weight' => 1.5]);
        $included = new MLPHP\FieldElementIncluded([
            'localname' => 'foo',
            'attribute-localname' => 'bar',
            'attribute-value' => 'baz',
            'namespace-uri' => 'http://example.com/foo',
            'attribute-namespace-uri' => 'http://example.com/bar',
            'weight' => 1.7
        ]);
        $excluded = new MLPHP\FieldElementExcluded([
            'localname' => 'foo',
            'attribute-localname' => 'bar',
            'attribute-value' => 'baz',
            'namespace-uri' => 'http://example.com/foo',
            'attribute-namespace-uri' => 'http://example.com/bar'
        ]);
        $arr = [
            'field-name' => 'myField',
            'field-path' => $fieldPath,
            'included-element' => $included,
            'excluded-element' => $excluded
        ];
        $field = new MLPHP\Field($arr);
        $this->assertJsonStringEqualsJsonString(json_encode($field), '
          {
            "field-name":"myField",
            "field-path":[{
              "path":"myPath",
              "weight":1.5
            }],
            "included-element":[{
              "localname":"foo",
              "attribute-localname":"bar",
              "attribute-value":"baz",
              "namespace-uri":"http://example.com/foo",
              "attribute-namespace-uri":"http://example.com/bar",
              "weight":1.7
            }],
            "excluded-element":[{
              "localname":"foo",
              "attribute-localname":"bar",
              "attribute-value":"baz",
              "namespace-uri":"http://example.com/foo",
              "attribute-namespace-uri":"http://example.com/bar"
            }]
          }
        ');
    }
}

