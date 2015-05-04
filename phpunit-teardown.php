<?php
/**
 * Convenience script for cleaning up REST APIs and data used in tests.
 * Useful when the testing process fails midstream. Run the following:
 * `php ./phpunit-teardown.php`
 */
require_once('vendor/autoload.php');
require_once('phpunit-config.php');
use MarkLogic\MLPHP;

// Delete global REST API
try {
    $apiGlobal = $mlphp->getAPI();
    $apiGlobal->delete();
} catch (\Exception $e) {}


// Delete REST API used in RESTAPI tests
// @see tests/MarkLogic/Test/RESTAPITest.php
try {
    $apiTest = new MLPHP\RESTAPI(
        $mlphp->config['api'] . '-1',
        $mlphp->config['host'],
        $mlphp->config['db'] . '-1',
        $mlphp->config['port'] + 1,
        $mlphp->config['username'],
        $mlphp->config['password'],
        $logger
    );
    $apiTest->delete();
} catch (\Exception $e) {}
