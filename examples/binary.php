<?php
// Set up global vars and class autoloading
require_once ('setup.php');

// Create a REST client object for the MarkLogic database
$client = new RESTClient($mlphp['host'], $mlphp['port'], $mlphp['path'], $mlphp['version'],
                         $mlphp['username'], $mlphp['password'], $mlphp['auth']);

$doc = new Document($client);
$uri = $_REQUEST['uri'];
$content = $doc->read($_REQUEST['uri']);
$filename = end(explode('/', $uri));
header('Content-Type: ' . $doc->getContentType());
header('Content-Disposition: attachment; filename=' . $filename);
echo $content;