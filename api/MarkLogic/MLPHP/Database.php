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
     * Delete the database.
     *
     */
    public function delete()
    {
        $headers = array('Content-type' => 'application/json');
        $request = new RESTRequest('DELETE', 'databases/' . $this->name, array(), '', $headers);
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
     * Get a simple property of the database.
     *
     * @param string key The property key.
     */
    public function getProperty($key)
    {
        $properties = $this->getProperties();
        return $properties->$key ?: null;
    }

    /**
     *
     * Set a simple property of the database.
     *
     * @param string key The property key.
     * @param string json The property value.
     */
    public function setProperty($key, $value)
    {
        $new = array($key => $value);
        return $this->setProperties(json_encode($new));
    }

    /**
     *
     * Add a complex property.
     * Generalized from addField, example:
     * $type = 'field'
     * $arr = instance of Field as an assoc array of properties
     * $idKey = 'field-name'
     * @todo handle instances where property to substitute is based
     *       on multiple property definitions (e.g., localname and namespace)
     *
     * @param string type The property type (key).
     * @param array arr The assoc array representing the property to add.
     * @param string key The key representing the object's unique ID (optional).
     */
    public function addProperty($type, $arr, $key = null)
    {
        // get existing
        $properties = $this->getProperties();
        if (property_exists($properties, $type)) {
          $existingProperties = $properties->{$type};
        } else {
          $existingProperties = array();
        }
        // remove any existing with same name
        if ($key) {
          foreach ($existingProperties as $k=>$v) {
              if ($v->$key == $arr[$key]) {
                  unset($existingProperties[$k]);
                  $existingProperties = array_values($existingProperties);
              }
          }
        }
        // add the new field
        array_push($existingProperties, $arr);
        // wrap in type property
        $new = (object) [$type => $existingProperties];
        // set the updated properties
        return $this->setProperties(json_encode($new));
    }

    /**
     *
     * Remove a complex property.
     * Generalized from removeField, example:
     * $type = 'field'
     * $key = 'field-name'
     * $id = 'foo'
     * @todo handle instances where property to remove is based
     *       on multiple property definitions (e.g., localname and namespace)
     *
     * @param string type The property type (key).
     * @param mixed key The key for the object's unique ID.
     * @param string id The ID of the property to remove.
     */
    public function removeProperty($type, $key, $id)
    {
        // get existing
        $properties = $this->getProperties();
        if (property_exists($properties, $type)) {
            $existingProperties = $properties->{$type};
            foreach ($existingProperties as $k=>$v) {
                if ($v->$key == $id) {
                    unset($existingProperties[$k]);
                    $existingProperties = array_values($existingProperties); // reindex
                    // wrap in outer property
                    $new = (object) [$type => $existingProperties];
                    // set the updated properties
                    $this->setProperties(json_encode($new));
                }
            }
        }
        return $this;
    }


    /**
     *
     * Check if a complex property exists.
     *
     * @param string type The property type (key).
     * @param array arr Assoc array representing property to check.
     */
    public function propertyExists($type, $arr)
    {
        // get existing
        $properties = $this->getProperties();
        if (property_exists($properties, $type)) {
            $existingProperties = $properties->$type;
            // cycle through each property of type
            foreach ($existingProperties as $k1=>$v1) {
                $found = true;
                // check if any properties don't match
                foreach ($arr as $k2=>$v2) {
                    if ($v1->$k2 != $v2) {
                        $found = false;
                        break;
                    }
                }
                // match found
                if ($found) {
                    return true;
                }
            }
        }
        // none matched
        return false;
    }

    /**
     *
     * Add a range element index.
     * @see http://docs-ea.marklogic.com/guide/admin/range_index#id_51346
     *
     * @param array properties The properties.
     */
    public function addRangeElementIndex($properties)
    {
        $properties = array_merge(array(
            'scalar-type' => 'string',
            'localname' => '',
            'namespace-uri' => '',
            'range-value-positions' => false,
            'invalid-values' => 'reject',
            'collation' => ''
        ), $properties);
        $this->addProperty('range-element-index', $properties, 'localname');
    }

    /**
     *
     * Remove a range element index.
     *
     * @param string localname The localname of the index to remove.
     */
    public function removeRangeElementIndex($localname)
    {
        $this->removeProperty('range-element-index', 'localname', $localname);
    }

    /**
     *
     * Add a range element attribute index.
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
     *
     * Add a field.
     * @todo generalize this for all complex properties
     *
     * @param string field The Field object.
     */
    public function addField($field)
    {
        // get existing fields
        $properties = $this->getProperties();
        if (property_exists($properties, 'field')) {
          $fields = $properties->{'field'};
        } else {
          $fields = array();
        }
        // remove any existing with same name
        foreach ($fields as $k=>$v) {
            if ($v->{'field-name'} == $field->properties['field-name']) {
                unset($fields[$k]);
                $fields = array_values($fields);
            }
        }
        // add the new field
        array_push($fields, $field->properties);
        // wrap in outer property
        $new = (object) ['field' => $fields];
        // set the updated properties
        return $this->setProperties(json_encode($new));
    }

    /**
     *
     * Remove a field.
     * @todo generalize this for all complex properties
     *
     * @param string name The name of the field to remove.
     */
    public function removeField($name)
    {
        // get existing fields
        $properties = $this->getProperties();
        if (property_exists($properties, 'field')) {
            $fields = $properties->{'field'};
            foreach ($fields as $k=>$v) {
                if ($v->{'field-name'} == $name) {
                    unset($fields[$k]);
                    $fields = array_values($fields); // reindex
                    // wrap in outer property
                    $new = (object) ['field' => $fields];
                    // set the updated properties
                    $this->setProperties(json_encode($new));
                }
            }
        }
        return $this;
    }

    /**
     *
     * Add a range field index.
     *
     * @param string scalarType The scalar type (example: 'int' or 'string').
     * @param string fieldName The name of the field.
     * @param boolean rangeValuePositions Whether to index range values positions (default is false).
     * @param string invalidValues "ignore" or "reject" (default).
     * @param string collation The collation value.
     */
    public function addRangeFieldIndex(
        $scalarType, $fieldName, $rangeValuePositions = false,
        $invalidValues = 'reject', $collation = ''
    )
    {
        $obj = (object) [
            'scalar-type' => $scalarType,
            'field-name' => $fieldName,
            'range-value-positions' => $rangeValuePositions,
            'invalid-values' => $invalidValues,
            'collation' => $collation
        ];
        // get any existing indexes
        $properties = $this->getProperties();
        if (property_exists($properties, 'range-field-index')) {
          $indexes = $properties->{'range-field-index'};
        } else {
          $indexes = array();
        }
        // add the new index
        array_push($indexes, $obj);
        // wrap in outer property
        $new = (object) ['range-field-index' => $indexes];
        // set the updated properties
        return $this->setProperties(json_encode($new));
    }

    /**
     *
     * Add path namespace.
     *
     * @param string prefix The prefix of the namespace.
     * @param string namespaceURI The namespace URI.
     */
    public function addPathNamespace($prefix, $namespaceURI = '')
    {
        $obj = (object) [
            'prefix' => $prefix,
            'namespace-uri' => $namespaceURI
        ];
        // get any existing indexes
        $properties = $this->getProperties();
        if (property_exists($properties, 'path-namespace')) {
          $namespaces = $properties->{'path-namespace'};
        } else {
          $namespaces = array();
        }
        // add the new namespace
        array_push($namespaces, $obj);
        // wrap in outer property
        $new = (object) ['path-namespace' => $namespaces];
        // set the updated properties
        return $this->setProperties(json_encode($new));
    }

    /**
     *
     * Add a range path index.
     *
     * @param string scalarType The scalar type (example: 'int' or 'string').
     * @param string pathExpression The path expression.
     * @param boolean rangeValuePositions Whether to index range values positions (default is false).
     * @param string invalidValues "ignore" or "reject" (default).
     * @param string collation The collation value.
     */
    public function addRangePathIndex(
        $scalarType, $pathExpression, $rangeValuePositions = false,
        $invalidValues = 'reject', $collation = ''
    )
    {
        $obj = (object) [
            'scalar-type' => $scalarType,
            'path-expression' => $pathExpression,
            'range-value-positions' => $rangeValuePositions,
            'invalid-values' => $invalidValues,
            'collation' => $collation
        ];
        // get any existing indexes
        $properties = $this->getProperties();
        if (property_exists($properties, 'range-path-index')) {
          $indexes = $properties->{'range-path-index'};
        } else {
          $indexes = array();
        }
        // add the new index
        array_push($indexes, $obj);
        // wrap in outer property
        $new = (object) ['range-path-index' => $indexes];
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

