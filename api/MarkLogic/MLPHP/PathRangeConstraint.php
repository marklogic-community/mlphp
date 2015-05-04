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
 * Represents path range constraint for search.
 *
 * @package MLPHP
 * @author Mike Wooldridge <mike.wooldridge@marklogic.com>
 */
class PathRangeConstraint extends AbstractConstraint
{
    private $path; // @var string
    private $datatype; // @var string
    private $facet; // @var bool
    private $ns; // @var string

    /**
     * Create a PathRangeConstraint object.
     *
     * @see http://docs.marklogic.com/search:search#opt-constraint
     *
     * @param string $name Constraint name.
     * @param string $datatype Constraint datatype.
     * @param string $facet Whether constraint should be faceted ('true' or 'false').
     * @param string $elem Path expression (e.g., '/publication/my:meta/my:year').
     */
    public function __construct($name, $datatype, $facet = 'true', $path)
    {
        $this->path = (string)$path;
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
        $prangeElem = $dom->createElement('range');
        $prangeElem->setAttribute('type', $this->datatype);
        $prangeElem->setAttribute('facet', $this->facet);
        if ($this->datatype === 'string') {
            $prangeElem->setAttribute('collation', 'http://marklogic.com/collation/');
        }
        $pathElem = $dom->createElement('path-index');
        $pathElem->appendChild(new \DOMText($this->path));
        $prangeElem->appendChild($pathElem);
        $this->addFacetOptions($dom, $prangeElem);
        $this->addFragmentScope($dom, $prangeElem);
        $constElem->appendChild($prangeElem);
        /* <constraint name="year">
             <range type="xs:gYear" facet="true">
               <path-index xmlns:my="http://example.com">
                 /publication/my:meta/my:year
               </path-index>
               <!--uses same options structure, options as element,
                   element-attribute or field based range constraints -->
             </range>
           </constraint> */
        return $constElem;
    }
}
