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
<title>Example: Connect to Non-ML REST</title>
</head>

<?php
// Set up global vars and class autoloading
require_once ('setup.php');

// Client for govtrack.us
$client1 = new MLPHP\RESTClient('www.govtrack.us', 0, 'api', 'v1');

// Get Senate bills from bill endpoint
try {
    $params = array('bill_type' => 'senate_bill');
    $request = new MLPHP\RESTRequest('GET', 'bill/', $params);
    $response = $client1->send($request);
    //print_r($response->getBody());
    echo 'Title of second bill object: <br />';
    $obj = json_decode($response->getBody());
    echo $obj->objects[1]->title . '<br /><br />' . PHP_EOL;
} catch(Exception $e) {
    echo $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine() . PHP_EOL;
}

// Get people named 'John' from person endpoint.
try {
    $params = array('firstname' => 'John');
    $request = new MLPHP\RESTRequest('GET', 'person', $params);
    $response = $client1->send($request);
    //print_r($response->getBody());
    echo 'Current role description of first person object: <br />';
    $obj = json_decode($response->getBody());
    echo $obj->objects[0]->current_role->description . '<br /><br />' . PHP_EOL;
} catch(Exception $e) {
    echo $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine() . PHP_EOL;
}

// Client for Flickr
$client2 = new MLPHP\RESTClient('api.flickr.com', 0, 'services/rest', '');

// Get recent public photos
try {
    $params = array('method' => 'flickr.photos.getRecent',
                    'api_key' => 'YOUR_FLICKR_API_KEY', // Add yours
                    'format' => 'rest');
    $request = new MLPHP\RESTRequest('GET', '', $params);
    $response = $client2->send($request);
    print_r($response->getBody());
    echo 'ID of fourth photo: <br />';
    $dom = new DOMDocument();
    $dom->loadXML($response->getBody());
    echo $dom->getElementsByTagName('photo')->item(4)->getAttribute('id') . '<br /><br />' . PHP_EOL;
} catch(Exception $e) {
    echo $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine() . PHP_EOL;
}

?>

</body>
</html>