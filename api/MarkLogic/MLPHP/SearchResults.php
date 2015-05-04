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
 * Represents search results.
 *
 * @package MLPHP
 * @author Mike Wooldridge <mike.wooldridge@marklogic.com>
 */
class SearchResults
{
    private $total; // @var int
    private $start; // @var int
    private $pageLength; // @var int
    private $qtext; // @var string
    private $query; // @var DOMElement

    private $results = array(); // @var array of SearchResult objects
    private $facets; // @var array of Facet objects

    private $response; // @var XML response

    /**
     * Create a SearchResults object.
     *
     * @todo Index and retrieval of results by index or URI.
     *
     * @param string $response A search response as XML.
     */
    public function __construct($response)
    {
        $this->response = $response;
        $dom = new \DOMDocument();
        $dom->loadXML($response);
        $respElem = $dom->getElementsByTagName('response')->item(0);
        $this->total = $respElem->getAttribute('total');
        $this->start = $respElem->getAttribute('start');
        $this->pageLength = $respElem->getAttribute('page-length');
        $results = $dom->getElementsByTagName('result');
        foreach ($results as $resultElem) {
            $result = new SearchResult($resultElem);
            $this->results[] = $result;
        }
        $facets = $respElem->getElementsByTagName('facet');
        foreach ($facets as $facet) {
            $this->facets[] = new Facet($facet);
        }
        $this->qtext = $respElem->getElementsByTagName('qtext')->length ?
            $respElem->getElementsByTagName('qtext')->item(0)->nodeValue : null;
        $this->query = $respElem->getElementsByTagName('query')->length ?
            $respElem->getElementsByTagName('query')->item(0) : null;
        return;
    }

    /**
     * Get the XML response.
     *
     * @return string The original XML response text.
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Get the search results.
     *
     * @return array An array of search result objects.
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * Get a search result corresponding to a URI.
     *
     * @param string $uri A document URI.
     * @return SearchResult A search result object or null if none found.
     */
    public function getResultByURI($uri)
    {
        $res = null;
        foreach ($this->results as $result) {
            if ($result->getURI() === $uri) {
                $res = $result;
            }
        }
        return $res;
    }

    /**
     * Get a search result corresponding to an index.
     *
     * @param int $index A search result index.
     * @return SearchResult A search result object or null if none found.
     */
    public function getResultByIndex($index)
    {
        $res = null;
        foreach ($this->results as $result) {
            if ($result->getIndex() == $index) {
                $res = $result;
            }
        }
        return $res;
    }

    /**
     * Get the total number of results.
     *
     * @return int The total number of results.
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Get the result starting index.
     *
     * @return int The starting index.
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Get the result ending index.
     *
     * @return int The ending index.
     */
    public function getEnd()
    {
        return min(array(($this->start + $this->pageLength - 1), $this->getTotal()));
    }

    /**
     * Get the current page index (starts at 1).
     *
     * @return int The current page index.
     */
    public function getCurrentPage()
    {
        return ($this->getStart() - 1)/$this->pageLength + 1;
    }

    /**
     * Get the total number of pages.
     *
     * @return int The total number of pages.
     */
    public function getTotalPages()
    {
        return ceil($this->total/$this->pageLength);
    }

    /**
     * Get the page length (number of results in results set).
     *
     * @return int The page length.
     */
    public function getPageLength()
    {
        return $this->pageLength;
    }

    /**
     * Get the previous starting index for paging.
     *
     * @return int The previous starting index.
     */
    public function getPreviousStart()
    {
        return $this->start - $this->pageLength;
    }

    /**
     * Get the next starting index for paging.
     *
     * @return int The next starting index.
     */
    public function getNextStart()
    {
        return $this->start + $this->pageLength;
    }

    /**
     * Check if a results set has one or more facets.
     *
     * @return bool true if one of more facets exist.
     */
    public function hasFacets()
    {
        return !empty($this->facets);
    }

    /**
     * Get the facets for a results set.
     *
     * @return array Array of Facet objects.
     */
    public function getFacets()
    {
        return $this->facets;
    }

    /**
     * Get a facet by its name.
     *
     * @return array Array of Facet objects.
     */
    public function getFacet($name)
    {
        $result = null;
        foreach ($this->facets as $facet) {
            if ($facet->getName() === $name) {
                $result = $facet;
            }
        }
        return $result;
    }

    /**
     * Get the search qtext.
     *
     * @return string The qtext.
     */
    public function getQtext()
    {
        return $this->qtext;
    }

    /**
     * Get the search query.
     *
     * @return DOMElement The search query as an DOMElement object.
     */
    public function getQuery()
    {
        return $this->query;
    }
}
