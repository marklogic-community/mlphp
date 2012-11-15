<?php

/**
 * Represents a REST request.
 *
 * @package mlphp\rest
 * @author Mike Wooldridge <mike.wooldridge@marklogic.com>
 */
class RESTRequest
{
    private $verb; // @var string
    private $resource; // @var string
    private $params; // @var array
    private $body; // @var string

    /*
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
    public function setResource($verb)
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
        $this->verb = $verb;
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
     * Get the resource and params as a URL string.
     *
     * @todo Allow for multiple params of same name (e.g., when filtering by collections or directories for search).
     *
     * @return string The request body.
     */
    public function getUrlStr()
    {
        $str = (!empty($this->resource)) ? $this->resource : '';
        $str .= (!empty($this->params)) ? ('?' . http_build_query($this->params)) : '';
        return $str;
    }
}
