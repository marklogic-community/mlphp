<?php

/**
 * Represents a preferred element for a search snippet.
 *
 * @package mlphp\options
 * @author Mike Wooldridge <mike.wooldridge@marklogic.com>
 */
class PreferredElement
{
    private $element; // @var string
    private $elementNs; // @var string

    /**
     * Create a PreferredElement object.
     *
     * @param string $element An element name.
     * @param string $elementNs An element namespace.
     */
    public function __construct($element, $elementNs = '')
    {
        $this->element = $element;
        $this->elementNs = $elementNs;
    }

    /**
     * Get the element name.
     *
     * @return string The element name.
     */
    public function getElement()
    {
        return $this->element;
    }

    /**
     * Set the element name.
     *
     * @param string $element The element name.
     */
    public function setElement($element)
    {
        $this->element = (string)$element;
    }

    /**
     * Get the element namespace.
     *
     * @return string The element namespace.
     */
    public function getElementNs()
    {
        return $this->elementNs;
    }

    /**
     * Set the element namespace.
     *
     * @param string $elementNs The element namespace.
     */
    public function setElementNs($elementNs)
    {
        $this->elementNs = (string)$elementNs;
    }
}