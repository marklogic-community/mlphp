<!DOCTYPE html>
<html lang="en-us">
<head>
<title>Example: Perform Searches</title>
<link type="text/css" href="styles.css" rel="stylesheet">
</head>

<div id="wrapper">

<div class="examples-subtitle"><a href="index.php">Example Code</a></div>
<h1>Perform Searches</h1>

<div class="code-links"><a href="example_search_display.php">Display code</a> | <strong>Execute code</strong></div>

<pre>
<?php
require_once ('setup.php');	// Define $mlphp properties

// Create a REST client object for the MarkLogic database
$client = new RESTClient($mlphp['host'], $mlphp['port'], $mlphp['path'], $mlphp['version'],
                         $mlphp['username-admin'], $mlphp['password-admin'], $mlphp['auth']);


// Load some documents
$doc = new Document($client);
$doc->setContentFile("hamlet.xml")->write("/" . "hamlet.xml");
$doc->setContentFile("macbeth.xml")->write("/" . "macbeth.xml");
$doc->setContentFile("example.json")->write("/" . "example.json");


// Search for a string
$search = new Search($client);
$search->setPageLength(2);
$results = $search->retrieve('donalbain');
echo "Simple text search results:\n\n";
print_r($results);
echo "\n\n\n";


// Search by key-value for an element
$search->setPageLength(1);
$results = $search->retrieveKeyValueElement('PLAYSUBT', '', 'HAMLET');
echo "Key-value (for an element) search results:\n\n";
print_r($results);
echo "\n\n\n";


// Search by key-value for a JSON property
$search->setPageLength(1);
$results = $search->retrieveKeyValue('planet', 'Earth');
echo "Key-value (for JSON) search results:\n\n";
print_r($results);
echo "\n\n\n";

?>

</div>

</body>
</html>