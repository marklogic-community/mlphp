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
 * Represents an element-query constraint for search.
 *
 * @todo element-query deprecated, use container constraint
 * http://docs.marklogic.com/guide/rest-dev/appendixb#id_96729
 *
 * @package MLPHP
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
