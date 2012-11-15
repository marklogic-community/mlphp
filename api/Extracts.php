<?php

require_once('QName.php');

/**
 * Represents a set of metadata extracts in a search options node.
 *
 * @package mlphp\options
 * @author Mike Wooldridge <mike.wooldridge@marklogic.com>
 */
class Extracts
{
    private $constraints; // @var array of strings
    private $qnames; // @var array of QName objects

    /**
     * Create an Extracts object.
     *
     * @see http://docs.marklogic.com/search:search#opt-extract-metadata
     */
    public function __construct() {
        $this->constraints = array();
        $this->qnames = array();
    }

    /**
     * Add constraint metadata.
     *
     * @param array|string $constraints An array of constraint names or a constraint name.
     */
    public function addConstraints($constraints) {
        if (is_array($constraints)) {
            $this->constraints = array_merge($this->constraints, $constraints);
        } else {
            $this->constraints[] = (string)$constraints;
        }
    }

    /**
     * Add QName metadata.
     *
     * @param string $element An element name.
     * @param string $ns An element namespace.
     * @param string $attribute An attribute name.
     * @param string $attributeNs An attribute namespace.
     */
    public function addQName($element, $ns = '', $attribute = '', $attributeNs = '') {
        $qname = new QName($element, $ns, $attribute, $attributeNs);
        $this->qnames[] = $qname;
    }

    /**
     * Get the metadata extracts as a DOMElement object.
     *
     * @param DOMDocument $dom The DOMDocument object in which to create the element.
     */
    public function getExtractsAsElem($dom) {
        $extractsElem = $dom->createElement('extract-metadata');
        // constraints
        foreach ($this->constraints as $constraint) {
            $constraintValElem = $dom->createElement('constraint-value');
            $constraintValElem->setAttribute('ref', $constraint);
            $extractsElem->appendChild($constraintValElem);
        }
        // qnames
        foreach ($this->qnames as $qname) {
            $qnameElem = $dom->createElement('qname');
            $qnameElem->setAttribute('elem-name', $qname->getElement());
            $qnameElem->setAttribute('elem-ns', $qname->getNs());
            if($qname->getAttribute() !== '') {
                $qnameElem->setAttribute('attr-name', $qname->getAttribute());
                $qnameElem->setAttribute('attr-ns', $qname->getAttributeNs());
            }
            $extractsElem->appendChild($qnameElem);
        }
        return $extractsElem;
    }
}