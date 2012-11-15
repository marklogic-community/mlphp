<?php

/**
 * Represents results transformation option.
 *
 * @package mlphp\options
 * @author Mike Wooldridge <mike.wooldridge@marklogic.com>
 * @see http://docs.marklogic.com/guide/rest-dev/appendixb#id_29258
 * @todo Handle custom snippetting.
 */
class TransformResults
{
    private $apply; // @var string
    private $preferredElements; // @var array

    /**
     * Create a TransformResults object.
     *
     * @param string $apply A type of transformation to apply.
     */
    public function __construct($apply)
    {
        $this->apply = $apply;
        $this->preferredElements = array();
    }

    /**
     * Add one or more preferred elements for snippetting.
     *
     * @param array|string $elements An array of preferred-element objects or a single preferred-element object.
     */
    public function addPreferredElements($elements)
    {
        if (is_array($elements)) {
            $this->preferredElements = array_merge($this->preferredElements, $elements);
        } else {
            $this->preferredElements[] = $elements;
        }
    }

    /**
     * Get the type of transformation to apply.
     *
     * @return string The type of transformation to apply.
     */
    public function getApply()
    {
        return $this->apply;
    }

    /**
     * Set the type of transformation to apply.
     *
     * @param string $apply The type of transformation to apply.
     */
    public function setApply($apply)
    {
        $this->apply = (string)$apply;
    }

    /**
     * Get the results transformation settings as a DOMElement object.
     *
     * @param DOMDocument $dom The DOMDocument object in which to create the element.
     */
    public function getTransformResultsAsElem($dom)
    {
        $transElem = $dom->createElement('transform-results');
        $transElem->setAttribute('apply', $this->apply);
        $prefElem = $dom->createElement('preferred-elements');
        // preferred elements
        foreach ($this->preferredElements as $elem) {
            $element = $dom->createElement('element');
            $element->setAttribute('name', $elem->getElement());
            $element->setAttribute('ns', $elem->getElementNs());
            $prefElem->appendChild($element);
        }
        $transElem->appendChild($prefElem);
        return $transElem;
    }
}