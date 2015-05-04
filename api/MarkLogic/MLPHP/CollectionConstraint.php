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
 * Represents a collection constraint for search.
 *
 * @package MLPHP
 * @author Mike Wooldridge <mike.wooldridge@marklogic.com>
 */
class CollectionConstraint extends AbstractConstraint
{
    private $prefix; // @var bool
    protected $termOptions; // @var array
    protected $facetOptions; // @var array

    /**
     * Create a CollectionConstraint object.
     *
     * @see http://docs.marklogic.com/search:search#opt-constraint
     *
     * @param string $name Constraint name.
     * @param string $prefix Collection prefix.
     */
    public function __construct($name, $prefix)
    {
        $this->prefix = (string)$prefix;
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
        $collElem = $dom->createElement('collection');
        $collElem->setAttribute('prefix', $this->prefix);
        $this->addTermOptions($dom, $collElem);
        $this->addFacetOptions($dom, $collElem);
        $constElem->appendChild($collElem);
        /* <constraint name='contributor'>
                <collection prefix='http://bbq.com/contributor/'/>
       </constraint> */
        return $constElem;
    }
}
