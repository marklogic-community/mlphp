<?php
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