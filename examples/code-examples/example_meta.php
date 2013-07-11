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
// Setup
use MarkLogic\MLPHP as MLPHP;
require_once ('setup.php');
$client = $mlphp->newClient();
$client->setUsername($mlphp->config['username-admin']);
$client->setPassword($mlphp->config['password-admin']);

// Write a sample document
$doc1 = new MLPHP\Document($client);
$file1 = 'example.xml';
$doc1->setContentFile($file1);
$doc1->setContentType('application/xml');
$uri1 = '/' . $file1;
$doc1->write($uri1);


// Reset metadata to default by deleting
$doc1->deleteMetadata();				// Delete the metadata for the document
$meta1 = $doc1->readMetadata();			// Read the metadata for the document
echo "Starting collections:\n";
print_r($meta1->getCollections());		// Read and display the collections metadata for the document
echo "Starting properties:\n";
print_r($meta1->getProperties());		// Read and display the properties metadata for the document
echo "Starting permissions:\n";
print_r($meta1->getPermissions());		// Read and display the permissions metadata for the document
echo "Starting quality:\n";
print_r($meta1->getQuality());			// Read and display the quality metadata for the document
echo "\n\n\n";


// Collections
$collections = array('food', 'fruit');		// Define an array of collections
$meta1->addCollections($collections);		// Add the collections to the metadata object
echo "Two collections added:\n";
print_r($meta1->getCollections());		// Read and display the collections in the metadata object
echo "One more collection added:\n";
$meta1->addCollections('red');			// Add a collection to the metadata object as a string
print_r($meta1->getCollections());		// Read and display the collections in the metadata object
echo "One collection deleted:\n";
$meta1->deleteCollections('fruit');		// Delete a collection from the metadata object
print_r($meta1->getCollections());		// Read and display the collections in the metadata object
$doc1->writeMetadata($meta1);			// Write the metadata object for the document to the database
echo "Final written collections:\n";
$meta1 = $doc1->readMetadata();			// Read the metadata for the document
print_r($meta1->getCollections());		// Read and display the collections in the metadata object
echo "\n\n\n";


// Properties
$properties = array('size' => 'large', 		// Define an associative array of properties
                    'color' => 'blue',
                    'qty' => 12);
$meta1->addProperties($properties);		// Add the properties to the metadata object
echo "Three properties added:\n";
print_r($meta1->getProperties());		// Read and display the properties in the metadata object
$meta1->deleteProperties('color');		// Delete a property from the metadata object
echo "One property deleted:\n";
print_r($meta1->getProperties());		// Read and display the properties in the metadata object
$doc1->writeMetadata($meta1);			// Write the metadata object for the document to the database
echo "Final written properties:\n";
$meta1 = $doc1->readMetadata();			// Read the metadata for the document
print_r($meta1->getProperties());		// Read and display the properties in the metadata object
echo "\n\n\n";


// Permissions
// PREREQUISITE: Create 'doc-reader', 'doc-admin', and 'doc-editor' roles in Admin interface
$perm1 = new MLPHP\Permission('doc-reader', 'read');	// Create a new Permission object for doc-reader
$perm2 = new MLPHP\Permission('doc-admin', 		// Create a new Permission object for doc-admin
                        array('read',
                              'update',
                              'insert')
                        );
$perm_arr = array($perm1, $perm2);		// Store the permissions objects in an array
$meta1->addPermissions($perm_arr);		// Add the permissions to the metadata object
echo "Permissions added for doc-reader and doc-admin:\n";
print_r($meta1->getPermissions());		// Read and display the permissions in the metadata object
$perm3 = new MLPHP\Permission('doc-editor', 	// Create a new Permission object for doc-editor
                        array('read',
                              'update')
                        );
$meta1->addPermissions($perm3);			// Add the permission to the metadata object
echo "Permission added for doc-editor:\n";
print_r($meta1->getPermissions());		// Read and display the permissions in the metadata object
$meta1->deletePermissions('doc-reader');		// Delete the permission object for doc-reader
echo "Permission deleted for doc-reader:\n";
print_r($meta1->getPermissions());		// Read and display the permissions in the metadata object
$doc1->writeMetadata($meta1);			// Write the metadata object for the document to the database
echo "Final written permissions:\n";
$meta1 = $doc1->readMetadata();			// Read the metadata for the document
print_r($meta1->getPermissions());		// Read and display the permissions in the metadata object
echo "\n\n\n";


// Quality
$quality = 9;					// Define a quality value
$meta1->setQuality($quality);			// Set the quality for the metadata object
echo "Quality updated:\n";
echo $meta1->getQuality() . PHP_EOL;		// Read and display the quality of the metadata object
$doc1->writeMetadata($meta1);			// Write the metadata object for the document to the database
echo "Final written quality:\n";
$meta1 = $doc1->readMetadata();			// Read the metadata for the document
print_r($meta1->getQuality());			// Read and display the quality for the metadata object
echo "\n\n\n";


// Update multiple metadata at once via method chaining
$meta1->addCollections(array('sugary', 'fresh'))->addProperties(array('rating' => '9/10'));
$perm4 = new MLPHP\Permission('doc-editor', array('read', 'update', 'insert'));
$meta1->addPermissions($perm4)->setQuality($meta1->getQuality() + 1);
echo "Metadata (collections, properties, permissions, and quality) updated via method chaining:\n";
// Write, read, and display
$doc1->writeMetadata($meta1);
print_r($doc1->readMetadata());
echo "\n\n\n";


// Update metadata simultaneously with document write
$doc2 = new MLPHP\Document($client);
$doc2->setContent('More content');
$uri2 = '/example_updated.xml';
// Add metadata as params
$params2 = array('collection' => 'round',
                 'prop:status' => 'current',
                 'perm:doc-editor' => 'insert',
                 'quality' => 99
                );
echo "Metadata updated via params:\n";
// Write, read, and display
$doc2->write($uri2, $params2);
print_r($doc2->readMetadata());
echo "\n\n\n";
