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

use JsonSerializable;

/**
 * Represents a database field.
 *
 * @package MLPHP
 * @author Mike Wooldridge <mike.wooldridge@marklogic.com>
 */
class Field implements JsonSerializable
{
    public $properties = array(); // @var array

    /**
     * Create a Field object.
     *
     * @param array $properties Associative array of config properties.
     */
    public function __construct($properties)
    {
        if (!is_array($properties['field-path'])) {
            $properties['field-path'] = array($properties['field-path']);
        }
        if (!is_array($properties['included-element'])) {
            $properties['included-element'] = array($properties['included-element']);
        }
        if (!is_array($properties['excluded-element'])) {
            $properties['excluded-element'] = array($properties['excluded-element']);
        }
        $this->properties = array_merge(array(
            'field-name' => '',
            'field-path' => '',
            'included-element' => '',
            'excluded-element' => ''
        ), $properties);
    public function jsonSerialize() {
        return $this->arr;
    }

    /**
     *
     * Add a path to the field.
     *
     * @param FieldPath path A FieldPath object.
     */
    public function addPath($path)
    {
        array_unshift($this->arr['field-path'], $path);
    }

    /**
     *
     * Add an included element to the field.
     *
     * @param FieldElementIncluded incl A FieldElementIncluded object.
     */
    public function addIncluded($incl)
    {
        array_unshift($this->arr['included-element'], $incl);
    }

    /**
     *
     * Add an excluded element to the field.
     *
     * @param FieldElementExcluded excl A FieldElementExcluded object.
     */
    public function addExcluded($excl)
    {
        array_unshift($this->arr['excluded-element'], $excl);
    }

}
