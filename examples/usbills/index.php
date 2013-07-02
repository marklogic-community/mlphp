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
$restClient = new MLPHP\RESTClient($mlphp['host'], $mlphp['port'], $mlphp['path'], $mlphp['version'], $mlphp['username'], $mlphp['password'], $mlphp['auth']);
$query = (!empty($_REQUEST['query'])) ? $_REQUEST['query'] : '';
$session = (!empty($_REQUEST['session'])) ? $_REQUEST['session'] : '';
$start = (!empty($_REQUEST['start'])) ? $_REQUEST['start'] : '';
$pageLength = (!empty($_REQUEST['pageLength'])) ? $_REQUEST['pageLength'] : '';
?>
<!DOCTYPE html>
<html lang="en-us">
<head>
<title>MLPHP: U.S. Bill Search</title>
<link href="styles.css" rel="stylesheet" type="text/css">
</head>
<body>

<div id="wrapper">

<div id="header">

<div id="logo"><a href="index.php">U.S. Bill Search</a></div>
<div id="subtitle">Powered by <a href="../index.php">MLPHP</a></div>

<div id="search">
<form action="index.php" method="get">
    <input id="query" type="text" name="query" value='<?php echo $query ?>'>
    <select id="menu" name="session">
        <option value="all" <?php echo ($session === 'all') ? 'selected' : '' ?>>All Congresses</option>
        <option value="110" <?php echo ($session === '110') ? 'selected' : '' ?>>110th</option>
        <option value="111" <?php echo ($session === '111') ? 'selected' : '' ?>>111th</option>
        <option value="112" <?php echo ($session === '112') ? 'selected' : '' ?>>112th</option>
    </select>
    <button id="submit" type="submit">Search</button>
</form>
</div>

</div><!-- /header -->

<?php

if (TRUE) { // TODO Change TRUE to not execute search following in some cases?

    // Get search results
    $search = new MLPHP\Search($restClient);
    $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 1;
    $pageLength = (isset($_REQUEST['pageLength'])) ? $_REQUEST['pageLength'] : 10;
    $params = array(
        "start" => $start,
        "pageLength" => $pageLength,
        "view" => "all",
        "options" => "usbills",
        "format" => "xml"
    );

    // Journal filter
    if (empty($_REQUEST['session']) || $_REQUEST['session'] === 'all') {
        $queryModified = $query;
    } else {
        $queryModified = $query . ' session:' . $_REQUEST['session'];
    }

    // Perform search
    $searchResults = $search->retrieve($queryModified, $params);

    //echo '<pre>';
    //print_r($searchResults);
    //echo '</pre>';

    // No results
    if ($searchResults->getTotal() == 0) {
        $message = 'No results.';
        if (!isset($_SESSION['documents_loaded_usbills']) || !$_SESSION['documents_loaded_usbills'] === TRUE) {
            $message .= ' <a href="documents.php?redirect=index.php&items=bills">Click here to load bills</a>.';
        }
        echo '<div class="result textLarge">' . $message . '</div>';
    // Results
    } else {

        // Display keywords
        $kw_constraint = 'subject';
        if ($searchResults->hasFacets()) {
            if ($facetValues = $searchResults->getFacet($kw_constraint)->getFacetValues()) {
                echo '<div class="keywords"><strong>Top Subjects:&nbsp;</strong> ';
                foreach ($searchResults->getFacet($kw_constraint)->getFacetValues() as $facetValue) {
                    $val_encoded = urlencode('"' . $facetValue->getValue() . '"');
                    echo '<span class="keyword"><a href="index.php?query=' . $val_encoded . '">' . $facetValue->getValue() . '</a> &nbsp;</span> ';
                }
                echo '</div>';
            }
        }

        // Paging
        $prevArray = array('query' => $queryModified, 'start' => $searchResults->getPreviousStart(), 'pageLength' => $searchResults->getPageLength());
        $nextArray = array('query' => $queryModified, 'start' => $searchResults->getNextStart(), 'pageLength' => $searchResults->getPageLength());
        $prevLink = ($searchResults->getCurrentPage() > 1) ? '<a href="index.php?' . http_build_query($prevArray) . '" id="prev">&lt;</a>' : '';
        $nextLink = ($searchResults->getCurrentPage() < $searchResults->getTotalPages()) ? '<a href="index.php?' . http_build_query($nextArray) . '" id="next">&gt;</a>' : '';
        echo '<div class="summary">';
        echo $prevLink . ' ' . $searchResults->getStart() . '-' . $searchResults->getEnd() . ' of ' . $searchResults->getTotal() . ' ' . $nextLink;
        echo '</div>';

        // Results
        foreach ($searchResults->getResults() as $result) {
            echo '<div class="result">';
            // Title link
            echo '<div class="result-title"><strong><a href="bill.php?uri=' . $result->getURI() . '&session=' . $session . '&start=' . $start . '">';
            echo $result->getMetadata('abbrev') . ': ' . $result->getMetadata('title');
            echo '</strong></a></div>';
            // Snippet
            echo '<div class="result-snippet">';
            $snippet = '';
            foreach ($result->getMatches() as $match) {
                $snippet .= $match->getContent() . ' ';
            }
            echo str_replace('... ...', '... ', $snippet);
            echo '</div>';
            // Metadata
            echo '<div class="result-meta">';
            echo $result->getMetadata('session') . 'th Congress &nbsp;&nbsp; ';
            echo 'Introduced: ' . $result->getMetadata('introduced') . ' &nbsp;&nbsp; Status: ';
            echo $result->getMetadata('status');
            echo ' &nbsp;&nbsp; <a href="' . $result->getMetadata('link') . '" target="_blank">View Full Text on THOMAS</a>';
            echo '</div>';
            echo '</div>';
        }
    }
}
?>
<div id="footer">Powered by <a href="../index.php">MLPHP</a></div>
</div><!-- /wrapper -->
</body>
</html>