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
 * Represents a preferred element for a search snippet.
 *
 * @package MLPHP
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
