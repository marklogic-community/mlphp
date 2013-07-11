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
 * Represents results transformation option.
 *
 * @package MLPHP
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
