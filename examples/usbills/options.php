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

// Load search options if needed
if (!isset($_SESSION['documents_loaded_usbills']) || !$_SESSION['options_loaded_usbills'] === TRUE) {
    echo '<!-- Loading search options -->' . PHP_EOL;

    $client = new MLPHP\RESTClient($mlphp['host'], $mlphp['port'], $mlphp['path'], $mlphp['version'], $mlphp['username-admin'], $mlphp['password-admin'], $mlphp['auth']);

    // Set up options node
    $options = new MLPHP\Options($client);

    // Range constraint on session
    $session = new MLPHP\RangeConstraint('session', 'xs:int', 'false', 'bill', '', 'session');
    $options->addConstraint($session);

    // Range constraint on type
    $type = new MLPHP\RangeConstraint('type', 'xs:string', 'false', 'bill', '', 'type');
    $options->addConstraint($type);

    // Range constraint on number
    $type = new MLPHP\RangeConstraint('number', 'xs:int', 'false', 'bill', '', 'number');
    $options->addConstraint($type);

    // Range constraint on abbrev
    $type = new MLPHP\RangeConstraint('abbrev', 'xs:string', 'false', 'bill', '', 'abbrev');
    $options->addConstraint($type);

    // Range constraint on introduced
    $type = new MLPHP\RangeConstraint('introduced', 'xs:string', 'false', 'introduced', '', 'date');
    $options->addConstraint($type);

    // Range constraint on status
    $status = new MLPHP\RangeConstraint('status', 'xs:string', 'true', 'status');
    $options->addConstraint($status);

    // Range constraint on subject
    $keyword = new MLPHP\RangeConstraint('subject', 'xs:string', 'true', 'subject');
    $keyword->setFacetOptions(array('descending', 'frequency-order', 'limit=5'));
    $options->addConstraint($keyword);

    // Range constraint on title
    $title = new MLPHP\RangeConstraint('title', 'xs:string', 'false', 'title');
    $options->addConstraint($title);

    // Range constraint on link
    $title = new MLPHP\RangeConstraint('link', 'xs:string', 'false', 'link', '', 'href');
    $options->addConstraint($title);

    // Snippetting prefs
    $transform = new MLPHP\TransformResults('snippet');
    $pref1 = new MLPHP\PreferredElement('title', '');
    $pref2 = new MLPHP\PreferredElement('summary', '');
    $transform->addPreferredElements(array($pref1, $pref2));
    $options->setTransformResults($transform);

    // Metadata extracts
    $extracts = new MLPHP\Extracts();
    $extracts->addConstraints(array('title', 'status', 'subject', 'introduced', 'link', 'session', 'abbrev'));
    $options->setExtracts($extracts);

    // Term setting
    //$term = new MLPHP\Term("no-results");
    //$options->setTerm($term);

    // Write to database
    $optionsid = 'usbills';
    $response = $options->write($optionsid);

    echo '<!--' . $options->read($optionsid) . '-->' . PHP_EOL;

    $_SESSION['options_loaded_usbills'] = TRUE;

} else {

    echo '<!-- Search options already loaded -->' . PHP_EOL;

}