<?php

/**
 * Represents a roperties constraint for search.
 *
 * @package mlphp\options
 * @author Mike Wooldridge <mike.wooldridge@marklogic.com>
 */
class PropertiesConstraint extends AbstractConstraint
{
    /**
     * Create a PropertiesConstraint object.
     *
     * @see http://docs.marklogic.com/search:search#opt-constraint
     *
     * @param string $name Constraint name.
     */
    public function __construct($name, $facet = 'true')
    {
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
        $propElem = $dom->createElement('properties');
        $constElem->appendChild($propElem);
        /* <constraint name='sample-property-constraint'>
                <properties />
       </constraint> */
        return $constElem;
    }
}