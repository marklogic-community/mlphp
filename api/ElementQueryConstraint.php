<?php

/**
 * Represents an element-query constraint for search.
 *
 * @package mlphp\options
 * @author Mike Wooldridge <mike.wooldridge@marklogic.com>
 */
class ElementQueryConstraint extends AbstractConstraint
{
    private $elem; // @var string
    private $ns; // @var string

    /**
     * Create a ElementQueryConstraint object.
     *
     * @see http://docs.marklogic.com/search:search#opt-constraint
     *
     * @param string $name Constraint name.
     * @param string $elem Element name.
     * @param string $ns Element namespace.
     */
    public function __construct($name, $elem, $ns = '')
    {
        $this->elem = (string)$elem;
      $this->ns = (string)$ns;
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
        $eqElem = $dom->createElement('element-query');
        $eqElem->setAttribute('ns', $this->ns);
        $eqElem->setAttribute('name', $this->elem);
        $constElem->appendChild($eqElem);
        /* <constraint name='sample-element-constraint'>
              <element-query name='title' ns='http://my/namespace' />
         </constraint> */
        return $constElem;
    }
}