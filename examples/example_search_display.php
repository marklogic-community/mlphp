<!DOCTYPE html>
<html lang="en-us">
<head>
<title>Example Code: Perform Searches</title>
<link type="text/css" href="styles.css" rel="stylesheet">
<link type="text/css" href="../external/prettify/prettify.css" rel="stylesheet">
<script type="text/javascript" src="../external/prettify/prettify.js"></script>
</head>

<body onload="prettyPrint()">

<div id="wrapper">

<div class="examples-subtitle"><a href="index.php">Example Code</a></div>
<h1>Perform Searches</h1>

<div class="code-links"><strong>Display code</strong> | <a href="example_search.php">Execute code</a></div>

<pre class="prettyprint lang-html">
// Create a REST client object for the MarkLogic database
$client = new RESTClient($mlphp['host'], $mlphp['port'], $mlphp['path'], $mlphp['version'],
                         $mlphp['username-admin'], $mlphp['password-admin'], $mlphp['auth']);


// Load some documents
$doc = new Document($client);
$doc->setContentFile("hamlet.xml")->write("/" . "hamlet.xml");
$doc->setContentFile("macbeth.xml")->write("/" . "macbeth.xml");
$doc->setContentFile("example.json")->write("/" . "example.json");


<span class="code-section">// Search for a string</span>
$search = new Search($client);
$search->setPageLength(2);
$results = $search->retrieve('donalbain');
echo "Simple text search results:\n\n";
print_r($results);
echo "\n\n\n";


<span class="code-section">// Search by key-value for an element</span>
$search->setPageLength(1);
$results = $search->retrieveKeyValueElement('PLAYSUBT', '', 'HAMLET');
echo "Key-value (for an element) search results:\n\n";
print_r($results);
echo "\n\n\n";


<span class="code-section">// Search by key-value for a JSON property</span>
$search->setPageLength(1);
$results = $search->retrieveKeyValue('planet', 'Earth');
echo "Key-value (for JSON) search results:\n\n";
print_r($results);
echo "\n\n\n";
</pre>

</div>

</body>
</html>