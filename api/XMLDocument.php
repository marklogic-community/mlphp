<?php

require_once('Document.php');

/**
 * Represents an XML document.
 *
 * @package mlphp\document
 * @author Mike Wooldridge <mike.wooldridge@marklogic.com>
 */
class XMLDocument extends Document
{
    private $dom; // @var DOMDocument object

    /**
     * Create an XML document object.
     *
     * @param RESTClient $restClient A REST client object.
     * @param string $uri A document URI.
     */
    public function __construct($restClient, $uri = null)
    {
        parent::__construct($restClient, $uri);
        $this->dom = new DOMDocument();
        $this->contentType = 'application/xml';
    }

    /**
     * Get the document as a DOMDocument object.
     *
     * @return DOMDocument|null A DOMDocument object.
     */
    public function getAsDOMDocument()
    {
        return $this->dom->loadXML($this->getContent());
    }

}