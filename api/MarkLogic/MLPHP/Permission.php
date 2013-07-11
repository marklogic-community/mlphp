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
 * Represents permission metadata.
 *
 * @package MLPHP
 * @author Mike Wooldridge <mike.wooldridge@marklogic.com>
 */
class Permission
{
    private $roleName = ''; // @var string
    private $capabilities = array(); // @var array

    /**
     * Create a Permission object.
     *
     * @param string $roleName The role name for the permission.
     * @param array|string $capabilities An array of capability strings or a single capability string.
     */
    public function __construct($roleName = '', $capabilities = array())
    {
        $this->roleName = (string)$roleName;
        $this->addCapabilities($capabilities);
    }

    /**
     * Get the role name.
     *
     * @return string A role name.
     */
    public function getRoleName()
    {
        return $this->roleName;
    }

    /**
     * Set the role name.
     *
     * @param string $roleName The role name.
     */
    public function setRoleName($roleName)
    {
        $this->roleName = (string)$roleName;
    }

    /**
     * Get the capabilities.
     *
     * @return array An array of capability strings.
     */
    public function getCapabilities()
    {
        return $this->capabilities;
    }

    /**
     * Add capabilities.
     *
     * @param array|string $capabilities An array of capability strings or a single capability string.
     */
    public function addCapabilities($capabilities)
    {
        if (is_array($capabilities)) {
            $this->capabilities = array_merge((array)$this->capabilities, (array)$capabilities);
        } else {
            $this->capabilities[] = (string)$capabilities;
        }
    }
}
