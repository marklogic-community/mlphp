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
$restClient = new MLPHP\RESTClient($mlphp['host'], $mlphp['port'], $mlphp['path'], $mlphp['version'], $mlphp['username'], $mlphp['password'], $mlphp['auth']);
$uri = (!empty($_REQUEST['uri'])) ? $_REQUEST['uri'] : exit;
$query = (!empty($_REQUEST['query'])) ? $_REQUEST['query'] : '';
$session = (!empty($_REQUEST['session'])) ? $_REQUEST['session'] : '';
$start = (!empty($_REQUEST['start'])) ? $_REQUEST['start'] : '';
$pageLength = (!empty($_REQUEST['pageLength'])) ? $_REQUEST['pageLength'] : '';
$link = 'index.php?q=' . $query . '&session=' . $session . '&start=' . $start;
?>
<!DOCTYPE html>
<html lang="en-us">
<head>
<title>MLPHP: Bill</title>
<link href="styles.css" rel="stylesheet" type="text/css">
</head>
<body>
<div id="wrapper">

<div id="header">

<div id="logo"><a href="index.php">U.S. Bill Search</a></div>
<div id="subtitle">Powered by <a href="../index.php">MLPHP</a></div>

</div><!-- /header -->

<div id="back-link"><a href="<?php echo $link; ?>">Back to results</a></div>

<?php
// Load the XML source
$doc = new MLPHP\Document($restClient, $uri);
$xml = new DOMDocument;
$xml->loadXML($doc->read());

// Load the XSLT
$xsl = new DOMDocument;
$xsl->load('bill.xsl');

// Configure the transformer
$proc = new XSLTProcessor;
$proc->importStyleSheet($xsl); // attach the xsl rules

echo $proc->transformToXML($xml);

?>
<div id="footer">Powered by <a href="../index.php">MLPHP</a></div>
</div><!-- /wrapper -->
</body>
</html>