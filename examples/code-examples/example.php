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

$title = array(
    'example_docs.php' => 'Read/Write/Delete Documents',
    'example_meta.php' => 'Manage Document Metadata',
    'example_opts.php' => 'Define Search Options',
    'example_search.php' => 'Perform Searches',
    '../my_first_app.php' => 'Simple Application',
);
$id = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
// Make sure page ID is in list, otherwise set default
$id_decoded = (in_array(urldecode($id), array_keys($title))) ? urldecode($id) : 'example_docs.php';
$view = (!empty($_REQUEST['view']) ? $_REQUEST['view'] : 'code');
?>
<!DOCTYPE html>
<html lang="en-us">
<head>
<title>Example Code: <?php echo $title[$id]; ?></title>
<link type="text/css" href="../external/prettify/prettify.css" rel="stylesheet">
<link type="text/css" href="styles.css" rel="stylesheet">
<script type="text/javascript" src="../external/prettify/prettify.js"></script>
</head>
<body onload="prettyPrint()">

<div id="wrapper">

<div class="examples-subtitle"><a href="index.php">Example Code</a></div>
<h1><?php echo $title[$id]; ?></h1>

<div class="code-links">
<?php if ($view === 'code') { ?>
<strong>Display code</strong> | <a href="example.php?id=<?php echo $id; ?>&view=exec">Execute code</a>
<?php } else { ?>
<a href="example.php?id=<?php echo $id; ?>&view=code">Display code</a> | <strong>Execute code</strong>
<?php } ?>
</div>

<?php if ($view === 'code') { ?>
<pre class="prettyprint">
<?php
$pattern = '/\/\*([^*]|[\r\n])*?\*\//';
$replacement = '';
echo trim(htmlspecialchars(preg_replace(
    $pattern,
    $replacement,
    str_replace('?>', "", str_replace('<?php', '', file_get_contents($id_decoded)))
)));
?>
</pre>
<?php } else { ?>
<pre>
<?php require_once($id_decoded) ?>
</pre>
<?php } ?>

</div>

</body>
</html>
