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
 * Represents document metadata.
 *
 * @package MLPHP
 * @author Mike Wooldridge <mike.wooldridge@marklogic.com>
 */
class Metadata
{
    private $collections; // @var array
    private $permissions; // @var associative array of Permission objects with role names as keys
    private $properties; // @var array
    private $quality; // @var int

    /**
     * Create a Metadata object.
     */
    public function __construct()
    {
        $this->collections = array();
        $this->permissions = array();
        $this->properties = array();
    }

    /**
     * Get the collections metadata.
     *
     * @return array The collections as an array of strings.
     */
    public function getCollections()
    {
        return $this->collections;
    }

    /**
     * Add collections metadata.
     *
     * @param array|string $collections An array of collection strings or a collection string.
     * @return Metadata $this
     */
    public function addCollections($collections)
    {
        if (is_array($collections)) {
            $this->collections = array_merge($this->collections, $collections);
        } else {
            $this->collections[] = (string)$collections;
        }
        return $this;
    }

    /**
     * Delete collections metadata.
     *
     * @param array|string $collections An array of collection strings or a collection string.
     * @return Metadata $this
     */
    public function deleteCollections($collections)
    {
        if (is_array($collections)) {
            foreach($collections as $coll) {
                $pos = array_search($coll, $this->collections);
                if($pos !== FALSE) {
                    array_splice($this->collections, $pos, 1);
                }
            }
        } else {
            $pos = array_search($collections, $this->collections);
            if($pos !== FALSE) {
                array_splice($this->collections, $pos, 1);
            }
        }
        return $this;
    }

    /**
     * Get the permissions metadata.
     *
     * @return array The permissions as an array of Permissions objects.
     */
    public function getPermissions()
    {
        return array_values($this->permissions);
    }

    /**
     * Add permissions metadata.
     *
     * @param array|Permission $permissions An array of Permission objects or a Permission object.
     * @return Metadata $this
     */
    public function addPermissions($permissions)
    {
        if (is_array($permissions)) {
            foreach($permissions as $perm) {
                $this->permissions[$perm->getRoleName()] = $perm;
            }
        } else {
            $this->permissions[$permissions->getRoleName()] = $permissions;
        }
        return $this;
    }

    /**
     * Delete permissions metadata.
     *
     * @param array|string $roleNames An array of role names or a role name string.
     * @return Metadata $this
     */
    public function deletePermissions($roleNames)
    {
        if (is_array($roleNames)) {
            foreach($roleNames as $name) {
                unset($this->permissions[$name]);
            }
        } else {
            unset($this->permissions[$roleNames]);
        }
        return $this;
    }

    /**
     * Get property metadata.
     *
     * @return array An associative array of property key/value pairs.
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * Add property metadata.
     *
     * @param array $properties An associative array of property key/value pairs.
     * @return Metadata $this
     */
    public function addProperties($properties)
    {
        $this->properties = array_merge($this->properties, $properties);
        return $this;
    }

    /**
     * Delete property metadata.
     *
     * @param array|string $properties An array of property keys or a property key.
     * @return Metadata $this
     */
    public function deleteProperties($properties)
    {
        if (is_array($properties)) {
            foreach($properties as $prop) {
                unset($this->properties[$prop]);
            }
        } else {
            unset($this->properties[$properties]);
        }
        return $this;
    }

    /**
     * Get quality metadata.
     *
     * @return int A quality value.
     */
    public function getQuality()
    {
        return $this->quality;
    }

    /**
     * Set quality metadata.
     *
     * @param int $quality A quality value.
     * @return Metadata $this
     */
    public function setQuality($quality)
    {
        $this->quality = (int)$quality;
        return $this;
    }

    /**
     * Get the metadata object as XML.
     *
     * @return string The metadata as XML.
     */
    public function getAsXML()
    {

        // root
        $dom = new \DOMDocument();
        $root = $dom->createElement('metadata');
        $root->setAttribute('xmlns', 'http://marklogic.com/rest-api');
        $dom->appendChild($root);

        // collections
        $collElem = $dom->createElement('collections');
        foreach($this->collections as $v) {
            $collElem->appendChild($dom->createElement('collection', $v));
        }
        $root->appendChild($collElem);

        // permissions
        $permElem = $dom->createElement('permissions');
        foreach($this->permissions as $rn => $p) {
            $pElem = $dom->createElement('permission');
            $pElem->appendChild($dom->createElement('role-name', $p->getRoleName()));
            foreach($p->getCapabilities() as $c) {
                $pElem->appendChild($dom->createElement('capability', $c));
            }
            $permElem->appendChild($pElem);
        }
        $root->appendChild($permElem);

        // properties
        $propElem = $dom->createElement('prop:properties');
        $propElem->setAttribute('xmlns:prop', 'http://marklogic.com/xdmp/property');
        foreach($this->properties as $k => $v) {
            $pElem = $dom->createElement($k, $v);
            $pElem->setAttribute('xmlns', '');
            $propElem->appendChild($pElem);
        }
        $root->appendChild($propElem);

        // quality
        if(!empty($this->quality)) {
            $qElem = $dom->createElement('quality', $this->quality);
            $root->appendChild($qElem);
        }

        return $dom->saveXML();
    }


    /**
     * Load the metadata object from XML.
     *
     * @param string $xml The metadata as XML.
     * @return Metadata $this
     */
    public function loadFromXML($xml)
    {

        // root
        $dom = new \DOMDocument();
        $dom->loadXML($xml);

        // collections
        $collElems = $dom->getElementsByTagName('collections')->item(0)->getElementsByTagName('collection');
        foreach ($collElems as $elem) {
            if($elem->nodeType === XML_ELEMENT_NODE) {
                $this->addCollections($elem->nodeValue);
            }
        }

        // permissions
        $permElems = $dom->getElementsByTagName('permissions')->item(0)->getElementsByTagName('permission');
        $permissions = array();
        foreach($permElems as $elem) {
            if($elem->nodeType === XML_ELEMENT_NODE) {
                $role_name = $elem->getElementsByTagName('role-name')->item(0)->nodeValue;
                $capElems = $elem->getElementsByTagName('capability');
                $capabilities = array();
                foreach($capElems as $c) {
                    $capabilities[] = $c->nodeValue;
                }
                $permission = new Permission($role_name, $capabilities);
                $permissions[] = $permission;
            }
        }
        $this->addPermissions($permissions);

        // properties
        $propElems = $dom->getElementsByTagName('properties')->item(0)->childNodes;
        foreach($propElems as $elem) {
            if($elem->nodeType === XML_ELEMENT_NODE) {
                $this->addProperties(array($elem->tagName => $elem->nodeValue));
            }
        }

        // quality
        $qElem = $dom->getElementsByTagName('quality')->item(0);
        $this->setQuality($qElem->nodeValue);

        return $this;
    }
}
