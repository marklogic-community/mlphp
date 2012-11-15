<?php

/**
 * Represents a term element in search options.
 *
 * @package mlphp\options
 * @author Mike Wooldridge <mike.wooldridge@marklogic.com>
 * @see http://docs.marklogic.com/guide/rest-dev/appendixb#id_70754
 * @todo Handle term options, additional default behavior, etc.
 */
class Term
{
    private $empty; // @var string
    private $termOptions; // @var array

    /**
     * Create a Term object.
     *
     * @param string $empty Setting for resolving empty searches (example: 'no-results').
     */
    public function __construct($empty = '')
    {
        $this->empty = (string)$empty;
    }

    /**
     * Set the empty setting.
     *
     * @param string $empty Setting for resolving empty searches (example: 'no-results').
     */
    public function setEmpty($empty)
    {
        $this->empty = (string)$empty;
    }

    /**
     * Set term options.
     *
     * @param array $termOptions An array of term options.
     */
    public function setTermOptions($termOptions)
    {
        $this->termOptions = $termOptions;
    }

    /**
     * Get the term as a DOMElement object.
     *
     * @param DOMDocument $dom The DOMDocument object with which to create the element.
     */
    public function getAsElem($dom)
    {
        $termElem = $dom->createElement('term');
        $emptyElem = $dom->createElement('empty');
        $emptyElem->setAttribute('apply', $this->empty);
        $termElem->appendChild($emptyElem);
        if (!empty($this->termOptions)) {
            foreach($this->termOptions as $opt) {
                $termOptElem = $dom->createElement('term-option');
                $termOptElem->nodeValue = $opt;
                $termElem->appendChild($termOptElem);
            }
        }
        /* <term>
               <empty apply="no-results" />
               <term-option>diacritic-insensitive</term-option>
               <term-option>unwildcarded</term-option>
            </term> */
        return $termElem;
    }
}