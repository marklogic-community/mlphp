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

// Global values (project values override in project/setup.php)
$mlphp = array(
    'api_path'					=>		'api/',
    'username'					=>		'rest-writer-user',
    'password'					=>		'writer-pw',
    'username-admin'			=>		'rest-admin-user',
    'password-admin'			=>		'admin-pw',
    'host'						=>		'localhost',
    'port'						=>		8077,
    'path'						=>		'',
    'version'					=>		'v1',
    'auth'						=>		'digest',
);

function __autoload($classname) {
      // TODO Get 'api/' dynamically
    $filename = 'api/' . $classname . '.php';
    require_once($filename);
}

ini_set('display_errors', 1);
ini_set('html_errors', 1);
ini_set('docref_root', 'http://php.net/manual/en/');
error_reporting(E_ALL);