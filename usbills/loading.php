<!DOCTYPE html>
<html lang="en-us">
<head>
<title>Loading<?php echo $itemsLoaded; ?></title>
<script type="text/javascript" src="../external/jquery-1.7.1.min.js"></script>
<link href="styles.css" rel="stylesheet" type="text/css">
<script>
$(window).load(function () {
    setTimeout(function() {window.location = "<?php echo $redirect; ?>"},<?php echo $delay; ?>);
});
</script>
</head>

<div id="loading">
    <div id="loading-content">Loading<?php echo $items; ?>...<br /><img src="images/loading.gif" /></div>
</div>