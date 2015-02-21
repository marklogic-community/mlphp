<?php
require_once('phpunit-config.php');

// Get MarkLogic version for skipping tests
$serverConfig = $mlphp->getServerConfig();
$mlphp->config['mlversion'] =  $serverConfig['version'];

// Create REST API for tests
$api = $mlphp->getAPI()->create()->setProperty('debug', 'true');

function phpunitTeardown($api)
{
    // Delete REST API
    if ($api->exists()) {
        $api->delete();
    }
}

// Run after all tests complete
register_shutdown_function('phpunitTeardown', $api);

?>
