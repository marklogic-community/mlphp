<?php
session_start();
require_once ('setup.php');
require_once ('options.php');
?>
<!DOCTYPE html>
<html lang="en-us">
<head>
<meta charset="UTF-8">
<title>MLPHP: iPhone Photomap</title>
<link type="text/css" href="styles.css" rel="stylesheet">
<script type="text/javascript" src="../external/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=<?php echo $mlphp['maps_key'] ?>&sensor=false"></script>
<script type="text/javascript" src="scripts.js"></script>
</head>
<?php

$client = new RESTClient($mlphp['host'], $mlphp['port'], $mlphp['path'], $mlphp['version'], $mlphp['username'], $mlphp['password'], $mlphp['auth']);

if (isset($_FILES['upload'])) {
    // Load files
    foreach ($_FILES['upload']['error'] as $key => $error) {
        if ($error == UPLOAD_ERR_OK) {
            // Move file to upload directory
            $tmpName = $_FILES['upload']['tmp_name'][$key];
            $name = $_FILES['upload']['name'][$key];
            $dest = $mlphp['uploads_dir'] . '/' . $name;
            move_uploaded_file($tmpName, $dest);
            try {
                // Write image file
                require_once ('IPhoneImageDocument.php');
                $image = new IPhoneImageDocument($client);
                $image->setContentFile($dest);
                $image->write($name);
                // Write image metadata
                $metadata = new Metadata();
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
}

// Get search results
$search = new Search($client);
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