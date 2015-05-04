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
 * Represents an XML document.
 *
 * @package MLPHP
 * @author Mike Wooldridge <mike.wooldridge@marklogic.com>
 */
class XMLDocument extends Document
{
    private $dom; // @var DOMDocument object

    /**
     * Create an XML document object.
     *
     * @param RESTClient $client A REST client object.
     * @param string $uri A document URI.
     */
    public function __construct($client, $uri = null)
    {
        parent::__construct($client, $uri);
        $this->dom = new \DOMDocument();
        $this->contentType = 'application/xml';
    }

    /**
     * Read an XML document from the database.
     *
     * @param string $uri A document URI.
     * @param array $params Optional additional parameters to pass when reading.
     * @return string The document content.
     */
    public function read($uri = null, $params = array())
    {
        $this->uri = (isset($uri)) ? (string)$uri : $this->uri;
        $params = array_merge(array('format' => 'xml'), $params);
        return parent::read($this->uri, $params);
    }

    /**
     * Write an XML document to the database.
     *
     * @param string $uri A document URI.
     * @param array $params Optional additional parameters to pass when writing.
     * @return Document $this
     */
    public function write($uri = null, $params = array())
    {
        if ($this->isValidXML($this->getContent())) {
            $this->uri = (isset($uri)) ? (string)$uri : $this->uri;
            $params = array_merge(array('format' => 'xml'), $params);
            return parent::write($this->uri, $params);
        } else {
            throw new \Exception('Attempting to write invalid XML content');
        }
    }

    /**
     * Check if XML content is valid.
     *
     * @return boolean true or false.
     */
    public function isValidXML($xml)
    {
        $doc = new \DOMDocument();
        try {
            return $doc->loadXML($xml) === true;
        } catch(\Exception $e) {
            $this->logger->warning('XMLDocument::isValidXML() - ' . $e->getMessage());
            return false;
        }
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
