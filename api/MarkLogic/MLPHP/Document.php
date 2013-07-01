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

require_once 'Metadata.php';
require_once 'RESTRequest.php';

/**
 * Represents a document.
 *
 * @package Documents
 * @author Mike Wooldridge <mike.wooldridge@marklogic.com>
 */
class Document
{
    private $uri; // @var string
    private $content; // @var string
    private $contentType; // @var string
    private $restClient; // @var RESTClient

    /**
     * Create a Document object.
     *
     * @param RESTClient $restClient A REST client object.
     * @param string $uri A document URI.
     */
    public function __construct($restClient, $uri = null)
    {
        $this->restClient = $restClient;
        $this->uri = (string)$uri;
    }

    /**
     * Read a document from the database.
     *
     * @see Document::getContent()
     *
     * @param string $uri A document URI.
     * @param array $params Optional additional parameters to pass when reading.
     * @return string The document content.
     */
    public function read($uri = null, $params = array())
    {
        $this->uri = (isset($uri)) ? (string)$uri : $this->uri;
        try {
            $params = array_merge(array('uri' => $this->uri), $params);
            $request = new RESTRequest('GET', 'documents', $params);
            $response = $this->restClient->send($request);
            $this->content = $response->getBody();
            $this->contentType = $response->getContentType();
            return $this->content;
        } catch(Exception $e) {
            echo $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine() . PHP_EOL;
            return false;
        }
    }

    /**
     * Write a document to the database.
     *
     * @todo Allow passing multiple params of same key (e.g., collections).
     *
     * @param string $uri A document URI.
     * @param array $params Optional additional parameters to pass when writing.
     * @return Document $this
     */
    public function write($uri = null, $params = array())
    {
        $this->uri = (isset($uri)) ? (string)$uri : $this->uri;
        try {
            $params = array_merge(array('uri' => $this->uri), $params);
            $headers = array();
            if ($this->getContentType()) {
                $headers = array('Content-type' => $this->getContentType());
            }
            $request = new RESTRequest('PUT', 'documents', $params, $this->content, $headers);
            $response = $this->restClient->send($request);
            return $this;
        } catch(Exception $e) {
            echo $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine() . PHP_EOL;
        }
    }

    /**
     * Delete a document from the database. Note that no properties for object are changed.
     *
     * @param string $uri The document URI.
     * @return Document $this
     */
    public function delete($uri = null)
    {
        $this->uri = (isset($uri)) ? (string)$uri : $this->uri;
        try {
            $params = array('uri' => $this->uri);
            $request = new RESTRequest('DELETE', 'documents', $params);
            $response = $this->restClient->send($request);
            return $this;
        } catch(Exception $e) {
            echo $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine() . PHP_EOL;
        }
    }

    /**
     * Read document metadata from the database.
     *
     * @return Metadata A Metadata object.
     */
    public function readMetadata()
    {
        try {
            $params = array('uri' => $this->uri, 'category' => 'metadata');
            $request = new RESTRequest('GET', 'documents', $params);
            $response = $this->restClient->send($request);
            $metadata = new Metadata();
            $metadata->loadFromXML($response->getBody());
            return $metadata;
        } catch(Exception $e) {
            echo $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine() . PHP_EOL;
        }
    }

    /**
     * Write document metadata to the database.
     *
     * @param Metadata $metadata A Metadata object.
     * @return Document $this
     */
    public function writeMetadata($metadata)
    {
        $metaxml = $metadata->getAsXML();
        try {
            $params = array('uri' => $this->uri, 'category' => 'metadata', 'format' => 'xml');
            $headers = array('Content-type' => 'application/xml');
            $request = new RESTRequest('PUT', 'documents', $params, $metaxml, $headers);
            $response = $this->restClient->send($request);
            return $this;
        } catch(Exception $e) {
            echo $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine() . PHP_EOL;
        }
    }

    /**
     * Delete document metadata. Metadata reverts to default metadata state.
     *
     * @return Document $this
     */
    public function deleteMetadata()
    {
        try {
            $params = array('uri' => $this->uri, 'category' => 'metadata');
            $request = new RESTRequest('DELETE', 'documents', $params);
            $response = $this->restClient->send($request);
            return $this;
        } catch(Exception $e) {
            echo $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine() . PHP_EOL;
        }
    }

    /**
     * Get the document URI.
     *
     * @return string The document URI.
     */
    public function getURI()
    {
        return $this->uri;
    }

    /**
     * Set the document URI.
     *
     * @param string $uri The document URI.
     * @return Document $this
     */
    public function setURI($uri)
    {
        $this->uri = (string)$uri;
        return $this;
    }

    /**
     * Get the document content type.
     *
     * @return string The document content type.
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * Set the document content type.
     *
     * @param string $contentType The document content type.
     * @return Document $this
     */
    public function setContentType($contentType)
    {
        $this->contentType = (string)$contentType;
        return $this;
    }

    /**
     * Get the document content.
     *
     * @return string The document content.
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set the document content.
     *
     * @param string $content The document content.
     * @return Document $this
     */
    public function setContent($content)
    {
        $this->content = (string)$content;
        return $this;
    }

    /**
     * Set the document content from the file system.
     *
     * @param string $file The file location.
     * @return Document $this
     */
    public function setContentFile($file)
    {
        try {
            $content = file_get_contents((string)$file);
        } catch(Exception $e) {
            echo $e->getMessage();
        }
        $this->setContent($content);
        return $this;
    }

    /**
     * Check if the document has content associated with it.
     *
     * @return bool true if the document has content, false otherwise.
     */
    public function hasContent()
    {
        return !empty($this->content);
    }

    /**
     * Set the REST client for the document.
     *
     * @param RESTClient $restClient A REST client object.
     * @return Document $this
     */
    public function setConnection($restClient)
    {
        $this->restClient = $restClient;
        return $this;
    }

    /**
     * Get the REST client for the document.
     *
     * @return RESTClient  A REST client object.
     */
    public function getConnection()
    {
        return $this->restClient;
    }

    /**
     * Get the mimetype of a file.
     *
     * @param string $file The file.
     * @return string The the mimetype of the file.
     */
    protected function getFileMimeType($file)
    {
        if (function_exists('finfo_file')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $type = finfo_file($finfo, $file);
            finfo_close($finfo);
        } else if (function_exists('mime_content_type')) {
            $type = mime_content_type($file);
        } else {
        	$type = '';
        }
        return $type;
    }
}
