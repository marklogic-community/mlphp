<?php

/**
 * Represents a search-facet value.
 *
 * @package mlphp\search
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