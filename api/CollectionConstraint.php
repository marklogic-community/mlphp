<?php

/**
 * Represents a collection constraint for search.
 *
 * @package mlphp\options
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