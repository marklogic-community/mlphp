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

$test_api_name = 'mlphp-test-rest-api';
$test_db_name = 'mlphp-test';

// Create a REST API for all tests
$test_api = new MLPHP\RESTAPI(
    $test_api_name,
    '127.0.0.1',
    $test_db_name,
    8234,
    'admin',
    'admin'
);
if ($test_api->exists()) {
    $test_api->delete();
}
$test_api->create();

// Delete after all tests complete
register_shutdown_function(function(){
    global $test_api;
    $test_api->delete();
});

?>
