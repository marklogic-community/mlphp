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
 * Represents a search result.
 *
 * @package MLPHP
 * @author Mike Wooldridge <mike.wooldridge@marklogic.com>
 */
class SearchResult
{
    private $result; // @var DOMElement
    private $index; // @var int
    private $uri; // @var string
    private $path; // @var string
    private $score; // @var int
    private $confidence; // @var int
    private $fitness; // @var int

    private $matches; // @var array
    private $metadata; // @var array
    private $similar; // @var array

    /**
     * Create a SearchResult object.
     *
     * @param DOMElement A result as a DOMElement object.
     */
    public function __construct($result)
    {
        $this->result = $result;
        $this->index = $result->getAttribute('index');
        $this->uri = $result->getAttribute('uri');
        $this->path = $result->getAttribute('path');
        $this->score = $result->getAttribute('score');
        $this->confidence = $result->getAttribute('confidence');
        $this->fitness = $result->getAttribute('fitness');
        $matches = $result->getElementsByTagName('match');
        foreach ($matches as $m) {
            $match = new Match($m);
            $this->matches[] = $match;
        }
        $metadata = $result->getElementsByTagName('constraint-meta');
        $this->metadata = array();
        foreach ($metadata as $meta) {
            $name = $meta->getAttribute('name');
            $val = $meta->nodeValue;
            if (array_key_exists($name, $this->metadata)) {
                $this->metadata[$name][] = $val;
            } else {
                $this->metadata[$name] = [$val];
            }

        }
        $similar = $result->getElementsByTagName('similar');
        $this->similar = array();
        foreach ($similar as $sim) {
            $this->similar[] = $sim->nodeValue;
        }
    }

    /**
     * Get the result index.
     *
     * @return int The result index.
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * Get the result URI.
     *
     * @return string The result URI.
     */
    public function getURI()
    {
        return $this->uri;
    }

    /**
     * Get the result path.
     *
     * @return string The result path.
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Get the result score.
     *
     * @return int The result score.
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * Get the result confidence.
     *
     * @return int The result confidence.
     */
    public function getConfidence()
    {
        return $this->confidence;
    }

    /**
     * Get the result fitness.
     *
     * @return int The result fitness.
     */
    public function getFitness()
    {
        return $this->fitness;
    }

    /**
     * Get matches.
     *
     * @return array Array of Match objects respresenting snippet.
     */
    public function getMatches()
    {
        return $this->matches;
    }

    /**
     * Get an arbitrary metadata array based on its key. A metadata key can be
     * associated with multiple metadata values in a search result (e.g.,
     * keywords) which is why the values are stored as arrays.
     *
     * @param mixed $key The key for the metadata value(s).
     * @return array The array of metadata value(s) associated with the key.
     */
    public function getMetadata($key)
    {
        return (isset($this->metadata[$key])) ? $this->metadata[$key] : null;
    }

    /**
     * Get an array metadata value(s) based on a qname element and namespace.
     *
     * @param mixed $elem The qname element name.
     * @param mixed $ns The qname namespace (optional).
     * @return array The array of metadata value(s) associated with the qname.
     */
    public function getMetadataQName($elem, $ns = '')
    {
        $result = array();
        // with optional namespace
        if ($ns) {
            foreach ($this->result->getElementsByTagNameNS($ns, $elem) as $e) {
                $val = $e->nodeValue;
                $result[] = $val;
            }
        }
        // without optional namespace
        else {
            foreach ($this->result->getElementsByTagName($elem) as $e) {
                $val = $e->nodeValue;
                $result[] = $val;
            }
        }
        return (count($result) > 0) ? $result  : null;
    }

    /**
     * Get all available metadata keys.
     *
     * @return array An array of metadata keys.
     */
    public function getMetadataKeys()
    {
        return (isset($this->metadata)) ? array_keys($this->metadata) : array();
    }

    /**
     * Get similar documents.
     *
     * @return array Array of document URIs.
     */
    public function getSimilar()
    {
        return $this->similar;
    }
}
