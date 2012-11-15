<?php

/**
 * Represents permission metadata.
 *
 * @package mlphp\document
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