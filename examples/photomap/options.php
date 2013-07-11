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
use MarkLogic\MLPHP as MLPHP;

require_once ('setup.php');

if (!empty($redirect)) {
    require_once('loading.php');
} else {
    //echo "<script>$(window).load(function () { document.write('" . ((!empty($_REQUEST['items'])) ? ' ' . $_REQUEST['items'] : '') . " loaded') });</script>";
}

// Load search options if needed
if (!isset($_SESSION['options_loaded_photomap']) || !$_SESSION['options_loaded_photomap'] === TRUE) {
    echo '<!-- Loading search options -->' . PHP_EOL;

    $client = $mlphp->newClient();
    $client->setUsername($mlphp->config['username-admin']);
    $client->setPassword($mlphp->config['password-admin']);

    // Set up options node
    $options = new MLPHP\Options($client);

    $latConstraint = new MLPHP\RangeConstraint('latitude', 'xs:float', 'false', 'latitude');
    $latConstraint->setFragmentScrope('properties');
    $options->addConstraint($latConstraint);

    $lonConstraint = new MLPHP\RangeConstraint('longitude', 'xs:float', 'false', 'longitude');
    $lonConstraint->setFragmentScrope('properties');
    $options->addConstraint($lonConstraint);

    $hConstraint = new MLPHP\RangeConstraint('height', 'xs:int', 'false', 'height');
    $hConstraint->setFragmentScrope('properties');
    $options->addConstraint($hConstraint);

    $wConstraint = new MLPHP\RangeConstraint('width', 'xs:int', 'false', 'width');
    $wConstraint->setFragmentScrope('properties');
    $options->addConstraint($wConstraint);

    $fConstraint = new MLPHP\RangeConstraint('filename', 'xs:string', 'false', 'filename');
    $fConstraint->setFragmentScrope('properties');
    $options->addConstraint($fConstraint);

    $extracts = new MLPHP\Extracts();
    $extracts->addConstraints(array('latitude', 'longitude', 'height', 'width', 'filename'));
    $options->setExtracts($extracts);

    // Write to database
    $optionsid = 'photomap';
    $response = $options->write($optionsid);

    echo '<!--' . $options->read($optionsid) . '-->' . PHP_EOL;

    $_SESSION['options_loaded_photomap'] = TRUE;

} else {

    echo '<!-- Search options already loaded -->' . PHP_EOL;

}
