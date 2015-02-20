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
 * Represents a range constraint bucket.
 *
 * @package MLPHP
 * @author Mike Wooldridge <mike.wooldridge@marklogic.com>
 */
class Bucket
{
    protected $name; // @var string
    protected $options; // @var string

    /**
     * Create a Bucket object.
     *
     * @param string $name A bucket name.
     * @param array $options An assoc array of options.
     */
    public function __construct($name, $options)
    {
        $this->name = $name;
        $this->options = $options;
    }

    /**
     * Get the path.
     *
     * @return string The path.
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the bucket options.
     *
     * @return array The assoc array of bucket options.
     */
    public function getOptions()
    {
        return $this->options;
    }

}
