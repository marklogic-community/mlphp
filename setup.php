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

// Global values (project values override in project/setup.php)
$mlphp = array(
    'status'			=>	DEVELOPMENT,
    'api_path'			=>	ROOT_DIR . 'api/',
    'username'			=>	'rest-writer-user',
    'password'			=>	'writer-pw',
    'username-admin'	=>	'rest-admin-user',
    'password-admin'	=>	'admin-pw',
    'host'				=>	'localhost',
    'path'				=>	'',
    'version'			=>	'v1',
    'auth'				=>	'digest',
);

function __autoload($className)
{
    global $mlphp;
    $className = ltrim($className, '\\');
    $filePath  = '';
    $namespace = '';
    if ($lastNsPos = strripos($className, '\\')) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $filePath  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }
    $fileName = $mlphp['api_path'] .
                $filePath .
                str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
    if (is_readable($fileName)) {
        require_once $fileName;
        logMessage('Class loaded: ' . $fileName);
    }
}

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
