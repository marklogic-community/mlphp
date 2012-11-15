<?php

require_once('Document.php');

/**
 * Represents a JSON document.
 *
 * @package mlphp\document
 * @author Mike Wooldridge <mike.wooldridge@marklogic.com>
 */
class JSONDocument extends Document
{
    /**
     * Create a JSON document object.
     *
     * @param RESTClient $restClient A REST client object.
     * @param string $uri A document URI.
     */
    public function __construct($restClient, $uri = null)
    {
        parent::__construct($restClient, $uri);
        $this->contentType = 'application/json';
    }

    /**
     * Get the document as a PHP object.
     *
     * @return Object A PHP object.
     */
    public function getAsObject()
    {
        return json_decode($this->getContent(), false);
    }

    /**
     * Get the document as a PHP associative array.
     *
     * @return array An associative array.
     */
    public function getAsArray()
    {
        return json_decode($this->getContent(), true);
    }
}