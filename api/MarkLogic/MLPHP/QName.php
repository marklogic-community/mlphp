<?php
/*
Copyright 2002-2012 MarkLogic Corporation.  All Rights Reserved.

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

     http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
*/
namespace MarkLogic\MLPHP;

/**
 * Represents a QName metadata option.
 *
 * @package MLPHP
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
