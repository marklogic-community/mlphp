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

// A Simple MLPHP Application

// 1. Complete the installation steps, see: mlphp/README.md

// 2. Tell the app how to talk to MarkLogic.  

// Adjust the path below as needed to find the MLPHP project's vendor directory.
require_once __DIR__ . '/../vendor/autoload.php';

use MarkLogic\MLPHP;

// 3. Create a REST client that talks to MarkLogic.

$mlphp = new MLPHP\MLPHP(array(
    'username' => 'rest-writer-user',
    'password' => 'writer-pw',
    'host'     => 'localhost',
    'port'     => 8077,
    'version'  => 'v1',
    'auth'     => 'digest'
));

$client = $mlphp->newClient();

// 4. Add a document to the MarkLogic database.
$document = new MLPHP\Document($client);
$document->setContent('<app><description>My first MLPHP app.</description></app>');
$document->write('/myfirstapp.xml');

// 5. Search the MarkLogic database.
$search = new MLPHP\Search($client);
$results = $search->retrieve('MLPHP');

// 6. Display a result.
echo '<html>';
echo '<style>.highlight { background-color: yellow; }</style>';
if ($results->getTotal() > 0) {
    $matches = $results->getResultByIndex(1)->getMatches();
    echo $matches[0]->getContent();
} else {
    echo 'No results found.';
}
echo '</html>';
?>
