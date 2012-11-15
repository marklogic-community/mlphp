<!DOCTYPE html>
<html lang="en-us">
<head>
<title>Example Code: Define Search Options</title>
<link type="text/css" href="styles.css" rel="stylesheet">
<link type="text/css" href="../external/prettify/prettify.css" rel="stylesheet">
<script type="text/javascript" src="../external/prettify/prettify.js"></script>
</head>

<body onload="prettyPrint()">

<div id="wrapper">

<div class="examples-subtitle"><a href="index.php">Example Code</a></div>
<h1>Define Search Options</h1>

<div class="code-links"><strong>Display code</strong> | <a href="example_opts.php">Execute code</a></div>

<pre class="prettyprint lang-html">
// Create a REST client object for the MarkLogic database
$client = new RESTClient($mlphp['host'], $mlphp['port'], $mlphp['path'], $mlphp['version'],
                         $mlphp['username-admin'], $mlphp['password-admin'], $mlphp['auth']);


<span class="code-section">// Range constraint and extracted metadata</span>
$options1 = new Options($client);	// Create an Options object (passing the REST client)

$range = new RangeConstraint('film',				// Set constraint name
                             'xs:string',			// Set constraint datatype
                             'true',				// Set facet option
                             'film-title',			// Set element name
                             'http://marklogic.com/wikipedia',	// Set element namespace
                             '',					// Set attribute
                             '');				// Set attribute namespace
$range->setFragmentScrope('documents');		// Set fragment scope
$options1->addConstraint($range);		// Add the constraint to the Options object

$extracts1 = new Extracts();			// Create an Extracts object (for extracted metadata)
$extracts1->addConstraints('film');		// Add the constraint as extracted metadata
$options1->setExtracts($extracts1);		// Set the extracted metadata in the Options object

$options1->write('options1');			// Write the search options to the database

// Read the options from the database and display
echo "Range constraint and extracted metadata:\n\n";
echo htmlspecialchars($options1->read('options1'));
echo "\n\n";


<span class="code-section">// Value constraint with snippet options</span>
$options2 = new Options($client);	// Create an Options object (passing the REST client)

$value = new ValueConstraint('person', 				// Set constraint name
                             'name', 				// Set element name
                             'http://marklogic.com/wikipedia');	// Set element namespace
$options2->addConstraint($value);	// Add the constraint to the Options object

$transform2 = new TransformResults('metadata-snippet');	// Create an TransformResults object (for snippetting)
$pref1 = new PreferredElement('description',			// Set element name
                              'http://marklogic.com/wikipedia');	// Set element namespace
$pref2 = new PreferredElement('personal',			// Set element name
                              'http://marklogic.com/wikipedia');	// Set element namespace
$transform2->addPreferredElements(array($pref1, $pref2));	// Add to the TransformResults object
$options2->setTransformResults($transform2);			// Add	 to the Options object

$options2->write('options2');					// Write the search options to the database

// Read the options from the database and display
echo "Value constraint with snippet options:\n\n";
echo htmlspecialchars($options2->read('options2'));
echo "\n\n";


<span class="code-section">// Word constraint and search option</span>
$options3 = new Options($client);	// Create an Options object (passing the REST client)

$word = new WordConstraint('abstract', 							// Set constraint name
                           'abstract', 							// Set element name
                           'http://marklogic.com/wikipedia');	// Set element namespace
$options3->addConstraint($word);		// Add the constraint to the Options object

$options3->setReturnSimilar('true');	// Return similar documents

$options3->write('options3');		// Write the search options to the database

// Read the options from the database and display
echo "Word constraint and a search-option:\n\n";
echo htmlspecialchars($options3->read('options3'));


<span class="code-section">// Field word constraint</span>
$options4 = new Options($client);	// Create an Options object (passing the REST client object)

$field = new FieldWordConstraint('summary', 	// Set constraint name
                                 'summary');	// Set field namespace
$options4->addConstraint($field);		// Add the constraint to the Options object

$options4->setDebug('true');			// Return debugging info

$options4->write('options4');			// Write the search options to the database

// Read the options from the database and display
echo "Field word constraint:\n\n";
echo htmlspecialchars($options4->read('options4'));
echo "\n\n";


<span class="code-section">// Collection constraint with metadata</span>
$options5 = new Options($client);	// Create an Options object (passing the REST client object)

$collection = new CollectionConstraint('tag', 				// Set constraint name
                                       'http://example.com/tag/');	// Set prefix
$options5->addConstraint($collection);			// Add the constraint to the Options object

$extracts5 = new Extracts();				// Create an Extracts object (for extracted metadata)
$extracts5->addQName('film-title',			// Set element name
                     'http://marklogic.com/wikipedia');	// Set element namespace
$options5->setExtracts($extracts5);			// Set the extracted metadata in the Options object

$options5->write('options5');				// Write the search options to the database

// Read the options from the database and display
echo "Collection constraint with metadata:\n\n";
echo htmlspecialchars($options5->read('options5'));
echo "\n\n";


<span class="code-section">// Element-query constraint</span>
$options6 = new Options($client);	// Create an Options object (passing the REST client object)

$eq = new ElementQueryConstraint('title',		// Set constraint name
                                 'title',		// Set element name
                                 'http://my/namespace');	// Set element namespace
$options6->addConstraint($eq);	// Add the constraint to the Options object

$options6->write('options6');	// Write the search options to the database

// Read the options from the database and display
echo "Element-query constraint:\n\n";
echo htmlspecialchars($options6->read('options6'));
echo "\n\n";


<span class="code-section">// Various options</span>
$options7 = new Options($client);	// Create an Options object (passing the REST client object)

$options7->setConcurrencyLevel(16);	// Set concurrency level
$options7->setPageLength(20);		// Set page length
$options7->setQualityWeight(0.5);	// Set quality weight

$options7->write('options7');		// Write the search options to the database

// Read the options from the database and display
echo "Various options:\n\n";
echo htmlspecialchars($options7->read('options7'));
echo "\n\n";


<span class="code-section">// More options</span>
$options8 = new Options($client);		// Create an Options object (passing the REST client object)

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
// Read the options from the database (and format for display)
echo htmlspecialchars($options8->read('options8'));
echo "\n\n";


<span class="code-section">// Term element</span>
$options9 = new Options($client);	// Create an Options object (passing the REST client object)

$term = new Term('no-results');		// Create an Term object
$term->setTermOptions(array('diacritic-insensitive',	// Set a term setting
                            'unwildcarded'));		// Set a term setting
$options9->setTerm($term);		// Set the Term object in the options

$options9->write('options9');		// Write the search options to the database

// Read the options from the database and display
echo "Term options:\n\n";
echo htmlspecialchars($options9->read('options9'));
echo "\n\n";

</pre>

</div>

</body>
</html>