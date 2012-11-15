<?php
// Set up global vars and class autoloading
require_once ('setup.php');

$client = new RESTClient($mlphp['host'], $mlphp['port'], $mlphp['path'], $mlphp['version'], $mlphp['username'], $mlphp['password'], $mlphp['auth']);

$img = new ImageDocument($client);
$uri = $_REQUEST['uri'];

$image = $img->read($uri);
header('content-type: image/jpeg');
echo $image;