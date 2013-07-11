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
namespace MarkLogic\MLPHP;

/**
 * Represents a field word constraint for search.
 *
 * @package MLPHP
 * @author Mike Wooldridge <mike.wooldridge@marklogic.com>
 */
class FieldWordConstraint extends AbstractConstraint
{
    private $field; // @var string
    protected $fragmentScope; // @var string
    protected $termOptions; // @var array

    /**
     * Create a FieldWordConstraint object.
     *
     * @see http://docs.marklogic.com/search:search#opt-constraint
     *
     * @param string $name Constraint name.
     * @param string $field Field name.
     */
    public function __construct($name, $field)
    {
        $this->field = (string)$field;
        parent::__construct($name);
    }

    /**
     * Get the constraint as a DOMElement object.
     *
     * @param DOMDocument $dom The DOMDocument object with which to create the element.
     */
    public function getAsElem($dom)
    {
        $constElem = $dom->createElement('constraint');
        $constElem->setAttribute('name', $this->name);
        $fwordElem = $dom->createElement('word');
        $fieldElem = $dom->createElement('field');
        $fieldElem->setAttribute('name', $this->field);
        $fwordElem->appendChild($fieldElem);
        $this->addTermOptions($dom, $fwordElem);
        $constElem->appendChild($fwordElem);
        /* <constraint name='summary'>
                <word>
                  <field name='summary'/>
                </word>
           </constraint> */
        return $constElem;
    }
}
