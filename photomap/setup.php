<?php

require_once ('../setup.php');

// Project values (these override and supplement global values in ../setup.php)
$mlphp = array_merge($mlphp, array(
    'api_path'		=>		'../api/',
    'port'			=>		8078,
    'maps_key'		=>		'AIzaSyDUsZCP04vN4oxSQBcHmz1YGbTq8RTMEvw',
    'uploads_dir'	=>		__DIR__ . '/uploads' // Make accessible to PHP, e.g. chmod 700, chown www
));

// Check uploads folder permissions
try {
    if (!is_readable($mlphp['uploads_dir']) || !is_writable($mlphp['uploads_dir']) || !is_executable($mlphp['uploads_dir'])) {
        throw new Exception('Error: Photo uploads directory not readable, writable, and executable.');
    }
} catch (Exception $e) {
    echo $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine() . PHP_EOL;
}
