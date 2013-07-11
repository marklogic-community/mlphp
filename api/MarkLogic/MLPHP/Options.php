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
 * Represents search options.
 *
 * @package MLPHP
 * @author Mike Wooldridge <mike.wooldridge@marklogic.com>
 * @see Parameter list: http://docs.marklogic.com/search:search
 * @todo Support complete of properties (see below)
 */
class Options
{
    private $dom; // @var DOMDocument
    private $restClient; // @var RESTClient

    private $constraints; // @var array of constraint objects
    private $values; // @var array of Values objects
    private $extracts; // @var Extracts object (handles 'extract-metadata' parameter)
    private $transformResults; // @var TransformResults object
    private $term; // @var Term object

    private $additionalQuery; // @var string
    private $concurrencyLevel; // @var int
    private $debug; // @var bool
    private $forest; // @var int
    private $pageLength; // @var int
    private $qualityWeight; // @var float
    private $returnConstraints; // @var string
    private $returnFacets; // @var string
    private $returnMetrics; // @var string
    private $returnPlan; // @var string
    private $returnQtext; // @var string
    private $returnQuery; // @var string
    private $returnResults; // @var string
    private $returnSimilar; // @var string
    private $searchOption; // @var array

    // TODO (*** higher priority):
    // custom constraint
    // bucketed range constraint ***
    // default-suggestion-source
    // grammar
    // operator ***
    // searchable-expression
    // sort-order ***
    // suggestion-source
    // term ***
    // tuples

    /**
     * Create an Options object.
     *
     * @param RESTClient $restClient A REST client object.
     */
    public function __construct($restClient)
    {
        $this->restClient = $restClient;
        $this->dom = new \DOMDocument();
        $this->constraints = array();
        $this->values = array();
    }

    /**
     * Add a constraint.
     *
     * @param mixed $constraint constraint object.
     */
    public function addConstraint($constraint)
    {
        $this->constraints[] = $constraint;
    }

    /**
     * Add a values setting.
     *
     * @param Values $values Values object.
     */
    public function addValues($values)
    {
        $this->values[] = $values;
    }

    /**
     * Set the metadata extracts.
     *
     * @param Extracts $extracts A metadata extracts object.
     */
    public function setExtracts($extracts)
    {
        $this->extracts = $extracts;
    }

    /**
     * Set the transform-results setting.
     *
     * @param TransformResults $transformResults A TransformResults object.
     */
    public function setTransformResults($transformResults)
    {
        $this->transformResults = $transformResults;
    }

    /**
     * Set the term setting.
     *
     * @param Term $term A Term object.
     */
    public function setTerm($term)
    {
        $this->term = $term;
    }

    /**
     * Get the search options as XML.
     *
     * @return string The search options as XML.
     */
    public function getAsXML()
    {
        // root
        $this->dom = new \DOMDocument(); // reset so we don't continually to add to the options node
        $root = $this->dom->createElement('options');
        $root->setAttribute('xmlns', 'http://marklogic.com/appservices/search');
        $this->dom->appendChild($root);

        // constraints
        foreach ($this->constraints as $constraint) {
            $root->appendChild($constraint->getAsElem($this->dom));
        }

        // values
        foreach ($this->values as $value) {
            $root->appendChild($value->getValuesAsElem($this->dom));
        }

        // metadata extracts
        if(isset($this->extracts)) {
            $extractsElem = $this->extracts->getExtractsAsElem($this->dom);
            $root->appendChild($extractsElem);
        }

        // transform results (snippetting)
        if(isset($this->transformResults)) {
            $transElem = $this->transformResults->getTransformResultsAsElem($this->dom);
            $root->appendChild($transElem);
        }

        // term
        if(isset($this->term)) {
            $termElem = $this->term->getAsElem($this->dom);
            $root->appendChild($termElem);
        }

        $this->addOption($root, 'additional-query', $this->additionalQuery);
        $this->addOption($root, 'concurrency-level', $this->concurrencyLevel);
        $this->addOption($root, 'debug', $this->debug);
        $this->addOption($root, 'forest', $this->forest);
        $this->addOption($root, 'page-length', $this->pageLength);
        $this->addOption($root, 'quality-weight', $this->qualityWeight);
        $this->addOption($root, 'return-constraints', $this->returnConstraints);
        $this->addOption($root, 'return-facets', $this->returnFacets);
        $this->addOption($root, 'return-metrics', $this->returnMetrics);
        $this->addOption($root, 'return-plan', $this->returnPlan);
        $this->addOption($root, 'return-qtext', $this->returnQtext);
        $this->addOption($root, 'return-query', $this->returnQuery);
        $this->addOption($root, 'return-results', $this->returnResults);
        $this->addOption($root, 'return-similar', $this->returnSimilar);
        if (!empty($this->searchOptions)) {
            foreach($this->searchOptions as $opt) {
                $this->addOption($root, 'search-option', $this->returnSimilar);
            }
        }
        return $this->dom->saveXML();
    }

    /**
     * If an option value is set, create a DOM element with an option name and value and append it to the root element of the options node.
     *
     * @param DOMElement $root The root element of the options node.
     * @param string $name The element name (option name).
     * @param string $value The node value.
     */
    public function addOption($root, $name, $value)
    {
        if (isset($value)) {
            $elem = $this->dom->createElement($name);
            $elem->nodeValue = $value;
            $root->appendChild($elem);
        }
    }

    /**
     * Read the search options from the database.
     *
     * @param string $name The search options name.
     * @return string The search options as XML.
     */
    public function read($name = 'all')
    {
        try {
            $params = array('format' => 'xml');
            $request = new RESTRequest('GET', 'config/query/' . $name, $params);
            $response = $this->restClient->send($request);
            return $response->getBody();
        } catch(Exception $e) {
            echo $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine() . PHP_EOL;
        }
    }

    /**
     * Write the search options to the database.
     *
     * @param string $name The search options name.
     * @return RESTResponse RESTResponse object.
     */
    public function write($name = 'all')
    {
        try {
            $params = array('format' => 'xml');
            $headers = array('Content-type' => 'application/xml');
            $request = new RESTRequest('PUT', 'config/query/' . $name, $params, $this->getAsXML(), $headers);
            $response = $this->restClient->send($request);
            //print_r($response);
            return $response;
        } catch(Exception $e) {
            echo $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine() . PHP_EOL;
        }
    }

    /**
     * Delete the search options from the database.
     *
     * @param string $name The search options name.
     * @return Options $this
     */
    public function delete($name)
    {
        try {
            $request = new RESTRequest('DELETE', 'config/query/' . $name);
            $response = $this->restClient->send($request);
            return $this;
        } catch(Exception $e) {
            echo $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine() . PHP_EOL;
        }
    }

    /**
     * Get the additional-query setting.
     *
     * @return string The additional-query setting.
     */
    public function getAdditionalQuery()
    {
        return $this->additionalQuery;
    }

    /**
     * Set The additional-query setting.
     *
     * @see http://docs.marklogic.com/search:search#opt-additional-query
     *
     * @param string $additionalQuery The additional-query setting.
     */
    public function setAdditionalQuery($additionalQuery)
    {
        $this->additionalQuery = (string)$additionalQuery;
    }

    /**
     * Get the concurrency level.
     *
     * @return int The concurrency level.
     */
    public function getConcurrencyLevel()
    {
        return $this->concurrencyLevel;
    }

    /**
     * Set The concurrency level.
     *
     * @see http://docs.marklogic.com/search:search#opt-concurrency-level
     *
     * @param int $concurrencyLevel The concurrency level.
     */
    public function setConcurrencyLevel($concurrencyLevel)
    {
        $this->concurrencyLevel = (int)$concurrencyLevel;
    }

    /**
     * Get the debug setting.
     *
     * @return string The debug setting, 'true' or 'false'.
     */
    public function getDebug()
    {
        return $this->debug;
    }

    /**
     * Set the debug setting.
     *
     * @see http://docs.marklogic.com/search:search#opt-debug
     *
     * @param string $debug The debug setting, 'true' or 'false'.
     */
    public function setDebug($debug)
    {
        $this->debug = (string)$debug;
    }

    /**
     * Get the forest ID.
     *
     * @return int The forest ID.
     */
    public function getForest()
    {
        return $this->forest;
    }

    /**
     * Set the forest ID.
     *
     * @see http://docs.marklogic.com/search:search#opt-forest
     *
     * @param int $forest The forest ID.
     */
    public function setForest($forest)
    {
        $this->forest = (int)$forest;
    }

    /**
     * Get the page length.
     *
     * @return int The page length.
     */
    public function getPageLength()
    {
        return $this->pageLength;
    }

    /**
     * Set the page length.
     *
     * @see http://docs.marklogic.com/search:search#opt-page-length
     *
     * @param int $pageLength The page length.
     */
    public function setPageLength($pageLength)
    {
        $this->pageLength = (int)$pageLength;
    }

    /**
     * Get the quality weight.
     *
     * @return float The quality weight.
     */
    public function getQualityWeight()
    {
        return $this->qualityWeight;
    }

    /**
     * Set the quality weight.
     *
     * @see http://docs.marklogic.com/search:search#opt-quality-weight
     *
     * @param float $qualityWeight The quality weight.
     */
    public function setQualityWeight($qualityWeight)
    {
        $this->qualityWeight = (float)$qualityWeight;
    }

    /**
     * Get the return-constraints setting.
     *
     * @return string The return-constraints setting.
     */
    public function getReturnConstraints()
    {
        return $this->returnConstraints;
    }

    /**
     * Set the return-constraints setting.
     *
     * @see http://docs.marklogic.com/search:search#opt-return-constraints
     *
     * @param string $returnConstraints The return-constraints setting, 'true' or 'false'.
     */
    public function setReturnConstraints($returnConstraints)
    {
        $this->returnConstraints = (string)$returnConstraints;
    }

    /**
     * Get the return-facets setting.
     *
     * @return string The return-facets setting.
     */
    public function getReturnFacets()
    {
        return $this->returnFacets;
    }

    /**
     * Set the return-facets setting.
     *
     * @see http://docs.marklogic.com/search:search#opt-return-facets
     *
     * @param string $returnFacets The return-facets setting, 'true' or 'false'.
     */
    public function setReturnFacets($returnFacets)
    {
        $this->returnFacets = (string)$returnFacets;
    }

    /**
     * Get the return-metrics setting.
     *
     * @return string The return-metrics setting.
     */
    public function getReturnMetrics()
    {
        return $this->returnMetrics;
    }

    /**
     * Set the return-metrics setting.
     *
     * @see http://docs.marklogic.com/search:search#opt-return-metrics
     *
     * @param string $returnMetrics The return-metrics setting, 'true' or 'false'.
     */
    public function setReturnMetrics($returnMetrics)
    {
        $this->returnMetrics = (string)$returnMetrics;
    }

    /**
     * Get the return-plan setting.
     *
     * @return string The return-plan setting.
     */
    public function getReturnPlan()
    {
        return $this->returnPlan;
    }

    /**
     * Set the return-plan setting.
     *
     * @see http://docs.marklogic.com/search:search#opt-return-plan
     *
     * @param string $returnPlan The return-plan setting, 'true' or 'false'.
     */
    public function setReturnPlan($returnPlan)
    {
        $this->returnPlan = (string)$returnPlan;
    }

    /**
     * Get the return-qtext setting.
     *
     * @return string The return-qtext setting.
     */
    public function getReturnQtext()
    {
        return $this->returnQtext;
    }

    /**
     * Set the return-qtext setting.
     *
     * @see http://docs.marklogic.com/search:search#opt-return-qtext
     *
     * @param string $returnQtext The return-qtext setting, 'true' or 'false'.
     */
    public function setReturnQtext($returnQtext)
    {
        $this->returnQtext = (string)$returnQtext;
    }

    /**
     * Get the return-query setting.
     *
     * @return string The return-query setting.
     */
    public function getReturnQuery()
    {
        return $this->returnQuery;
    }

    /**
     * Set the return-query setting.
     *
     * @see http://docs.marklogic.com/search:search#opt-return-query
     *
     * @param string $returnQuery The return-query setting, 'true' or 'false'.
     */
    public function setReturnQuery($returnQuery)
    {
        $this->returnQuery = (string)$returnQuery;
    }

    /**
     * Get the return-results setting.
     *
     * @return string The return-results setting.
     */
    public function getReturnResults()
    {
        return $this->returnResults;
    }

    /**
     * Set the return-results setting.
     *
     * @see http://docs.marklogic.com/search:search#opt-return-results
     *
     * @param string $returnResults The return-results setting, 'true' or 'false'.
     */
    public function setReturnResults($returnResults)
    {
        $this->returnResults = (string)$returnResults;
    }

    /**
     * Get the return-similar setting.
     *
     * @return string The return-similar setting.
     */
    public function getReturnSimilar()
    {
        return $this->returnSimilar;
    }

    /**
     * Set the return-similar setting.
     *
     * @see http://docs.marklogic.com/search:search#opt-return-similar
     *
     * @param string $returnSimilar The return-similar setting, 'true' or 'false'.
     */
    public function setReturnSimilar($returnSimilar)
    {
        $this->returnSimilar = (string)$returnSimilar;
    }

    /**
     * Get the search options.
     *
     * @return array The search options.
     */
    public function getSearchOptions()
    {
        return $this->searchOptions;
    }

    /**
     * Set the search options.
     *
     * @see http://docs.marklogic.com/search:search#opt-search-option
     *
     * @param string|array $searchOptions The search options as a string (single option) or array of strings.
     */
    public function setSearchOptions($searchOptions)
    {
        if (is_array($searchOptions)) {
            $this->searchOptions = array_unique(array_merge($this->searchOptions, $searchOptions));
        } else {
            $this->searchOptions[] = $searchOptions;
            $this->searchOptions = array_unique($this->searchOptions);
        }
    }
}
