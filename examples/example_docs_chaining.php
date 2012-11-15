<!DOCTYPE html>
<html lang="en-us">
<head>
<title>Example: Chaining Document Methods</title>
</head>

<?php
// Set up global vars and class autoloading
require_once ('setup.php');

// Write text as a document to the database
$doc = new Document($mlphp['client']);
echo $doc->setContent('Hello, PHP!')->write('/chained1.txt')->getContent();
echo '<br />';
echo $doc->setContentFile('example.xml')->write('/chained2.xml')->getContent();
echo '<br />';
$doc2 = new Document($mlphp['client']);
echo $doc2->write();

?>

</body>
</html>