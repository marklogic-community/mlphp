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

// Load some documents
$doc = new MLPHP\Document($client);
$doc->setContentFile("hamlet.xml")->write("/" . "hamlet.xml");
$doc->setContentFile("macbeth.xml")->write("/" . "macbeth.xml");
$doc->setContentFile("example.json")->write("/" . "example.json");


// Search for a string
$search = new MLPHP\Search($client);
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
