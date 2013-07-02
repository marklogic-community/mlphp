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
$items = (!empty($_REQUEST['items'])) ? ' ' . $_REQUEST['items'] : '';
$redirect = (!empty($_REQUEST['redirect'])) ? ' ' . $_REQUEST['redirect'] : '';
$delay = (!empty($_REQUEST['delay'])) ? ' ' . $_REQUEST['delay'] : 1000;

if (!empty($redirect)) {
    require_once('loading.php');
} else {
    echo "<script>$(window).load(function () { document.write('" . $items . " loaded') });</script>";
}

$restClient = new MLPHP\RESTClient($mlphp['host'], $mlphp['port'], $mlphp['path'], $mlphp['version'], $mlphp['username'], $mlphp['password'], $mlphp['auth']);

$rootdir = 'bills';
$subdirs = array('110', '111', '112'); // directories to import from

// Loop through files from subdirectories
foreach($subdirs as $subdir) {
    $count = 0;
    $dir = $rootdir . '/' . $subdir;
    if ($handle = opendir($dir)) {
        //echo "Writing files from directory: " . $dir . "<br />";
        $doc = new MLPHP\Document($restClient);
        while (false !== ($file = readdir($handle))) {
            if (substr($file, 0, 1) !== ".") {
                $doc->setContentType("application/xml");
                $content = $doc->setContentFile($dir . '/' . $file)->getContent();
                $uri = '/bills/' . $subdir . '/' . $file; // URI example: '/bills/112/h321.xml'
                $dom = new DOMDocument();
                $dom->loadXML($content);
                // Only write bills with related bills and short titles
                $num_rel_bills = $dom->getElementsByTagName('relatedbill')->length;
                $len_title = strlen($dom->getElementsByTagName('title')->item(0)->nodeValue);
                if ($num_rel_bills == 0 || $len_title > 80) {
                    continue;
                }
                $xpath = new DOMXPath($dom);
                // Set collection base on bill type. Example: 'hr' (House resolution)
                $type = $xpath->query('//bill/@type')->item(0)->nodeValue;
                $params = array("collection" => $type);
                $count++;
                //echo $count . ': ' . $uri . ' (' . $type . ')<br />' . PHP_EOL;
                // Write content to database via REST client
                if ($count++ >= 200) {
                    //break;
                }
                $doc->write($uri, $params);
            }
        }
        closedir($handle);
        $_SESSION['documents_loaded_usbills'] = TRUE;
    }
}

?>

</body>
</html>