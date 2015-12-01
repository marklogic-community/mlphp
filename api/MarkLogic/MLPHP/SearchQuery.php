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
 * Represents a search query.
 *
 * @package MLPHP
 * @author Mike Wooldridge <mike.wooldridge@marklogic.com>
 */
class SearchQuery
{
    private $query; // @var string
    private $structured; // @var boolean

    /**
     * Create a SearchQuery object.
     *
     * @todo Make $structured optional, automatically determine whether
     * query is XML or JSON (structured) or simple.
     *
     * @param string $query A simple or structured search query string.
     * @param boolean $structured Whether the query is a structured query.
     */
    public function __construct($query, $structured)
    {
        $this->query = $query;
        $this->structured = $structured;
    }

    /**
     * Get the query string.
     *
     * @return string The query string.
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Return whether the query is a structured query.
     *
     * @return boolean True if the query is structured.
     */
    public function isStructured()
    {
        return $this->structured;
    }

}
