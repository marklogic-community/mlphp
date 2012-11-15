<?php

/**
 * Represents a search facet.
 *
 * @package mlphp\search
 * @author Mike Wooldridge <mike.wooldridge@marklogic.com>
 */
class Facet
{
    private $name; // @var string
    private $type; // @var string
    private $facetValues; // @var array

    /**
     * Create a Facet object.
     *
     * @param string $facet The search-result DOMElement for the facet.
     */
    public function __construct($facet)
    {
        $this->name = $facet->getAttribute('name');
        $this->type = $facet->getAttribute('type');
        $values = $facet->getElementsByTagName('facet-value');
        foreach ($values as $val) {
            $facetValue = new FacetValue($val->getAttribute('name'), $val->getAttribute('count'), $val->nodeValue);
            $this->facetValues[] = $facetValue;
        }
    }

    /**
     * Get the facet name.
     *
     * @return string The facet name.
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the facet type.
     *
     * @return string The facet type.
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get the facet values.
     *
     * @return array An array of FacetValue objects.
     */
    public function getFacetValues()
    {
        return $this->facetValues;
    }
}