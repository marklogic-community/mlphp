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
 * Represents a REST request.
 *
 * @package MLPHP
 * @author Mike Wooldridge <mike.wooldridge@marklogic.com>
 */
class RESTRequest
{
    private $verb; // @var string
    private $resource; // @var string
    private $params; // @var array
    private $body; // @var string
    private $headers; // @var array

    /**
     * Create a REST request object.
     *
     * @param string $verb The REST verb (example: 'GET').
     * @param string $resource The REST resource (example: 'documents').
     * @param array $params Array of parameters for the REST request.
     * @param string $body The REST request body;
     * @param array $headers Array of request headers;
     */
    public function __construct($verb, $resource, $params = array(), $body = '', $headers = array())
    {
        $this->verb = (string)$verb;
        $this->resource = (string)$resource;
        $this->params = $params;
        $this->body = (string)$body;
        $this->headers = $headers;
    }

    /**
     * Set the REST verb.
     *
     * @param string $verb The REST verb (example: 'GET')
     */
    public function setVerb($verb)
    {
        $this->verb = $verb;
    }

    /**
     * Get the REST verb.
     *
     * @return string The REST verb.
     */
    public function getVerb()
    {
        return $this->verb;
    }

    /**
     * Set the REST resource.
     *
     * @param string $resource The REST resource (example: 'documents').
     */
    public function setResource($resource)
    {
        $this->resource = $resource;
    }

    /**
     * Get the REST resource.
     *
     * @return string The REST resource.
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * Set the parameters.
     *
     * @param array $params An array of parameters.
     */
    public function setParams($params)
    {
        $this->params = $params;
    }

    /**
     * Get the parameters.
     *
     * @return array An array of parameters.
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Set the request body.
     *
     * @param string $body The request body.
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * Get the request body.
     *
     * @return string The request body.
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set the request headers.
     *
     * @param array $headers An array of request headers (example: 'Content-type' => 'appliation/xml').
     */
    public function setHeaders($headers)
    {
        $this->headers = $headers;
    }

    /**
     * Get the request headers.
     *
     * @return array An array of request headers.
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @return true if the request is a POST with Content-type application/x-www-form-urlencoded
     */
    public function isWWWFormURLEncodedPost()
    {
        if (strtolower($this->verb) != "post") {
            return false;
        }

        // @todo Should this return false?
        if (empty($this->headers["Content-type"])) {
            return true;
        }

        // @todo Do I need to check for other cases of Content-Type content-type, etc?
        switch (strtolower($this->headers["Content-type"])) {
            case "application/x-www-form-urlencoded":
                return true;
            default:
                return false;
        }
    }

    /**
     * Build query from resource and params. Accounts for the fact that some param
     * keys may be associated with arrays, which will generate the same keys multiple
     * times in the query, e.g.:
     * params: array("foo"=>["bar", "baz"])
     * query:  ?foo=bar&foo=baz
     *
     * @return string The built query.
     */
    public function buildQuery()
    {
        $query = '';
        if (!empty($this->params)) {
            foreach($this->params as $key => $val) {
                if (!is_array($val)) {
                    $query .= urlencode($key) . '=' . urlencode($val) . '&';
                } else {
                    foreach($val as $v) {
                        $query .= urlencode($key) . '=' . urlencode($v) . '&';
                    }
                }
            }
            // Remove trailing '&'
            $query = substr($query, 0, -1);
        }
        return $query;
    }


    /**
     * Get the resource and params as a URL string.
     *
     * @return string The request body.
     */
    public function getUrlStr()
    {
        $str = (!empty($this->resource)) ? $this->resource : '';

        /* x-www-form-encoded posts encodes query params in the body */
        if (!$this->isWWWFormURLEncodedPost()) {
            $str .= (!empty($this->params)) ? ('?' . $this->buildQuery()) : '';
        }
        return $str;
    }
}
