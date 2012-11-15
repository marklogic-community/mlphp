<?php
require_once 'setup.php' ;

$client = new RESTClient($mlphp['host'], $mlphp['port'], $mlphp['path'], $mlphp['version'], $mlphp['username'], $mlphp['password'], $mlphp['auth']);

$doc = new Document($client);
$uri = $_REQUEST['uri'];

$doc->delete($uri);
header('content-type: text/html');
echo 'deleted';