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
 * Represents a search-facet value.
 *
 * @package MLPHP
 * @author Mike Wooldridge <mike.wooldridge@marklogic.com>
 */
class FacetValue
{
    private $name; // @var string
    private $count; // @var string
    private $value; // @var array

    /**
     * Create a FacetValue object.
     *
     * @param string $name The name.
     * @param string $count The count.
     * @param string $value The value.
     */
    public function __construct($name, $count, $value)
    {
        $this->name = $name;
        $this->count = $count;
        $this->value = $value;
    }

    /**
     * Get the facet-value name.
     *
     * @return string The name.
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the facet-value count.
     *
     * @return int The count.
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Get the facet-value value.
     *
     * @return string The value.
     */
    public function getValue()
    {
        return $this->value;
    }
}
