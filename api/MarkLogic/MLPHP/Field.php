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
    public $arr = array(); // @var array

    /**
     * Create a Field object.
     *
     * @param array $arr Associative array of config properties.
     */
    public function __construct($arr)
    {
        if (key_exists('field-path', $arr) && !is_array($arr['field-path'])) {
            $arr['field-path'] = array($arr['field-path']);
        }
        if (key_exists('included-element', $arr) && !is_array($arr['included-element'])) {
            $arr['included-element'] = array($arr['included-element']);
        }
        if (key_exists('excluded-element', $arr) && !is_array($arr['excluded-element'])) {
            $arr['excluded-element'] = array($arr['excluded-element']);
        }
        $this->arr = array_merge(array(
            'field-name' => '',
            'field-path' => array(),
            'included-element' => array(),
            'excluded-element' => array()
        ), $arr);
    }

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
