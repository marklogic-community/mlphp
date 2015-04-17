<?php
/**
 * Configuration for PHPUnit testing.
 */
use MarkLogic\MLPHP;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

// Create a logger for tests
$logger = new Logger('test');
$logger->pushHandler(new StreamHandler('php://stderr', Logger::ERROR));

// Global config properties for tests
$mlphp = new MLPHP\MLPHP(array(
    'host' => '127.0.0.1',
    'port' => 8234,
    'api' => 'mlphp-test-api',
    'db' => 'mlphp-test-db',
    'username' => 'admin',
    'password' => 'admin',
    'auth' => 'digest',
    'logger' => $logger
));
