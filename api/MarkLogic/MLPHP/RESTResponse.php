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
 * Represents a REST response.
 *
 * @package MLPHP
 * @author Mike Wooldridge <mike.wooldridge@marklogic.com>
 */
class RESTResponse
{
    private $body; // @var mixed
    private $info; // @var array

    private $url; // @var string
    private $contentType; // @var string

    /**
     * Set the response body.
     *
     * @param mixed $result The REST result from a cURL call: curl_exec($ch)
     */
    public function setBody($result)
    {
        $this->body = $result;
    }

    /**
     * Get the response body.
     *
     * @return mixed The REST result.
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set the response information.
     *
     * @param array $result The REST information from a cURL call: curl_getinfo($ch);
     */
    public function setInfo($info)
    {
        $this->info = $info;
    }

    /**
     * Get the response information.
     *
     * @param $index Index to info property.
     * @return array The REST information.
     */
    public function getInfo($key = '')
    {
        return $this->info;
    }
    /**
     * Get the URL.
     *
     * @return array The URL.
     */
    public function getUrl()
    {
        return $this->info['url'];
    }

    /**
     * Get the content type.
     *
     * @return array The REST information.
     */
    public function getContentType()
    {
        return $this->info['content_type'];
    }

    /**
     * Get the HTTP response code.
     *
     * @return array The HTTP response code.
     */
    public function getHttpCode()
    {
        return $this->info['http_code'];
    }

    /**
     * Get the redirect URL.
     *
     * @return array The redirect URL.
     */
    public function getRedirectUrl()
    {
        return $this->info['redirect_url'];
    }

    /**
     * Get the format type of the body.
     *
     * @return string The type:
     * 'json', 'xml', or 'other' (for non-JSON, non-XML text)
     */
    public function getBodyType()
    {
        $result = '';
        if (json_decode($this->body)) {
            $result = 'json';
        } else if (substr(trim($this->body), 0, 1) === '<') {
            $result = 'xml';
        } else {
            $result = 'other';
        }
        return $result;
    }

    /**
     * Get error message from REST response body.
     *
     * @see http://docs.marklogic.com/guide/rest-dev/service#id_61169
     * @todo error message formats seem unpredictable from server
     *
     * @return array The message.
     */
    public function getErrorMessage()
    {
        $result = '';
        switch ($this->getBodyType())
        {
            case 'json':
                $obj = json_decode($this->body);
                $statusCode = $obj->errorResponse->statusCode;
                $status = $obj->errorResponse->status;
                $message = $obj->errorResponse->message;
                $result = 'Error ' . $statusCode . ': ' . $status . ' - ' . $message;
                break;
            case 'xml':
                $dom = new \DOMDocument($this->body);
                $dom->loadXML($this->body);
                // $statusCode = $dom->getElementsByTagNameNS('http://marklogic.com/rest-api', 'status-code')->item(0)->nodeValue;
                // $status = $dom->getElementsByTagNameNS('http://marklogic.com/rest-api', 'status')->item(0)->nodeValue;
                // $message = $dom->getElementsByTagNameNS('http://marklogic.com/rest-api', 'message')->item(0)->nodeValue;
                // $result = 'Error ' . $statusCode . ': ' . $status . ' - ' . $message;
                // @todo XML won't necessarily be http://marklogic.com/rest-api format
                // For now, just return body of response
                $result = 'Error: ' . $this->body;
                break;
            default:
                $result = 'Error: ' . $this->body;
        }
        return $result;
    }
}
