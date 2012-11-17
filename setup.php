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

define('PRODUCTION', 0);
define('DEVELOPMENT', 1);
define('DEBUG', 2);

define('STATUS', DEVELOPMENT);

// Global values (project values override in project/setup.php)
$mlphp = array(
    'api_path'			=>	'api/',
    'username'			=>	'rest-writer-user',
    'password'			=>	'writer-pw',
    'username-admin'	=>	'rest-admin-user',
    'password-admin'	=>	'admin-pw',
    'host'				=>	'localhost',
    'path'				=>	'',
    'version'			=>	'v1',
    'auth'				=>	'digest',
);

function __autoload($classname) {
    global $mlphp;
    $filename = $mlphp['api_path'] . $classname . '.php';
    require_once($filename);
}

switch (STATUS) {
    case PRODUCTION: {
        ini_set('display_errors', 0);
    }
    case DEVELOPMENT: {
        ini_set('display_errors', 1);
        ini_set('html_errors', 1);
        ini_set('docref_root', 'http://php.net/manual/en/');
        error_reporting(E_ALL | E_STRICT);
    }
    case DEBUG: {
        ini_set('display_errors', 1);
        ini_set('html_errors', 1);
        ini_set('docref_root', 'http://php.net/manual/en/');
        error_reporting(E_ALL | E_STRICT);
    }
    default:
        ini_set('display_errors', 0);
        break;
}
