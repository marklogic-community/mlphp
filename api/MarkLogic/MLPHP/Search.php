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
//use MarkLogic\MLPHP\SearchResult;

/**
 * Represents a search.
 *
 * @package MLPHP
 * @author Mike Wooldridge <mike.wooldridge@marklogic.com>
 */
class Search
{
    private $client; // @var RESTClient
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
     * @param RESTClient $client A REST client object.
     */
    public function __construct(
        $client = null, $start = 1, $pageLength = 10,
        $view = 'all', $format = 'xml'
    )
    {
        $this->client = $client;
        $this->start = (int)$start;
        $this->pageLength = (int)$pageLength;
        $this->view = (string)$view;
        $this->format = (string)$format;
    }

    /**
     * Set the REST client connection.
     *
     * @param RESTClient $client The RestClient object.
     */
    public function setConnection($client)
    {
        $this->client = $client;
    }

    /**
     * Get the common search parameters as an associated array.
     *
     * @return array An associated array of name/value pairs.
     */
    public function getParams()
    {
        $params = array(
            'start' => $this->start, 'pageLength' => $this->pageLength,
            'view' => $this->view, 'format' => $this->format
        );
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
     * @param array $params
     * @param bool $structured defaults to false
     * @return SearchResults A search results object.
     */
    public function retrieve($query, $params = array(), $structured = false)
    {
        $this->query = (string)$query;
        $params = array_merge(array(($structured ? 'structuredQuery' : 'q') =>
            $this->query), $this->getParams(), $params);
        $request = new RESTRequest('GET', 'search', $params);

        try {
            $response = $this->client->send($request);
            //print_r($response);
            $results = new SearchResults($response->getBody());
            return $results;
        } catch(Exception $e) {
            echo $e->getMessage() . ' in ' . $e->getFile() .
                ' on line ' . $e->getLine() . PHP_EOL;
        }
    }

    /**
     * Retrieve the key-value search results for JSON content using the
     * REST client.
     *
     * @param string $key The key (property name) in JSON content.
     * @param string $value The value (property value) in JSON content.
     * @return SearchResults A search results object.
     */
    public function retrieveKeyValue($key, $value, $params = array())
    {
        // /v1/keyvalue is deprecated, use /v1/search with structured
        $this->key = (string)$key;
        $this->value = (string)$value;
        $query = '<query xmlns="http://marklogic.com/appservices/search">
                    <container-query>
                      <json-property name="' . $this->key . '" ns="" />
                      <term-query>
                        <text>' . $this->value . '</text>
                      </term-query>
                    </container-query>
                  </query>';

        try {
            $params = array_merge(
                array('structuredQuery' => $query),
                $this->getParams(), $params
            );
            $request = new RESTRequest('GET', 'search', $params);
            $response = $this->client->send($request);
            $results = new SearchResults($response->getBody());
            return $results;
        } catch(Exception $e) {
            echo $e->getMessage() . ' in ' . $e->getFile() .
            ' on line ' . $e->getLine() . PHP_EOL;
        }
    }

    /**
     * Retrieve the key-value search results, where the key is an element
     * name, for XML content using the REST client.
     *
     * @param string $element The element name.
     * @param string $attribute The attribute for the element, to search for
     *        attribute content.
     * @param string $value The value for that element (or attribute).
     * @return SearchResults A search results object.
     */
    public function retrieveKeyValueElement(
        $element, $attribute, $value, $params = array()
    )
    {
        $this->element = (string)$element;
        $this->attribute = (string)$attribute;
        $this->value = (string)$value;
        // /v1/keyvalue is deprecated, use /v1/search with structured
        // $query = '<query xmlns="http://marklogic.com/appservices/search">
        //             <container-query>
        //               <element name="' . $this->element . '" ns="" />';
        // $query .= $this->attribute ?
        //     '<attribute name=' . $this->attribute . ' ns="" />' :
        //     '';
        // $query .= '<term-query>
        //                 <text>' . $this->value . '</text>
        //               </term-query>
        //             </container-query>
        //           </query>';

        try {
            // Only include attribute in final array if it is set
            $array_attr = (!empty($attribute)) ?
                array('attribute' => $this->attribute) : array();
            $params = array_merge(
                array('element' => $this->element, 'value' => $this->value),
                $array_attr, $this->getParams(), $params);
            $request = new RESTRequest('GET', 'keyvalue', $params);

            $params = array_merge(
                array('element' => $this->element, 'value' => $this->value),
                $array_attr, $this->getParams(), $params);
            // $params = array_merge(
            //     array('structuredQuery' => $query),
            //     $this->getParams(), $params
            // );
            $request = new RESTRequest('GET', 'keyvalue', $params);
            $response = $this->client->send($request);
            $results = new SearchResults($response->getBody());
            return $results;
        } catch(Exception $e) {
            echo $e->getMessage() . ' in ' . $e->getFile() .
            ' on line ' . $e->getLine() . PHP_EOL;
        }
    }

    /**
     * Highlight hits in the given content based on the given query
     *
     * Implemented via a MarkLogic REST API resource extension.
     *
     * @param string $content
     * @param string $contentType
     *        - describes content to be highlighted
     *        - support for application/xml and text'plain
     * @param string $query The search query.
     * @param array $params
     * @param bool $structured defaults to false (only false is
     *        supported today)
     * @return string hit-highlighted content
     */
    public function highlight(
        $content, $contentType, $class, $query,
        $params = array(), $structured = false
    )
    {
        // Install the API extension
        $resource = "resources/highlight";
        $this->client->installExtension("config/" . $resource, array(
            'method' => 'post',
            'post:q?' => 'string',
            'post:class' => 'string',
            'post:structuredQuery?' => 'string',
            'post:c' => 'string',
            'post:ct' => 'string',
            'post:provider?' => 'string'
        ), "highlight.xqy");

        // Use it
        $this->query = (string)$query;
        $params = array_merge(array(
            ($structured ? 'structuredQuery' : 'q') => $this->query,
            'c' => $content,
            'ct' => $contentType,
            'class' => $class
        ), $this->getParams(), $params);

        $request = new RESTRequest('POST', $resource, $params, "", array(
            'Content-type' => 'application/x-www-form-urlencoded'
        ));

        try {
            $response = $this->client->send($request);
            //print_r($response);
            $results = $response->getBody();
            if ($contentType === "text/plain") {
                return $results;
            } else {
                // Strip off XML decl
                return substr( $results, strpos($results, "\n")+1 );
            }
        } catch(Exception $e) {
            echo $e->getMessage() . ' in ' . $e->getFile() .
            ' on line ' . $e->getLine() . PHP_EOL;
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
        return $this;
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
        return $this;
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
        return $this;
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
        return $this;
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
     * Set the format setting ('xml' or 'json'). MLPHP only supports
     * 'xml' currently.
     *
     * @see http://docs.marklogic.com/REST/GET/v1/search
     *
     * @param string $format The format setting.
     */
    public function setFormat($format)
    {
        $this->format = (int)$format;
        return $this;
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
     *
     * @param array|string $collection An array of collection strings or a
     *        collection string.
     */
    public function setCollection($collection)
    {
        $this->collection = $collection;
        return $this;
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
     *
     * @param array|string $directory An array of directory strings or a
     *        directory string.
     */
    public function setDirectory($directory)
    {
        $this->directory = $directory;
        return $this;
    }
}
