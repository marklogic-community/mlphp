<?php
session_start();
require_once ('setup.php');
$restClient = new RESTClient($mlphp['host'], $mlphp['port'], $mlphp['path'], $mlphp['version'], $mlphp['username'], $mlphp['password'], $mlphp['auth']);
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
$doc = new Document($restClient, $uri);
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
<div id="footer">Powered by MLPHP</div>
</div><!-- /wrapper -->
</body>
</html>