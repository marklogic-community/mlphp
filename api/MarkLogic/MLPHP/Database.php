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
    private $manageClient; // @var RESTClient
    private $response; // @var RESTResponse

    /**
     * Create a Database object.
     *
     * @param RESTClient $manageClient A REST client to the management API.
     * @param string $name The database name.
     */
    public function __construct($manageClient, $name)
    {
        $this->name = $name;
        $this->manageClient = $manageClient;
    }

    /**
     * Get the database name.
     *
     * @return string The database name.
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the database name.
     *
     * @param string $name The database name.
     * @return Database $this
     */
    public function setName($name)
    {
        $this->name = (string)$name;
        return $this;
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
     * Get the status information for the database.
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
     * Get the modifiable properties of the database as a PHP object.
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
     * @param array arr Assoc array representing one or more properties.
     */
    public function setProperties($arr)
    {
        $headers = array('Content-type' => 'application/json');
        $request = new RESTRequest(
          'PUT', 'databases/' . $this->name . '/properties',
          array(), json_encode($arr), $headers
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
        return $this->setProperties($new);
    }

    /**
     *
     * Add a complex property.
     * Generalized from addField, example:
     * $type = 'field'
     * $arr = instance of Field as an assoc array of properties
     *
     * @param string type The property type (key).
     * @param array arr The assoc array representing the property to add.
     */
    public function addProperty($type, $arr)
    {
        // get existing
        $properties = $this->getProperties();
        if (property_exists($properties, $type)) {
          $existingProperties = $properties->$type;
        } else {
          $existingProperties = array();
        }
        // add the new field to beginning of array
        array_unshift($existingProperties, $arr);
        // wrap in type property
        // $new = (object) [$type => $existingProperties]; // PHP 5.4
        $new = new \stdClass();
        $new->$type = $existingProperties;
        // set the updated properties
        return $this->setProperties($new);
    }

    /**
     *
     * Remove a complex property.
     *
     * @param string type The property type (key).
     * @param array arr Assoc array representing property to remove.
     */
    public function removeProperty($type, $arr)
    {
        // get existing
        $properties = $this->getProperties();
        if (property_exists($properties, $type)) {
            $existingProperties = $properties->$type;
            // cycle through each property of type
            foreach ($existingProperties as $k1=>$v1) {
                $found = true;
                // if any property doesn't match, don't remove
                foreach ($arr as $k2=>$v2) {
                    if ($v1->$k2 != $v2) {
                        $found = false;
                        break;
                    }
                }
                if ($found) {
                    unset($existingProperties[$k1]);
                    $existingProperties = array_values($existingProperties); // reindex
                }
            }
            // wrap in outer property
            //$new = (object) [$type => $existingProperties]; // PHP 5.4
            $new = new \stdClass();
            $new->$type = $existingProperties;
            // set the updated properties
            $this->setProperties($new);
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
     * @param array arr Assoc array of properties.
     */
    public function addRangeElementIndex($arr)
    {
        $arr = array_merge(array(
            'scalar-type' => 'string',
            'localname' => '',
            'namespace-uri' => '',
            'range-value-positions' => false,
            'invalid-values' => 'reject',
            'collation' => ''
        ), $arr);
        // string types require a collation property, set if empty
        $arr['collation'] = ($arr['scalar-type'] === 'string' &&
            $arr['collation'] === '') ? 'http://marklogic.com/collation/' : '';
        $this->addProperty('range-element-index', $arr, 'localname');
    }

    /**
     *
     * Remove a range element index.
     *
     * @param array arr Assoc array representing index to remove.
     */
    public function removeRangeElementIndex($arr)
    {
        $this->removeProperty('range-element-index', $arr);
    }

    /**
     *
     * Add a range element attribute index.
     *
     * @param array arr Assoc array of properties.
     */
    public function addRangeAttributeIndex($arr)
    {
        $arr = array_merge(array(
            'scalar-type' => 'string',
            'parent-localname' => '',
            'localname' => '',
            'parent-namespace-uri' => '',
            'namespace-uri' => '',
            'range-value-positions' => false,
            'invalid-values' => 'reject',
            'collation' => ''
        ), $arr);
        // string types require a collation property, set if empty
        $arr['collation'] = ($arr['scalar-type'] === 'string' &&
            $arr['collation'] === '') ? 'http://marklogic.com/collation/' : '';
        $this->addProperty('range-element-attribute-index', $arr);
    }

    /**
     *
     * Remove a range element attribute index.
     *
     * @param array arr Assoc array representing index to remove.
     */
    public function removeRangeAttributeIndex($arr)
    {
        $this->removeProperty('range-element-attribute-index', $arr);
    }

    /**
     *
     * Add a field.
     *
     * @param string field The Field object.
     */
    public function addField($field)
    {
        $this->addProperty('field', $field);
    }

    /**
     *
     * Remove a field.
     *
     * @param string name The name of the field to remove.
     */
    public function removeField($name)
    {
        $this->removeProperty('field', array('field-name' => $name));
    }

    /**
     *
     * Add a range field index.
     *
     * @param array arr Assoc array of properties.
     */
    public function addRangeFieldIndex($arr)
    {
        $arr = array_merge(array(
            'scalar-type' => 'string',
            'field-name' => '',
            'range-value-positions' => false,
            'invalid-values' => 'reject',
            'collation' => ''
        ), $arr);
        // string types require a collation property, set if empty
        $arr['collation'] = ($arr['scalar-type'] === 'string' &&
            $arr['collation'] === '') ? 'http://marklogic.com/collation/' : '';
        $this->addProperty('range-field-index', $arr);
    }

    /**
     *
     * Remove a range field index.
     *
     * @param array arr Assoc array representing index to remove.
     */
    public function removeRangeFieldIndex($arr)
    {
        $this->removeProperty('range-field-index', $arr);
    }

    /**
     *
     * Add a path namespace.
     *
     * @param array arr Assoc array of properties.
     */
    public function addPathNamespace($arr)
    {
        $arr = array_merge(array(
            'prefix' => '',
            'namespace-uri' => ''
        ), $arr);
        $this->addProperty('path-namespace', $arr);
    }

    /**
     *
     * Remove a path namespace.
     *
     * @param array arr Assoc array representing index to remove.
     */
    public function removePathNamespace($arr)
    {
        $this->removeProperty('path-namespace', $arr);
    }

    /**
     *
     * Add a range path index.
     *
     * @param array arr Assoc array of properties.
     */
    public function addRangePathIndex($arr)
    {
        $arr = array_merge(array(
            'scalar-type' => 'string',
            'path-expression' => '',
            'range-value-positions' => false,
            'invalid-values' => 'reject',
            'collation' => ''
        ), $arr);
        // string types require a collation property, set if empty
        $arr['collation'] = ($arr['scalar-type'] === 'string' &&
            $arr['collation'] === '') ? 'http://marklogic.com/collation/' : '';
        $this->addProperty('range-path-index', $arr);
    }

    /**
     *
     * Remove a range path index.
     *
     * @param array arr Assoc array representing index to remove.
     */
    public function removeRangePathIndex($arr)
    {
        $this->removeProperty('range-path-index', $arr);
    }

    /**
     *
     * Add an element word lexicon.
     *
     * @param array arr Assoc array of properties.
     */
    public function addElementLexicon($arr)
    {
        $arr = array_merge(array(
            'localname' => '',
            'namespace-uri' => '',
            'collation' => 'http://marklogic.com/collation/'
        ), $arr);
        $this->addProperty('element-word-lexicon', $arr);
    }

    /**
     *
     * Remove an element word lexicon.
     *
     * @param array arr Assoc array of properties.
     */
    public function removeElementLexicon($arr)
    {
        $this->removeProperty('element-word-lexicon', $arr);
    }

    /**
     *
     * Add an element attribute word lexicon.
     *
     * @param array arr Assoc array of properties.
     */
    public function addAttributeLexicon($arr)
    {
        $arr = array_merge(array(
            'parent-localname' => '',
            'parent-namespace-uri' => '',
            'localname' => '',
            'namespace-uri' => '',
            'collation' => 'http://marklogic.com/collation/'
        ), $arr);
        $this->addProperty('element-attribute-word-lexicon', $arr);
    }

    /**
     *
     * Remove an element attribute word lexicon.
     *
     * @param array arr Assoc array of properties.
     */
    public function removeAttributeLexicon($arr)
    {
        $this->removeProperty('element-attribute-word-lexicon', $arr);
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

