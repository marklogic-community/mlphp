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

// Set up global vars and class autoloading
require_once ('setup.php');

// Create a REST client object for the MarkLogic database
$client = new RESTClient($mlphp['host'], $mlphp['port'], $mlphp['path'], $mlphp['version'],
                         $mlphp['username'], $mlphp['password'], $mlphp['auth']);

$doc = new Document($client);
$uri = $_REQUEST['uri'];
$content = $doc->read($_REQUEST['uri']);
$filename = end(explode('/', $uri));
header('Content-Type: ' . $doc->getContentType());
header('Content-Disposition: attachment; filename=' . $filename);
echo $content;