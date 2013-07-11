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

session_start();
require_once ('setup.php');
require_once ('options.php');

$mapsKey = (!empty($mlphp->getConfig()['maps_key'])) ? ('key=' . $mlphp->getConfig()['maps_key'] . '&') : '';
?>
<!DOCTYPE html>
<html lang="en-us">
<head>
<meta charset="UTF-8">
<title>MLPHP: iPhone Photomap</title>
<link type="text/css" href="styles.css" rel="stylesheet">
<script type="text/javascript" src="../external/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?<?php echo $mapsKey ?>sensor=false"></script>
<script type="text/javascript" src="scripts.js"></script>
</head>
<?php

$client = $mlphp->newClient();

if (isset($_FILES['upload'])) {
    try {
    	// Load files
    	foreach ($_FILES['upload']['error'] as $key => $error) {
			if ($error == UPLOAD_ERR_OK) {
				// Move file to upload directory
				$tmpName = $_FILES['upload']['tmp_name'][$key];
				$name = $_FILES['upload']['name'][$key];
				$dest = $mlphp->getConfig()['uploads_dir'] . '/' . $name;
				move_uploaded_file($tmpName, $dest);
				try {
					// Write image file
					require_once ('IPhoneImageDocument.php');
					$image = new IPhoneImageDocument($client);
					$image->setContentFile($dest);
					$image->write($name);
					// Write image metadata
					$metadata = new MLPHP\Metadata();
					$metadata->addProperties(array(
						'latitude' => $image->getLatitude(),
						'longitude' => $image->getLongitude(),
						'height' => $image->getHeight(),
						'width' => $image->getWidth(),
						'filename' => $image->getFilename()
					));
					$image->writeMetadata($metadata);
				} catch(Exception $e) {
					echo 'Error: ' . $e->getMessage();
				}
			}
    	}
    } catch (Exception $e) {
        echo $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine() . PHP_EOL;
    }
}

// Get search results
$search = new MLPHP\Search($client);
$params = array(
    'pageLength' => 1000,
    'view' => 'all',
    'options' => 'photomap',
    'format' => 'xml'
);
$searchResults = $search->retrieve('', $params);

// Write JavaScript data for map.js
echo '<script>' . PHP_EOL;
echo 'var locations = { ' . PHP_EOL;
$lines = array();
foreach ($searchResults->getResults() as $result) {
    $line = '"' . $result->getURI() . '" : { ';
    $props = array();
    foreach ($result->getMetadataKeys() as $key) {
        $props[] = ' "' . $key . '" : "' . $result->getMetadata($key) . '"';
    }
    $line .= implode(',', $props);
    $line .= ' }';
    $lines[] = $line;
}
echo implode(', ' . PHP_EOL, $lines);
echo '}' . PHP_EOL;
echo '</script>';

$title = ($searchResults->getTotal() != 1) ? 'Photos' : 'Photo';

?>
<body onload="initialize()">

    <!-- Google map container -->
    <div id="map_canvas" style="width:100%; height:100%"></div>

    <!-- Photo loading form -->
    <div id="upload">
        <div id="title">
            <span id="total"><?php echo $searchResults->getTotal() ?></span>
            <?php echo ($searchResults->getTotal() != 1) ? 'iPhone Photos' : 'iPhone Photo' ?>
        </div>
        <form action="index.php" method="post" enctype="multipart/form-data">
            <input id="upload-input" type="file" value="" name="upload[]" multiple>
            <button id="upload-submit" type="submit">Load</button>
        </form>
        <div id="footer">Powered by <a href="../index.php">MLPHP</a></div>
    </div><!-- /upload -->

</body>
</html>
