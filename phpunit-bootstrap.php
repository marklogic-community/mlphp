<?php
use MarkLogic\MLPHP;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

// Create a logger for tests
$logger = new Logger('test');
$logger->pushHandler(new StreamHandler('php://stderr', Logger::ERROR));

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

// Create REST API for tests
$api =  $mlphp->getAPI()->create();

// Run after all tests complete
register_shutdown_function(function(){
    global $mlphp;
    // Delete REST API
    $api = $mlphp->getAPI();
    if ($api->exists()) {
        $api->delete();
    }
});

?>
