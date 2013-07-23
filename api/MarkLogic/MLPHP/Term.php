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
 * Represents a term element in search options.
 *
 * @package MLPHP
 * @author Mike Wooldridge <mike.wooldridge@marklogic.com>
 * @see http://docs.marklogic.com/guide/rest-dev/appendixb#id_70754
 * @todo Handle term options, additional default behavior, etc.
 */
class Term
{
    private $empty; // @var string
    private $termOptions; // @var array
    private $default; // @var Constraint

    /**
     * Create a Term object.
     *
     * @param string $empty Setting for resolving empty searches (example: 'no-results').
     */
    public function __construct($empty = '')
    {
        $this->empty = (string)$empty;
    }

    /**
     * Set the empty setting.
     *
     * @param string $empty Setting for resolving empty searches (example: 'no-results').
     */
    public function setEmpty($empty)
    {
        $this->empty = (string)$empty;
    }

    /**
     * Set term options.
     *
     * @param array $termOptions An array of term options.
     */
    public function setTermOptions($termOptions)
    {
        $this->termOptions = $termOptions;
    }

    /**
     * Set the default constraint
     *
     * @param Constraint $constraint for default term processing
     */
    public function setDefault($constraint)
    {
        $this->default = $constraint;
    }

    /**
     * Get the term as a DOMElement object.
     *
     * @param DOMDocument $dom The DOMDocument object with which to create the element.
     */
    public function getAsElem($dom)
    {
        $termElem = $dom->createElement('term');
        $emptyElem = $dom->createElement('empty');
        $emptyElem->setAttribute('apply', $this->empty);
        $termElem->appendChild($emptyElem);
        if (!empty($this->termOptions)) {
            foreach($this->termOptions as $opt) {
                $termOptElem = $dom->createElement('term-option');
                $termOptElem->nodeValue = $opt;
                $termElem->appendChild($termOptElem);
            }
        }
        if (!empty($this->default)) {
            $constraint = $this->default->getAsElem($dom);
            /* pluck out child nodes of the <constraint/> */
            if ($constraint->hasChildNodes()) {
                $defElem = $dom->createElement('default');
                foreach ($constraint->childNodes as $child) {
                    if ($child->nodeType == XML_ELEMENT_NODE) {
                        $defElem->appendChild($child);
                    }
                }
                $termElem->appendChild($defElem);
            }
        }
        /* <term>
               <empty apply="no-results" />
               <term-option>diacritic-insensitive</term-option>
               <term-option>unwildcarded</term-option>
               <default>....</default>
            </term> */
        return $termElem;
    }
}
