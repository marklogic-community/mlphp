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

define('PRODUCTION', 'prod');
define('DEVELOPMENT', 'dev');
define('DEBUG', 'debug');
define('ROOT_DIR', __DIR__ . DIRECTORY_SEPARATOR);

/**
 * Global values (project values override in <project>/setup.php)
 */
$mlphp = array(
    'status'			=>	DEVELOPMENT,
    'api_path'			=>	ROOT_DIR . 'api/',
    'username'			=>	'rest-writer-user',
    'password'			=>	'writer-pw',
    'username-admin'	=>	'rest-admin-user',
    'password-admin'	=>	'admin-pw',
    'host'				=>	'localhost',
    'port'				=>	8077,
    'path'				=>	'',
    'version'			=>	'v1',
    'auth'				=>	'digest',
);

/**
 * Set up autoloading of classes. Since API classes in one directory, can use 
 * default.
 * @see http://php.net/manual/en/function.spl-autoload-register.php
 */
set_include_path(get_include_path() . PATH_SEPARATOR . $mlphp['api_path']);
spl_autoload_register();

function logMessage($msg)
{
    global $mlphp;
    if ($mlphp['status'] === DEBUG) {
        echo $msg . '<br />' . PHP_EOL;
    }
}

switch ($mlphp['status']) {
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
        break;
}
