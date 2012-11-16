<?php
// 1. Complete the setup steps: mlphp/README.md

// 2. Tell the app how to talk to MarkLogic.
$mlphp = array(
    'username' => 'rest-writer-user',
    'password' => 'writer-pw',
    'host'     => 'localhost',
    'port'     => 8077,
    'version'  => 'v1',
    'auth'     => 'digest'
);

// 3. Create a REST client that talks to MarkLogic.
require_once('api/RESTClient.php');
$client = new RESTClient($mlphp['host'], $mlphp['port'], '',
                         $mlphp['version'], $mlphp['username'],
                         $mlphp['password'], $mlphp['auth']);

// 4. Add a document to the MarkLogic database.
require_once('api/Document.php');
$document = new Document($client);
$document->setContent('<app><description>My first MLPHP app.</description></app>');
$document->write('/myfirstapp.xml');

// 5. Search the MarkLogic database.
require_once('api/Search.php');
$search = new Search($client);
$results = $search->retrieve('MLPHP');

// 6. Display a result.
echo '<html>';
echo '<style>.highlight { background-color: yellow; }</style>';
if ($results->getTotal() > 0) {
    $matches = $results->getResultByIndex(1)->getMatches(); //->getMatches();
    echo $matches[0]->getContent();
} else {
    echo 'No results found.';
}
echo '</html>';
?>