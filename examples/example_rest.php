<!DOCTYPE html>
<html lang="en-us">
<head>
<title>Example: Connect to ML with a REST client</title>
</head>

<?php
// Set up global vars and class autoloading
require_once ('setup.php');

// Write a doc via PUT
$doc = new Document($mlphp['client']);
$file = "example.xml";
$doc->setContentFile($file);
$uri = "/" . $file;
echo '<br />Write: ' . $doc->write($uri) . '<br />' . PHP_EOL;

// Read a doc via GET
echo '<br />Read: ' . $doc->read($uri) . '<br />' . PHP_EOL;

// Delete a doc via DELETE
echo '<br />Delete: ' . $doc->delete($uri) . '<br />' . PHP_EOL;

// Test 301 redirect
$govTrackClient = new RESTClient('www.govtrack.us', 0, 'api', 'v1');

// Get Senate bills from bill endpoint
$params = array('bill_type' => 'senate_bill');
// 'bill' resource results in redirect ('bill/' does not)
$request = new RESTRequest('GET', 'bill', $params);
$response = $govTrackClient->send($request);
//print_r($response->getBody());
echo '<br />Title of second bill object: <br />';
$obj = json_decode($response->getBody());
echo $obj->objects[1]->title . '<br /><br />' . PHP_EOL;

?>

</body>
</html>