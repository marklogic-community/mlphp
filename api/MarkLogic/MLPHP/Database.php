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

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Represents a Database
 *
 * @package MLPHP
 * @author Eric Bloch <eric.bloch@gmail.com>
 * @author Mike Wooldridge <mike.wooldridge@marklogic.com>
 */
class Database
{
    private $name; // @var string
    private $restClient; // @var RESTClient
    private $manageClient; // @var RESTClient
    private $response; // @var RESTResponse

    /**
     * Create a Database object.
     *
     * @param string $name The database name.
     * @param RESTClient $manageClient A REST client to the management API.
     */
    public function __construct($name, $manageClient)
    {
        $this->name = $name;
        $this->manageClient = $manageClient;
    }

    /**
     *
     * Clear the database of all content.
     *
     */
    public function clear()
    {
        $json = '{
            "operation": "clear-database"
        }';
        $headers = array('Content-type' => 'application/json');
        $request = new RESTRequest('POST', 'databases/' . $this->name, array(), $json, $headers);
        try {
            $this->response = $this->manageClient->send($request);
            return $this;
        } catch(Exception $e) {
            echo $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine() . PHP_EOL;
        }
    }

    /**
     *
     * Get the number of documents in the database.
     *
     */
    public function numDocs()
    {
        $counts = $this->getCounts();
        return $counts->{'database-counts'}->{'count-properties'}->documents->value;
    }

    /**
     *
     * Get the configuration information for the database.
     *
     */
    public function getConfig()
    {
        $params = array('view' => 'config', 'format' => 'json');
        $request = new RESTRequest('GET', 'databases/' . $this->name, $params);
        try {
            $this->response = $this->manageClient->send($request);
            $config = json_decode($this->response->getBody());
            return $config;
        } catch(Exception $e) {
            echo $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine() . PHP_EOL;
        }
    }

    /**
     *
     * Get the count information for the database.
     *
     */
    public function getCounts()
    {
        $params = array('view' => 'counts', 'format' => 'json');
        $request = new RESTRequest('GET', 'databases/' . $this->name, $params);
        try {
            $this->response = $this->manageClient->send($request);
            $counts = json_decode($this->response->getBody());
            return $counts;
        } catch(Exception $e) {
            echo $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine() . PHP_EOL;
        }
    }

    /**
     *
     * Get the status information for the database as JSON.
     *
     */
    public function getStatus()
    {
        $params = array('view' => 'status', 'format' => 'json');
        $request = new RESTRequest('GET', 'databases/' . $this->name, $params);
        try {
            $this->response = $this->manageClient->send($request);
            $status = json_decode($this->response->getBody());
            return $status;
        } catch(Exception $e) {
            echo $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine() . PHP_EOL;
        }
    }

    /**
     *
     * Get the modifiable properties of the database as JSON.
     *
     */
    public function getProperties()
    {
        $params = array('format' => 'json');
        $request = new RESTRequest('GET', 'databases/' . $this->name . '/properties', $params);
        try {
            $this->response = $this->manageClient->send($request);
            $properties = json_decode($this->response->getBody());
            return $properties;
        } catch(Exception $e) {
            echo $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine() . PHP_EOL;
        }
    }

    /**
     *
     * Set properties of the database.
     *
     * @param string json The JSON string representing one or more properties.
     */
    public function setProperties($json)
    {
        $headers = array('Content-type' => 'application/json');
        $request = new RESTRequest(
          'PUT', 'databases/' . $this->name . '/properties',
          array(), $json, $headers
        );
        try {
            $this->response = $this->manageClient->send($request);
            return $this;
        } catch(Exception $e) {
            echo $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine() . PHP_EOL;
        }
    }

    /**
     *
     * Set an range element index.
     * @see http://docs-ea.marklogic.com/guide/admin/range_index#id_51346
     *
     * @param string scalarType The scalar type (example: 'int' or 'string').
     * @param string localname The local name of the element.
     * @param string namespaceURI The namespace URI (for XML content).
     * @param boolean rangeValuePositions Whether to index range values positions (default is false).
     * @param string invalidValues "ignore" or "reject" (default).
     * @param string collation The collation value.
     */
    public function addRangeElementIndex(
        $scalarType, $localname, $namespaceURI = '',
        $rangeValuePositions = false, $invalidValues = 'reject', $collation = ''
    )
    {
        $obj = (object) [
            'scalar-type' => $scalarType,
            'localname' => $localname,
            'namespace-uri' => $namespaceURI,
            'range-value-positions' => $rangeValuePositions,
            'invalid-values' => $invalidValues,
            'collation' => $collation
        ];
        // get any existing indexes
        $properties = $this->getProperties();
        if (property_exists($properties, 'range-element-index')) {
          $indexes = $properties->{'range-element-index'};
        } else {
          $indexes = array();
        }
        // add the new index
        array_push($indexes, $obj);
        // wrap in outer property
        $new = (object) ['range-element-index' => $indexes];
        // set the updated properties
        return $this->setProperties(json_encode($new));
    }

    /**
     *
     * Set an range element attribute index.
     * @see http://docs-ea.marklogic.com/guide/admin/range_index#id_51346
     *
     * @param string scalarType The scalar type (example: 'int' or 'string').
     * @param string parentLocalname The local name of the parent element.
     * @param string localname The local name of the attribute.
     * @param string parentNamespaceURI The namespace URI of the parent (for XML content).
     * @param string namespaceURI The namespace URI of the attribute (for XML content).
     * @param boolean rangeValuePositions Whether to index range values positions (default is false).
     * @param string invalidValues "ignore" or "reject" (default).
     * @param string collation The collation value.
     */
    public function addRangeAttributeIndex(
        $scalarType, $parentLocalname, $localname, $parentNamespaceURI = '', $namespaceURI = '',
        $rangeValuePositions = false, $invalidValues = 'reject', $collation = ''
    )
    {
        $obj = (object) [
            'scalar-type' => $scalarType,
            'parent-localname' => $parentLocalname,
            'localname' => $localname,
            'parent-namespace-uri' => $parentNamespaceURI,
            'namespace-uri' => $namespaceURI,
            'range-value-positions' => $rangeValuePositions,
            'invalid-values' => $invalidValues,
            'collation' => $collation
        ];
        // get any existing indexes
        $properties = $this->getProperties();
        if (property_exists($properties, 'range-element-attribute-index')) {
          $indexes = $properties->{'range-element-attribute-index'};
        } else {
          $indexes = array();
        }
        // add the new index
        array_push($indexes, $obj);
        // wrap in outer property
        $new = (object) ['range-element-attribute-index' => $indexes];
        // set the updated properties
        return $this->setProperties(json_encode($new));
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

