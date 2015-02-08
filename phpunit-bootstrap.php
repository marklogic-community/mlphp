<?php

// use Monolog\Logger;

// $loader = require 'vendor/autoload.php';

// $mlphp = array(
//     'log_level' => Logger::DEBUG,

//     /* 'user'      => 'admin', */
//      'pass'      => 'adm1n',

//     /* 'host'      => 'localhost', */
//     /* 'port'      => '8234', */
//     /* 'db'        => 'mlphp-test', */
//     /* 'mgmt_port' => '8002', */

//     'unused'       => foo
// )

use MarkLogic\MLPHP;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

// Create a logger for tests
$logger = new Logger('test');
$logger->pushHandler(new StreamHandler('php://stderr', Logger::DEBUG));

// Global config properties for tests
$mlphp = new MLPHP\MLPHP([
    'host' => '127.0.0.1',
    'port' => 8234,
    'managePort' => 8002,
    'api' => 'mlphp-test-api',
    'db' => 'mlphp-test-db',
    'username' => 'admin',
    'password' => 'admin',
    'path' => '',
    'managePath' => 'manage',
    'version' => 'v1',
    'manageVersion' => 'v2',
    'auth' => 'digest',
    'logger' => $logger
]);

    //$api =  $mlphp->getAPI()->create();

// Run after all tests complete
register_shutdown_function(function(){
    global $mlphp;
    // If API was created, delete it
    $api = $mlphp->getAPI();
    if ($api->exists()) {
        $api->delete();
    }
});

?>
