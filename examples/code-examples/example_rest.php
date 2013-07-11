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
use MarkLogic\MLPHP as MLPHP;

?>
<!DOCTYPE html>
<html lang="en-us">
<head>
<title>Example: Connect to ML with a REST client</title>
</head>

<?php
// Set up global vars and class autoloading
require_once ('setup.php');

$client = $mlphp->newClient();
$client->setUsername($mlphp->config['username-admin']);
$client->setPassword($mlphp->config['password-admin']);

// Write a doc via PUT
$doc = new MLPHP\Document($client);
$file = 'example.xml';
$doc->setContentFile($file);
$uri = "/" . $file;
echo '<br />Write: ' . $doc->write($uri)->getUri() . '<br />' . PHP_EOL;

// Read a doc via GET
echo '<br />Read: ' . $doc->read($uri) . '<br />' . PHP_EOL;

// Delete a doc via DELETE
echo '<br />Delete: ' . $doc->delete($uri)->getUri() . '<br />' . PHP_EOL;

// Test 301 redirect
$govTrackClient = new MLPHP\RESTClient('www.govtrack.us', 0, 'api', 'v1');

// Get Senate bills from bill endpoint
$params = array('bill_type' => 'senate_bill');
// 'bill' resource results in redirect ('bill/' does not)
$request = new MLPHP\RESTRequest('GET', 'bill', $params);
$response = $govTrackClient->send($request);
//print_r($response->getBody());
echo '<br />Title of second bill object: <br />';
$obj = json_decode($response->getBody());
echo $obj->objects[1]->title . '<br /><br />' . PHP_EOL;

?>

</body>
</html>
