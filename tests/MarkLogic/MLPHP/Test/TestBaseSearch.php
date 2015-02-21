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
namespace MarkLogic\MLPHP\Test;

use MarkLogic\MLPHP;

/**
 * @package MLPHP\Test
 * @author Eric Bloch <eric.bloch@gmail.com>
 * @author Mike Wooldridge <mike.wooldridge@marklogic.com>
 *
 * Supports search tests (of XML and JSON content)
 */
abstract class TestBaseSearch extends TestBaseDB
{

    public static function loadDocsText($client)
    {
        $doc = new MLPHP\Document(parent::$client, '/text.txt');
        $doc->setContent('Hello MLPHP!!!')->setContentType('text/text')->write();
    }

    public static function loadDocsXML($client)
    {
        $rootdir = __DIR__ . DIRECTORY_SEPARATOR . 'docs';
        $subdirs = array('110', '111', '112'); // directories to import from

        // Loop through files from subdirectories
        $count = 0;
        foreach($subdirs as $subdir) {
            $dir = $rootdir . DIRECTORY_SEPARATOR . $subdir;
            if ($handle = opendir($dir)) {
                parent::$logger->debug(
                    "Writing files from directory: " . $dir
                );
                $doc = new MLPHP\Document($client);
                while (false !== ($file = readdir($handle))) {
                    if (substr($file, 0, 1) !== ".") {
                        $doc->setContentType("application/xml");
                        $content = $doc->setContentFile($dir . '/' . $file)->getContent();
                        $uri = '/bills/' . $subdir . '/' . $file; // URI example: '/bills/112/h321.xml'
                        $dom = new \DOMDocument();
                        $dom->loadXML($content);
                        // Only write bills with related bills and short titles
                        $num_rel_bills = $dom->getElementsByTagName('relatedbill')->length;
                        $len_title = strlen($dom->getElementsByTagName('title')->item(0)->nodeValue);
                        if ($num_rel_bills == 0 || $len_title > 80) {
                            continue;
                        }
                        $xpath = new \DOMXPath($dom);
                        // Set collection base on bill type. Example: 'hr' (House resolution)
                        $type = $xpath->query('//bill/@type')->item(0)->nodeValue;
                        $params = array("collection" => $type);
                        $sess = $xpath->query('//bill/@session')->item(0)->nodeValue;
                        $params['prop:sess'] = $sess;
                        $count++;
                        parent::$logger->debug(
                            $count . ': ' . $uri . ' (' . $type . ')'
                        );
                        // Write content to database via REST client
                        $doc->write($uri, $params);
                    }
                }
                closedir($handle);
            }
        }

        parent::$logger->debug('XML files loaded: ' . $count);
    }

    public static function loadDocsJSON($client)
    {
        // Load json files
        $dir = __DIR__ . DIRECTORY_SEPARATOR . 'docs' . DIRECTORY_SEPARATOR . 'nv';

        $count = 0;
        if ($handle = opendir($dir)) {
            parent::$logger->debug(
                "Writing files from directory: " . $dir
            );
            $doc = new MLPHP\Document($client);
            while (false !== ($file = readdir($handle))) {
                if (substr($file, 0, 1) !== ".") {
                    $doc->setContentType("application/json");
                    $content = $doc->setContentFile($dir . '/' . $file)->getContent();
                    $uri = '/legislators/' . $file; // URI example: '/legislators/Care.json'
                    $obj = json_decode($content);
                    $params = array("collection" => $obj->{'old_roles'}->{'2009-2010'}[0]->party);
                    $count++;
                    parent::$logger->debug(
                        $count . ': ' . $uri
                    );
                    // Write content to database via REST client
                    $doc->write($uri, $params);
                }
            }
            closedir($handle);
        }

        parent::$logger->debug('JSON files loaded: ' . $count);
    }

    public static function setIndexesXML($manageClient)
    {
        parent::$logger->debug('setIndexes');
        $db = new MLPHP\Database($manageClient, 'mlphp-test-db');

        // Set range attribute indexes
        parent::$logger->debug('Add Range Attribute Index: type');
        $type = array(
            'scalar-type' => 'string',
            'parent-localname' => 'bill',
            'localname' => 'type'
        );
        $db->addRangeAttributeIndex($type);
        parent::$logger->debug('Add Range Attribute Index: number');
        $number = array(
            'scalar-type' => 'int',
            'parent-localname' => 'bill',
            'localname' => 'number'
        );
        $db->addRangeAttributeIndex($number);

        // Set path range index
        parent::$logger->debug('Add Range Attribute Index: date');
        $date = array(
            'scalar-type' => 'string',
            'path-expression' => 'introduced/@date'
        );
        $db->addRangePathIndex($date);

        // Set range element indexes
        parent::$logger->debug('Add Range Element Index: status');
        $status = array(
            'scalar-type' => 'string',
            'localname' => 'status'
        );
        $db->addRangeElementIndex($status);
        parent::$logger->debug('Add Range Element Index: subject');
        $subject = array(
            'scalar-type' => 'string',
            'localname' => 'subject'
        );
        $db->addRangeElementIndex($subject);
        parent::$logger->debug('Add Range Element Index: title');
        $title = array(
            'scalar-type' => 'string',
            'localname' => 'title'
        );
        $db->addRangeElementIndex($title);

        // Fields
        parent::$logger->debug('Add Field Range Indexes');
        $fieldPath = new MLPHP\FieldPath(array(
            'path' => 'meta',
            'weight' => 1.5
        ));
        $included = new MLPHP\FieldElementIncluded(array(
            'localname' => 'number',
            'weight' => 1.7
        ));
        $excluded1 = new MLPHP\FieldElementExcluded(array(
            'localname' => 'session'
        ));
        $excluded2 = new MLPHP\FieldElementExcluded(array(
            'localname' => 'abbrev'
        ));

        // Field Range Index: type, number
        $field1 = new MLPHP\Field(array(
            'field-name' => 'field1',
            'field-path' => $fieldPath,
            'excluded-element' => array($excluded1, $excluded2)
        ));
        $db->addField($field1);
        $db->addRangeFieldIndex(array(
            'field-name' => 'field1'
        ));

        // Field Range Index: session/number, type, number
        $field2 = new MLPHP\Field(array(
            'field-name' => 'field2',
            'field-path' => $fieldPath,
            'included-element' => $included,
            'excluded-element' => array($excluded1, $excluded2)
        ));
        $db->addField($field2);
        $db->addRangeFieldIndex(array(
            'field-name' => 'field2'
        ));

        // Field: type, number, abbrev
        $field3 = new MLPHP\Field(array(
            'field-name' => 'field3',
            'field-path' => $fieldPath,
            'excluded-element' => $excluded1
        ));
        $db->addField($field3);

        // to enable collection constraints
        parent::$logger->debug('Enable Collection Lexicon');
        $db->setProperty('collection-lexicon', 'true');

    }

    public static function setIndexesJSON($manageClient)
    {
        parent::$logger->debug('setIndexes');
        $db = new MLPHP\Database($manageClient, 'mlphp-test-db');

        // @todo define a path expression that works
        // $party = array(
        //     'scalar-type' => 'string',
        //     // The following errors due to hyphen '-'
        //     'path-expression' => '//old_roles/2009-2010[0]/party'
        // );
        // $db->addRangePathIndex($party);

        $id = array(
            'scalar-type' => 'string',
            'localname' => 'id'
        );
        $db->addRangeElementIndex($id);

        $address = array(
            'scalar-type' => 'string',
            'localname' => 'address'
        );
        $db->addRangeElementIndex($address);

    }

    public static function setOptionsXML($client)
    {
        parent::$logger->debug('setOptions');
        $options = new MLPHP\Options($client);

        // Range constraint on session
        $session = new MLPHP\RangeConstraint(
            'session', 'xs:int', 'false', 'bill', '', 'session'
        );
        $options->addConstraint($session);

        // Range constraint on type
        $type = new MLPHP\RangeConstraint(
            'type', 'xs:string', 'false', 'bill', '', 'type'
        );
        $options->addConstraint($type);

        // Range constraint on number
        $type = new MLPHP\RangeConstraint(
            'number', 'xs:int', 'false', 'bill', '', 'number'
        );
        $options->addConstraint($type);

        // Range constraint on abbrev
        $type = new MLPHP\RangeConstraint(
            'abbrev', 'xs:string', 'false', 'bill', '', 'abbrev'
        );
        $options->addConstraint($type);

        // Range constraint on introduced
        $type = new MLPHP\RangeConstraint(
            'introduced', 'xs:string', 'false', 'introduced', '', 'date'
        );
        $options->addConstraint($type);

        // Range constraint on status
        $status = new MLPHP\RangeConstraint(
            'status', 'xs:string', 'true', 'status'
        );
        $options->addConstraint($status);

        // Range constraint on subject
        $keyword = new MLPHP\RangeConstraint(
            'subject', 'xs:string', 'true', 'subject'
        );
        $keyword->setFacetOptions(
            array('descending', 'frequency-order', 'limit=5')
        );
        $options->addConstraint($keyword);

        // Range constraint on title
        // $title = new MLPHP\RangeConstraint(
        //     'title', 'xs:string', 'false', 'title'
        // );
        // $options->addConstraint($title);

        // Range constraint on link
        $title = new MLPHP\RangeConstraint(
            'link', 'xs:string', 'false', 'link', '', 'href'
        );
        $options->addConstraint($title);

        // Snippetting prefs
        $transform = new MLPHP\TransformResults('snippet');
        //$pref1 = new MLPHP\PreferredElement('title', '');
        $pref2 = new MLPHP\PreferredElement('summary', '');
        //$transform->addPreferredElements(array($pref1, $pref2));
        $transform->addPreferredElements(array($pref2));
        $options->setTransformResults($transform);

        // Metadata extracts
        $extracts = new MLPHP\Extracts();
        $extracts->addConstraints(
            //array('title', 'status', 'subject', 'introduced',
            array('status', 'subject', 'introduced',
                  'link', 'session', 'abbrev'
            )
        );
        $options->setExtracts($extracts);

        $options->setReturnSimilar('true');
        $options->setReturnQuery('true');

        // Term setting
        //$term = new MLPHP\Term("no-results");
        //$options->setTerm($term);

        $options->write('test');
    }

}

