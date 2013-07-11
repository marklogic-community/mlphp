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
// Setup
use MarkLogic\MLPHP as MLPHP;
require_once ('setup.php');
$client = $mlphp->newClient();
$client->setUsername($mlphp->config['username-admin']);
$client->setPassword($mlphp->config['password-admin']);

// Range constraint and extracted metadata
$options1 = new MLPHP\Options($client);	// Create an Options object (passing the REST client)

$range = new MLPHP\RangeConstraint('film',				// Set constraint name
                                   'xs:string',				// Set constraint datatype
                                   'true',				// Set facet option
                                   'film-title',				// Set element name
                                   'http://marklogic.com/wikipedia',	// Set element namespace
                                   '',					// Set attribute
                                   '');					// Set attribute namespace
$range->setFragmentScrope('documents');		// Set fragment scope
$options1->addConstraint($range);		// Add the constraint to the Options object

$extracts1 = new MLPHP\Extracts();		// Create an Extracts object (for extracted metadata)
$extracts1->addConstraints('film');		// Add the constraint as extracted metadata
$options1->setExtracts($extracts1);		// Set the extracted metadata in the Options object

$options1->write('options1');			// Write the search options to the database

// Read the options from the database and display
echo "Range constraint and extracted metadata:\n\n";
echo htmlspecialchars($options1->read('options1'));
echo "\n\n";


// Value constraint with snippet options
$options2 = new MLPHP\Options($client);	// Create an Options object (passing the REST client)

$value = new MLPHP\ValueConstraint('person', 			// Set constraint name
                             'name', 				// Set element name
                             'http://marklogic.com/wikipedia');	// Set element namespace
$options2->addConstraint($value);	// Add the constraint to the Options object

$transform2 = new MLPHP\TransformResults('metadata-snippet');	// Create an TransformResults object (snippet)
$pref1 = new MLPHP\PreferredElement('description',		// Set element name
                              'http://marklogic.com/wikipedia');	// Set element namespace
$pref2 = new MLPHP\PreferredElement('personal',			// Set element name
                              'http://marklogic.com/wikipedia');	// Set element namespace
$transform2->addPreferredElements(array($pref1, $pref2));	// Add to the TransformResults object
$options2->setTransformResults($transform2);			// Add	 to the Options object

$options2->write('options2');					// Write the search options to the database

// Read the options from the database and display
echo "Value constraint with snippet options:\n\n";
echo htmlspecialchars($options2->read('options2'));
echo "\n\n";


// Word constraint and search option
$options3 = new MLPHP\Options($client);	// Create an Options object (passing the REST client)

$word = new MLPHP\WordConstraint('abstract', 				// Set constraint name
                                 'abstract', 				// Set element name
                                 'http://marklogic.com/wikipedia');	// Set element namespace
$options3->addConstraint($word);		// Add the constraint to the Options object

$options3->setReturnSimilar('true');	// Return similar documents

$options3->write('options3');		// Write the search options to the database

// Read the options from the database and display
echo "Word constraint and a search-option:\n\n";
echo htmlspecialchars($options3->read('options3'));


// Field word constraint
$options4 = new MLPHP\Options($client);	// Create an Options object (passing the REST client object)

$field = new MLPHP\FieldWordConstraint('summary', // Set constraint name
                                       'summary');// Set field namespace
$options4->addConstraint($field);		// Add the constraint to the Options object

$options4->setDebug('true');			// Return debugging info

$options4->write('options4');			// Write the search options to the database

// Read the options from the database and display
echo "Field word constraint:\n\n";
echo htmlspecialchars($options4->read('options4'));
echo "\n\n";


// Collection constraint with metadata
$options5 = new MLPHP\Options($client);	// Create an Options object (passing the REST client object)

$collection = new MLPHP\CollectionConstraint('tag', 			// Set constraint name
                                             'http://example.com/tag/');	// Set prefix
$options5->addConstraint($collection);			// Add the constraint to the Options object

$extracts5 = new MLPHP\Extracts();			// Create an Extracts object (for extracted metadata)
$extracts5->addQName('film-title',			// Set element name
                     'http://marklogic.com/wikipedia');	// Set element namespace
$options5->setExtracts($extracts5);			// Set the extracted metadata in the Options object

$options5->write('options5');				// Write the search options to the database

// Read the options from the database and display
echo "Collection constraint with metadata:\n\n";
echo htmlspecialchars($options5->read('options5'));
echo "\n\n";


// Element-query constraint
$options6 = new MLPHP\Options($client);	// Create an Options object (passing the REST client object)

$eq = new MLPHP\ElementQueryConstraint('title',			// Set constraint name
                                       'title',			// Set element name
                                       'http://my/namespace');	// Set element namespace
$options6->addConstraint($eq);	// Add the constraint to the Options object

$options6->write('options6');	// Write the search options to the database

// Read the options from the database and display
echo "Element-query constraint:\n\n";
echo htmlspecialchars($options6->read('options6'));
echo "\n\n";


// Various options
$options7 = new MLPHP\Options($client);	// Create an Options object (passing the REST client object)

$options7->setConcurrencyLevel(16);	// Set concurrency level
$options7->setPageLength(20);		// Set page length
$options7->setQualityWeight(0.5);	// Set quality weight

$options7->write('options7');		// Write the search options to the database

// Read the options from the database and display
echo "Various options:\n\n";
echo htmlspecialchars($options7->read('options7'));
echo "\n\n";


// More options
$options8 = new MLPHP\Options($client);		// Create an Options object (passing the REST client object)

$options8->setReturnConstraints('true');		// Return constraints
$options8->setReturnFacets('false');		// Return facets
$options8->setReturnMetrics('true');		// Return metrics
$options8->setReturnPlan('false');		// Return the plan
$options8->setReturnQtext('true');		// Return the qtext
$options8->setReturnQuery('false');		// Return the query
$options8->setReturnResults('true');		// Return results
$options8->setReturnSimilar('false');		// Return similar documents

$options8->write('options8');			// Write the search options to the database
echo "More options:\n\n";
// Read the options from the database and display
echo htmlspecialchars($options8->read('options8'));
echo "\n\n";


// Term element
$options9 = new MLPHP\Options($client);	// Create an Options object (passing the REST client object)

$term = new MLPHP\Term('no-results');	// Create an Term object
$term->setTermOptions(array('diacritic-insensitive',	// Set a term setting
                            'unwildcarded'));		// Set a term setting
$options9->setTerm($term);		// Set the Term object in the options

$options9->write('options9');		// Write the search options to the database

// Read the options from the database and display
echo "Term options:\n\n";
echo htmlspecialchars($options9->read('options9'));
echo "\n\n";

// Delete the example options from the database
$num = 9;
for ($i = 1; $i <= $num; $i++) {
    $options1->delete('options' . $i);
}
