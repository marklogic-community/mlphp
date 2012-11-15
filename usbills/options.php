<?php
require_once ('setup.php');

// Load search options if needed
if (!isset($_SESSION['options_loaded'])) {
    echo '<!-- Loading search options -->' . PHP_EOL;

    $client = new RESTClient($mlphp['host'], $mlphp['port'], $mlphp['path'], $mlphp['version'], $mlphp['username-admin'], $mlphp['password-admin'], $mlphp['auth']);

    // Set up options node
    $options = new Options($client);

    // Range constraint on session
    $session = new RangeConstraint('session', 'xs:int', 'false', 'bill', '', 'session');
    $options->addConstraint($session);

    // Range constraint on type
    $type = new RangeConstraint('type', 'xs:string', 'false', 'bill', '', 'type');
    $options->addConstraint($type);

    // Range constraint on number
    $type = new RangeConstraint('number', 'xs:int', 'false', 'bill', '', 'number');
    $options->addConstraint($type);

    // Range constraint on abbrev
    $type = new RangeConstraint('abbrev', 'xs:string', 'false', 'bill', '', 'abbrev');
    $options->addConstraint($type);

    // Range constraint on introduced
    $type = new RangeConstraint('introduced', 'xs:string', 'false', 'introduced', '', 'date');
    $options->addConstraint($type);

    // Range constraint on status
    $status = new RangeConstraint('status', 'xs:string', 'true', 'status');
    $options->addConstraint($status);

    // Range constraint on subject
    $keyword = new RangeConstraint('subject', 'xs:string', 'true', 'subject');
    $keyword->setFacetOptions(array('descending', 'frequency-order', 'limit=5'));
    $options->addConstraint($keyword);

    // Range constraint on title
    $title = new RangeConstraint('title', 'xs:string', 'false', 'title');
    $options->addConstraint($title);

    // Range constraint on link
    $title = new RangeConstraint('link', 'xs:string', 'false', 'link', '', 'href');
    $options->addConstraint($title);

    // Snippetting prefs
    $transform = new TransformResults('snippet');
    $pref1 = new PreferredElement('title', '');
    $pref2 = new PreferredElement('summary', '');
    $transform->addPreferredElements(array($pref1, $pref2));
    $options->setTransformResults($transform);

    // Metadata extracts
    $extracts = new Extracts();
    $extracts->addConstraints(array('title', 'status', 'subject', 'introduced', 'link', 'session', 'abbrev'));
    $options->setExtracts($extracts);

    // Term setting
    //$term = new Term("no-results");
    //$options->setTerm($term);

    // Write to database
    $optionsid = 'usbills';
    $response = $options->write($optionsid);

    echo '<!--' . $options->read($optionsid) . '-->' . PHP_EOL;

    $_SESSION['options_loaded'] = true;

} else {

    echo '<!-- Search options already loaded -->' . PHP_EOL;

}