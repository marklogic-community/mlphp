<?php
$title = array(
    'docs' => 'Read/Write/Delete Documents',
    'meta' => 'Manage Document Metadata',
    'opts' => 'Define Search Options',
    'search' => 'Perform Searches',
);
$id = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : 'docs');
$view = (!empty($_REQUEST['view']) ? $_REQUEST['view'] : 'code');

require_once ('setup.php');	// Define $mlphp properties

$client = new RESTClient($mlphp['host'], $mlphp['port'], $mlphp['path'], $mlphp['version'],
                         $mlphp['username-admin'], $mlphp['password-admin'], $mlphp['auth']);
?>
<!DOCTYPE html>
<html lang="en-us">
<head>
<title>Example Code: <?php echo $title[$id]; ?></title>
<link type="text/css" href="styles.css" rel="stylesheet">
<link type="text/css" href="../external/prettify/prettify.css" rel="stylesheet">
<script type="text/javascript" src="../external/prettify/prettify.js"></script>
</head>

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
<?php echo str_replace('<?php', '', file_get_contents('example_' . $id . '.php')); ?>
</pre>
<?php } else { ?>
<pre>

<?php require_once('example_' . $id . '.php') ?>
</pre>
<?php } ?>

</div>

</body>
</html>