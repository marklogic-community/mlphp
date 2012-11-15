<?php

/**
 * Represents a snippet match.
 *
 * @package mlphp\search
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
        $this->path = $elem->getAttribute('patch');
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