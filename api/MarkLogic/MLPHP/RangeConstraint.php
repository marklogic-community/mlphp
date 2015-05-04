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
 * Represents a range constraint for search.
 *
 * @package MLPHP
 * @author Mike Wooldridge <mike.wooldridge@marklogic.com>
 */
class RangeConstraint extends AbstractConstraint
{
    private $elem; // @var string
    private $ns; // @var string
    private $attr; // @var string
    private $attrNs; // @var string
    private $datatype; // @var string
    private $facet; // @var bool
    private $buckets; // @var array
    protected $fragmentScope; // @var string
    protected $facetOptions; // @var array

    /**
     * Create a RangeConstraint object.
     *
     * @see http://docs.marklogic.com/search:search#opt-constraint
     *
     * @param string $name Constraint name.
     * @param string $datatype Constraint datatype.
     * @param string $facet Whether constraint should be faceted ('true' or 'false').
     * @param string $elem Name of the element.
     * @param string $ns Element namespace.
     * @param string $attr Attribute name.
     * @param string $attrNs An attribute namespace.
     */
    public function __construct(
        $name, $datatype, $facet = 'true', $elem, $ns = '', $attr = '', $attrNs = ''
    )
    {
        $this->elem = (string)$elem;
        $this->ns = (string)$ns;
        $this->attr = (string)$attr;
        $this->attrNs = (string)$attrNs;
        $this->datatype = (string)$datatype;
        $this->facet = (string)$facet;
        $this->buckets = array();
        parent::__construct($name);
    }

    /**
     * Add bucket objects.
     *
     * @param array|Bucket $buckets An array of bucket objects or a bucket object.
     */
    public function addBuckets($buckets) {
        if (is_array($buckets)) {
            $this->buckets = array_merge($this->buckets, $buckets);
        } else {
            $this->buckets[] = $buckets;
        }
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
        $rangeElem = $dom->createElement('range');
        $rangeElem->setAttribute('type', $this->datatype);
        $rangeElem->setAttribute('facet', $this->facet);
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
        if($this->buckets) {
            foreach ($this->buckets as $buck) {
                $buckElem = $dom->createElement('bucket');
                $buckElem->setAttribute('name', $buck->getName());
                foreach ($buck->getOptions() as $key => $val) {
                    $buckElem->setAttribute($key, $val);
                }
                $rangeElem->appendChild($buckElem);
            }
        }
        // Note: No term options for range constraints
        $this->addFacetOptions($dom, $rangeElem);
        $this->addFragmentScope($dom, $rangeElem);
        $constElem->appendChild($rangeElem);
    /* <constraint name='rating'>
               <range type='xs:decimal'>
                   <element ns='http://example.com' name='rating'/>
               </range>
           </constraint> */
        return $constElem;
    }
}
