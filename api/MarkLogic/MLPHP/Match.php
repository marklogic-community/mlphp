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
 * Represents a snippet match.
 *
 * @package MLPHP
 * @author Mike Wooldridge <mike.wooldridge@marklogic.com>
 */
class Match
{
    private $path; // @var string
    private $content; // @var string

    /**
     * Create a Match object.
     *
     * @param DOMNode $elem A DOMNode object for a match.
     */
    public function __construct($elem)
    {
        $this->path = $elem->getAttribute('path');
        $this->content = '';
        foreach($elem->childNodes as $node) {
            if($node->nodeName === 'search:highlight') {
                $this->content .= '<span class="highlight">' . $node->nodeValue . '</span>';
            } else {
                $this->content .= $node->nodeValue;
            }
        }
    }

    /**
     * Get the path.
     *
     * @return string The path.
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set the path.
     *
     * @param string $path The path.
     */
    public function setPath($path)
    {
        $this->path = (string)$path;
    }

    /**
     * Get the content.
     *
     * @return string The content.
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set the content.
     *
     * @param string $content The content.
     */
    public function setContent($content)
    {
        $this->content = (string)$content;
    }
}
