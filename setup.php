<?php

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