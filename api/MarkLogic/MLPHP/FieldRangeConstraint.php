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
 * Represents a field range constraint for search.
 *
 * @package MLPHP
 * @author Mike Wooldridge <mike.wooldridge@marklogic.com>
 */
class FieldRangeConstraint extends AbstractConstraint
{
    private $field; // @var string
    private $datatype; // @var string
    private $facet; // @var bool
    protected $fragmentScope; // @var string
    protected $facetOptions; // @var array

    /**
     * Create a FieldRangeConstraint object.
     *
     * @see http://docs.marklogic.com/search:search#opt-constraint
     *
     * @param string $name Constraint name.
     * @param string $datatype Constraint datatype.
     * @param string $facet Whether constraint should be faceted ('true' or 'false').
     * @param string $field Field name.
     */
    public function __construct($name, $datatype, $facet = 'true', $field)
    {
        $this->field = (string)$field;
        $this->datatype = (string)$datatype;
        $this->facet = (string)$facet;
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
        $frangeElem = $dom->createElement('range');
        $frangeElem->setAttribute('type', $this->datatype);
        $frangeElem->setAttribute('facet', $this->facet);
        if ($this->datatype === 'xs:string') {
            $frangeElem->setAttribute('collation', 'http://marklogic.com/collation/');
        }
        $fieldElem = $dom->createElement('field');
        $fieldElem->setAttribute('name', $this->field);
        $frangeElem->appendChild($fieldElem);
        $this->addFacetOptions($dom, $frangeElem);
        $this->addFragmentScope($dom, $frangeElem);
        $constElem->appendChild($frangeElem);
        /* <constraint name='name'>
                 <range type='string' collation='http://marklogic.com/collation/'>
                    <field name='my-field-name'/>
                 </range>
            </constraint> */
        return $constElem;
    }
}
