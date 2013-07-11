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
    private $httpCode; // @var int

    /**
     * Set the response body.
     *
     * @param mixed $result The REST result.
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
     * @param array $result The REST information.
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
     * Get error message from REST response body.
     *
     * @return array The message.
     */
    public function getErrorMessage()
    {
        if ($obj = json_decode($this->body)) {
            // response body is JSON
            $statusCode = $obj->error->{'status-code'};
            $status = $obj->error->status;
            $message = $obj->error->message;
            $result = 'Error ' . $statusCode . ': ' . $status . ' - ' . $message;
        } else if (substr(trim($this->body), 0, 1) === '<') {
            // response body is XML
            $dom = new \DOMDocument($this->body);
            $dom->loadXML($this->body);
            $statusCode = $dom->getElementsByTagNameNS('http://marklogic.com/rest-api', 'status-code')->item(0)->nodeValue;
            $status = $dom->getElementsByTagNameNS('http://marklogic.com/rest-api', 'status')->item(0)->nodeValue;
            $message = $dom->getElementsByTagNameNS('http://marklogic.com/rest-api', 'message')->item(0)->nodeValue;
            $result = 'Error ' . $statusCode . ': ' . $status . ' - ' . $message;
        } else {
            // response is text or something else
            $result = 'Error: ' . $this->body;
        }
        return $result;
    }
}
