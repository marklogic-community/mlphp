<?php

/**
 * Represents a QName metadata option.
 *
 * @package mlphp\options
 * @author Mike Wooldridge <mike.wooldridge@marklogic.com>
 */
class QName
{
    private $element; // @var string
    private $ns; // @var string
    private $attribute; // @var string
    private $attributeNs; // @var string

    /**
     * Create a QName object.
     *
     * @param string $element The element name.
     * @param string $ns The element namespace.
     * @param string $attribute The attribute name.
     * @param string $attributeNs The attribute namespace.
     */
    public function __construct($element, $ns = '', $attribute = '', $attributeNs = '')
    {
        $this->element = (string)$element;
        $this->ns = (string)$ns;
        $this->attribute = (string)$attribute;
        $this->attributeNs = (string)$attributeNs;
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
     * Get the element namespace.
     *
     * @return string The element namespace.
     */
    public function getNs()
    {
        return $this->ns;
    }

    /**
     * Get the attribute name.
     *
     * @return string The attribute name.
     */
    public function getAttribute()
    {
        return $this->attribute;
    }

    /**
     * Get the attribute namespace.
     *
     * @return string The attribute namespace.
     */
    public function getAttributeNs()
    {
        return $this->attributeNs;
    }
}