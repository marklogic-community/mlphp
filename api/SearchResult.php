<?php

require_once ('Match.php');

/**
 * Represents a search result.
 *
 * @package mlphp\search
 * @author Mike Wooldridge <mike.wooldridge@marklogic.com>
 */
class SearchResult
{
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
        foreach ($metadata as $meta) {
            $key = $meta->getAttribute('name');
            $val = $meta->nodeValue;
            $this->metadata[$key] = $val;
        }
        $similar = $result->getElementsByTagName('similar');
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
     * Get an arbitrary metadata value based on its key.
     *
     * @todo Handle multiple instances of metadata (e.g., keywords).
     *
     * @param mixed $key The key for the metadata value.
     * @return mixed The metadata value.
     */
    public function getMetadata($key)
    {
        return (isset($this->metadata[$key])) ? $this->metadata[$key] : null;
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