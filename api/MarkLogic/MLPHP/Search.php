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

require_once ('SearchResults.php');

/**
 * Represents a search.
 *
 * @package Search
 * @author Mike Wooldridge <mike.wooldridge@marklogic.com>
 */
class Search
{
    private $restClient; // @var RESTClient
    private $query; // @var string
    private $key; // @var string
    private $element; // @var string
    private $attribute; // @var string
    private $value; // @var string
    private $start; // @var int
    private $pageLength; // @var int
    private $options; // @var string
    private $view; // @var string
    private $format; // @var string
    private $collection; // @var string
    private $directory; // @var string

    /**
     * Create a Search object.
     *
     * @param RESTClient $restClient A REST client object.
     */
    public function __construct($restClient = null, $start = 1, $pageLength = 10, $view = 'all', $format = 'xml')
    {
        $this->restClient = $restClient;
        $this->start = (int)$start;
        $this->pageLength = (int)$pageLength;
        $this->view = (string)$view;
        $this->format = (string)$format;
    }

    /**
     * Set the REST client connection.
     *
     * @param RESTClient $restClient The RestClient object.
     */
    public function setConnection($restClient)
    {
        $this->restClient = $restClient;
    }

    /**
     * Get the common search parameters as an associated array.
     *
     * @return array An associated array of name/value pairs.
     */
    public function getParams()
    {
            $params = array('start' => $this->start, 'pageLength' => $this->pageLength, 'view' => $this->view, 'format' => $this->format);
            if(!empty($this->options)) {
                $params['options'] = $this->options;
            }
            if(!empty($this->collection)) {
                $params['collection'] = $this->collection;
            }
            if(!empty($this->directory)) {
                $params['directory'] = $this->directory;
            }
            return $params;
    }

    /**
     * Retrieve the search results using the REST client.
     *
     * @param string $query The search query.
     */
    public function retrieve($query, $params = array())
    {

        $this->query = (string)$query;

        try {
            $params = array_merge(array('q' => $this->query), $this->getParams(), $params);
            $request = new RESTRequest('GET', 'search', $params);
            $response = $this->restClient->send($request);
            //print_r($response);
            $results = new SearchResults($response->getBody());
            return $results;
        } catch(Exception $e) {
                echo $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine() . PHP_EOL;
        }
    }

    /**
     * Retrieve the key-value search results for JSON content using the REST client.
     *
     * @param string $key The key (property name) in JSON content.
     * @param string $value The value (property value) in JSON content.
     */
    public function retrieveKeyValue($key, $value, $params = array())
    {

        $this->key = (string)$key;
        $this->value = (string)$value;

        try {
            $params = array_merge(array('key' => $this->key, 'value' => $this->value), $this->getParams(), $params);
            $request = new RESTRequest('GET', 'keyvalue', $params);
            $response = $this->restClient->send($request);
            $results = new SearchResults($response->getBody());
            return $results;
        } catch(Exception $e) {
                echo $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine() . PHP_EOL;
        }
    }

    /**
     * Retrieve the key-value search results, where the key is an element name, for XML content using the REST client.
     *
     * @param string $element The element name.
     * @param string $attribute The attribute for the element, to search for attribute content.
     * @param string $value The value for that element (or attribute).
     */
    public function retrieveKeyValueElement($element, $attribute, $value, $params = array())
    {

        $this->element = (string)$element;
        $this->attribute = (string)$attribute;
        $this->value = (string)$value;

        try {
            // Only include attribute in final array if it is set
            $array_attr = (!empty($attribute)) ? array('attribute' => $this->attribute) : array();
            $params = array_merge(array('element' => $this->element, 'value' => $this->value), $array_attr, $this->getParams(), $params);
            $request = new RESTRequest('GET', 'keyvalue', $params);
            $response = $this->restClient->send($request);
            $results = new SearchResults($response->getBody());
            return $results;
        } catch(Exception $e) {
                echo $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine() . PHP_EOL;
        }
    }

    /**
     * Get the start setting.
     *
     * @return string The debug setting, 'true' or 'false'.
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Set the start setting.
     *
     * @param string $start The debug setting, 'true' or 'false'.
     */
    public function setStart($start)
    {
        $this->start = (int)$start;
    }

    /**
     * Get the page length.
     *
     * @return int The page length.
     */
    public function getPageLength()
    {
        return $this->pageLength;
    }

    /**
     * Set the page length.
     *
     * @see http://docs.marklogic.com/REST/GET/v1/search
     *
     * @param int $pageLength The page length.
     */
    public function setPageLength($pageLength)
    {
        $this->pageLength = (int)$pageLength;
    }

    /**
     * Get the search-options name.
     *
     * @return string The search-options name.
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Set the search-options name.
     *
     * @param string $option The search-options name.
     */
    public function setOptions($options)
    {
        $this->options = (string)$options;
    }

    /**
     * Get the view setting.
     *
     * @return string The search options as XML.
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * Set the view setting ('facets', 'results', 'metadata', or 'all').
     *
     * @see http://docs.marklogic.com/REST/GET/v1/search
     *
     * @param string $view The search options as XML.
     */
    public function setView($view)
    {
        $this->view = (int)$view;
    }

    /**
     * Get the format setting.
     *
     * @return string The format setting.
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * Set the format setting ('xml' or 'json'). MLPHP only supports 'xml' currently.
     *
     * @see http://docs.marklogic.com/REST/GET/v1/search
     *
     * @param string $format The format setting.
     */
    public function setFormat($format)
    {
        $this->format = (int)$format;
    }

    /**
     * Get the collection to filter by.
     *
     * @return string A collection string.
     */
    public function getCollection()
    {
        return $this->collection;
    }

    /**
     * Set the collection to filter by.
     *
     * @see http://docs.marklogic.com/guide/rest-dev/search#id_74024
     * @todo Allow filtering by multiple collections.
     *
     * @param string $collection A collection string .
     */
    public function setCollection($collection)
    {
        $this->collection = collection;
    }

    /**
     * Get the directory to filter by.
     *
     * @return string A directory string.
     */
    public function getDirectory()
    {
        return $this->directory;
    }

    /**
     * Set the directory to filter by.
     *
     * @see http://docs.marklogic.com/guide/rest-dev/search#id_74024
     * @todo Allow filtering by multiple directories.
     *
     * @param string $directory A directory string.
     */
    public function setDirectory($directory)
    {
        $this->directory = $directory;
    }
}