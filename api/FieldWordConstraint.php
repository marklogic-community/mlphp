<?php

/**
 * Represents a field word constraint for search.
 *
 * @package mlphp\options
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