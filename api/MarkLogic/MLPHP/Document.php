<?php
/*
Copyright 2002-2013 MarkLogic Corporation.  All Rights Reserved.

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
 * Represents a document.
 *
 * @package MLPHP
 * @author Mike Wooldridge <mike.wooldridge@marklogic.com>
 */
class Document
{
    private $uri; // @var string
    private $content; // @var string
    protected $contentType; // @var string
    private $client; // @var RESTClient
    protected $logger; // @var LoggerInterface
    private $response; // @var RESTResponse

    /**
     * Create a Document object.
     *
     * @param RESTClient $client A REST client object.
     * @param string $uri A document URI.
     */
    public function __construct($client, $uri = null)
    {
        $this->client = $client;
        $this->logger = $client->getLogger();
        $this->uri = (string)$uri;
    }

    /**
     * Read a document from the database.
     *
     * @see Document::getContent()
     *
     * @param string $uri A document URI.
     * @param array $params Optional additional parameters to pass when reading.
     * @return string|bool The document content or false on failure.
     */
    public function read($uri = null, $params = array())
    {
        $this->uri = (isset($uri)) ? (string)$uri : $this->uri;
        try {
            $params = array_merge(array('uri' => $this->uri), $params);
            $request = new RESTRequest('GET', 'documents', $params);
            $this->response = $this->client->send($request);
            $this->content = $this->response->getBody();
            $this->contentType = $this->response->getContentType();
            return $this->content;
        } catch(\Exception $e) {
            // TODO: error codes for not-found and other reasonable, unexceptional errors.
            $this->logger->warning( $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine() );
            return false;
        }
    }

    /**
     * Write a document to the database.
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
            $this->response = $this->client->send($request);
        } catch(\Exception $e) {
            $this->logger->error( $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine() );
            throw $e;
        }
        return $this;
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
            $this->response = $this->client->send($request);
        } catch(\Exception $e) {
            $this->logger->error(  $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine() );
        }
        return $this;
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
            $this->response = $this->client->send($request);
            $metadata = new Metadata();
            $metadata->loadFromXML($this->response->getBody());
            return $metadata;
        } catch(\Exception $e) {
            $this->logger->error( $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine() );
            return null;
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
            $this->response = $this->client->send($request);
        } catch(\Exception $e) {
            $this->logger->error( $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine() );
        }
        return $this;
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
            $this->response = $this->client->send($request);
        } catch(\Exception $e) {
            $this->logger->error( $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine() );
        }
        return $this;
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
        } catch(\Exception $e) {
            $this->logger->error( $e->getMessage() );
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
     * @param RESTClient $client A REST client object.
     * @return Document $this
     */
    public function setConnection($client)
    {
        $this->client = $client;
        return $this;
    }

    /**
     * Get the REST client for the document.
     *
     * @return RESTClient  A REST client object.
     */
    public function getConnection()
    {
        return $this->client;
    }

    /**
     * Get the mimetype of a file.
     *
     * @param string $file The file.
     * @return string The the mimetype of the file.
     */
    public function getFileMimeType($file)
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

    /**
     * Get the last REST response received. Useful for testing.
     *
     * @return RESTRresponse A REST response object.
     */
    public function getResponse()
    {
        return $this->response;
    }
}
