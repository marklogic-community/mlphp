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
 * Represents a abstraction of a search-options constraint that specific constraints can extend.
 *
 * @package MLPHP
 * @author Mike Wooldridge <mike.wooldridge@marklogic.com>
 */
abstract class AbstractConstraint implements Constraint
{
    protected $name; // @var string

    /**
     * Create a Constraint object.
     *
     * @see http://docs.marklogic.com/search:search#opt-constraint
     *
     * @param string $name Constraint name.
     */
    public function __construct($name)
    {
        $this->name = (string)$name;
    }

    /**
     * Set term options.
     *
     * @param array $termOptions An array of term options.
     */
    public function setTermOptions($termOptions)
    {
        $this->termOptions = $termOptions;
    }

    /**
     * Set facet options.
     *
     * @param array $facetOptions An array of facet options.
     */
    public function setFacetOptions($facetOptions)
    {
        $this->facetOptions = $facetOptions;
    }

    /**
     * Set fragment scope.
     *
     * @param string $scope The fragment scope ('documents' or 'properties').
     */
    public function setFragmentScrope($scope)
    {
        $this->fragmentScope = $scope;
    }

    /**
     * Add term options to an element.
     *
     * @param DOMDocument $dom A DOM document.
     * @param DOMElement $elem A DOM element.
     * @return DOMElement The updated element.
     */
    protected function addTermOptions($dom, $elem)
    {
        if (!empty($this->termOptions)) {
            foreach ($this->termOptions as $opt) {
                $termElem = $dom->createElement('term-option');
                $termElem->nodeValue = $opt;
                $elem->appendChild($termElem);
            }
        }
        return $elem;
    }

    /**
     * Add facet options to an element.
     *
     * @param DOMDocument $dom A DOM document.
     * @param DOMElement $elem A DOM element.
     * @return DOMElement The updated element.
     */
    protected function addFacetOptions($dom, $elem)
    {
        if (!empty($this->facetOptions)) {
            foreach ($this->facetOptions as $opt) {
                $facetElem = $dom->createElement('facet-option');
                $facetElem->nodeValue = $opt;
                $elem->appendChild($facetElem);
            }
        }
        return $elem;
    }

    /**
     * Add a fragment-scope setting to an element.
     *
     * @param DOMDocument $dom A DOM document.
     * @param DOMElement $elem A DOM element.
     * @return DOMElement The updated element.
     */
    protected function addFragmentScope($dom, $elem)
    {
        if (!empty($this->fragmentScope)) {
            $fragScopeElem = $dom->createElement('fragment-scope');
            $fragScopeElem->nodeValue = $this->fragmentScope;
            $elem->appendChild($fragScopeElem);
        }
        return $elem;
    }

    /**
     * Get the constraint as a DOMElement object.
     *
     * @param DOMDocument $dom The DOMDocument object with which to create the element.
     */
    abstract function getAsElem($dom);
}
