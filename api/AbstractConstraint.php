<?php

/**
 * Represents a abstraction of a search-options constraint that specific constraints can extend.
 *
 * @package mlphp\options
 * @author Mike Wooldridge <mike.wooldridge@marklogic.com>
 */
class AbstractConstraint
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
}