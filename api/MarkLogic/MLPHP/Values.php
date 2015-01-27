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
 * Represents a values setting in a search options node.
 *
 * @package MLPHP
 * @author Mike Wooldridge <mike.wooldridge@marklogic.com>
 */
class Values
{
    private $name; // @var string
    private $type; // @var string
    private $elem; // @var string
    private $ns; // @var string
    private $attr; // @var string
    private $attrNs; // @var string
    private $field; // @var string
    private $index; // @var string
    private $datatype; // @var string

    private $aggregate; // @var string

    private $valuesOptions; // @var array

    /**
     * Create a Values object.
     *
     * @see http://docs.marklogic.com/guide/rest-dev/search#id_53938
     * @see http://docs.marklogic.com/search:search?q=search:search#opt-values
     * @see http://docs.marklogic.com/guide/admin/range_index
     *
     * @param string $name Values name.
     */
    public function __construct($name)
    {
        $this->name = (string)$name;
    }

    /**
     * Set up range values.
     *
     * @param string $elem Name of the element.
     * @param string $ns Element namespace.
     * @param string $attr Attribute name.
     * @param string $attrNs An attribute namespace.
     * @param string $datatype Constraint datatype.
     */
    public function setUpRange($elem, $ns = '', $attr = '', $attrNs = '', $datatype = 'string')
    {
        $this->type = 'range';
        $this->elem = (string)$elem;
        $this->ns = (string)$ns;
        $this->attr = (string)$attr;
        $this->attrNs = (string)$attrNs;
        $this->datatype = (string)$datatype;
    }

    /**
     * Set up uri values.
     */
    public function setUpUri()
    {
        $this->type = 'uri';
    }

    /**
     * Set up collection values.
     */
    public function setUpCollection()
    {
        $this->type = 'collection';
    }

    /**
     * Set values options.
     *
     * @param array $valuesOptions An array of values options.
     */
    public function setValuesOptions($valuesOptions)
    {
        $this->valuesOptions = $valuesOptions;
    }

    /**
     * Set aggregate operation to apply.
     *
     * @param string $aggregate Aggregate operation to apply.
     */
    public function setAggregate($aggregate)
    {
        $this->aggregate = $aggregate;
    }

    /**
     * Get the values setting as a DOMElement object.
     *
     * @param DOMDocument $dom The DOMDocument object in which to create the element.
     */
    public function getValuesAsElem($dom)
    {
        $valsElem = $dom->createElement('values');
        $valsElem->setAttribute('name', $this->name);

        switch ($this->type) {
            case 'range':
                $rangeElem = $dom->createElement('range');
                $rangeElem->setAttribute('type', $this->datatype);
                $elemElem = $dom->createElement('element');
                $elemElem->setAttribute('ns', $this->ns);
                $elemElem->setAttribute('name', $this->elem);
                $rangeElem->appendChild($elemElem);
                if($this->attr) {
                    $attrElem = $dom->createElement('attribute');
                    $attrElem->setAttribute('ns', $this->attrNs);
                    $attrElem->setAttribute('name', $this->attr);
                    $rangeElem->appendChild($attrElem);
                }
                $valsElem->appendChild($rangeElem);
                break;
                /* <values name='example'>
                    <range type='xs:integer'>
                        <element ns='' name='game'/>
                        <attribute ns='' name='runs'/>
                    </range>
                    <aggregate apply='sum'/>
                </values> */
            case 'uri':
                $uriElem = $dom->createElement('uri');
                $valsElem->appendChild($uriElem);

                break;
               /* <values name='uri'>
                      <uri/>
                      <values-option>limit=10</values-option>
                    </values> */
            case 'collection':
                $collectionElem = $dom->createElement('collection');
                $valsElem->appendChild($collectionElem);
                break;
               /* <values name="coll">
                      <collection/>
                      <values-option>limit=10</values-option>
                    </values> */
        }
        $this->addAggregate($dom, $valsElem);
        $this->addValuesOptions($dom, $valsElem);
        return $valsElem;
    }

    /**
     * Add values options to an element.
     *
     * @param DOMDocument $dom A DOM document.
     * @param DOMElement $elem A DOM element.
     * @return DOMElement The updated element.
     */
    private function addValuesOptions($dom, $elem)
    {
        if (!empty($this->valuesOptions)) {
            foreach($this->valuesOptions as $opt) {
                $valuesElem = $dom->createElement('values-option');
                $valuesElem->nodeValue = $opt;
                $elem->appendChild($valuesElem);
            }
        }
        return $elem;
    }

    /**
     * Add aggregate setting to an element.
     *
     * @param DOMDocument $dom A DOM document.
     * @param DOMElement $elem A DOM element.
     * @return DOMElement The updated element.
     */
    private function addAggregate($dom, $elem)
    {
        if(!empty($this->aggregate)) {
            $aggregateElem = $dom->createElement('aggregate');
            $aggregateElem->setAttribute('apply', $this->aggregate);
            $elem->appendChild($aggregateElem);
        }
        return $elem;
    }
}
