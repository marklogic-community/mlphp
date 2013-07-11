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

// Text as a document
$doc1 = new MLPHP\Document($client);		// Create a Document object (passing the REST client)
$text1 = 'Hello, PHP';				// Define document text
$doc1->setContentType('text/text');		// Set the content type for the document
$doc1->setContent($text1);			// Set the text as the document content
$uri1 = '/example.txt';				// Define the URI for the document
$doc1->write($uri1);				// Write the document to the database

// Read the document from the database and display
echo "Read the text document:\n";
echo $doc1->read($uri1) . "\n\n";


// XML file
$doc2 = new MLPHP\Document($client);		// Create a Document object (passing the REST client)
$file2 = 'example.xml';				// Define the path to the file to write
$doc2->setContentType('application/xml');	// Set the content type for the document
$doc2->setContentFile($file2);			// Set the file as the document content
$uri2 = '/' . $file2;				// Define the URI for the document
$doc2->write($uri2);				// Write the document to the database

// Read the document from the database and display
echo "Read the file-based document:\n";
echo htmlspecialchars($doc2->read($uri2)) . "\n";


// JSON file
$doc3 = new MLPHP\Document($client);		// Create a Document object (passing the REST client)
$file3 = 'example.json';				// Define the path to the file to write
$doc3->setContentType('application/json');	// Set the content type for the document
$doc3->setContentFile($file3);			// Set the file as the document content
$uri3 = '/' . $file3;				// Define the URI for the document
$doc3->write($uri3);				// Write the document to the database

// Read the document from the database and display
echo "Read the JSON document:\n";
echo $doc3->read($uri3) . "\n\n";


// Binary image file
$doc4 = new MLPHP\Document($client);		// Create a Document object (passing the REST client)
$file4 = 'example.jpg';				// Define the path to the file to write
$doc4->setContentType('image/jpeg');		// Set the content type for the document
$doc4->setContentFile($file4);			// Set the file as the document content
$uri4 = '/' . $file4;				// Define the URI for the document
$doc4->write($uri4);				// Write the document to the database

// Embed the binary image file in a page with HTML
echo "Display the image document:\n";
displayImage($uri4, $doc4->getContentType()); // Helper function
echo "\n\n";


// Binary PDF file
$doc5 = new MLPHP\Document($client);		// Create a Document object (passing the REST client)
$file5 = 'example.pdf';				// Define the path to the file to write
$doc5->setContentType('application/pdf');	// Set the content type for the document
$doc5->setContentFile($file5);			// Set the file as the document content
$uri5 = '/' . $file5;				// Define the URI for the document
$doc5->write($uri5);				// Write the document to the database

// Link to the binary PDF file from the database
echo "Display a link to the PDF document:\n";
displayLink($uri5, $doc4->getContentType(), 'PDF File'); // Helper function
echo "\n\n";


// Files from a directory
$dir = __DIR__ . '/several';				// Get directory relative to current directory
$doc6 = new MLPHP\Document($client);			// Create a Document object (passing the REST client)
if ($handle = opendir($dir)) {				// Create a directory handle for reading
    echo 'Reading files from directory: ' . $dir . "\n\n";
    $files = array();
    while (false !== ($file = readdir($handle))) {	// Read each file in the directory
        if (substr($file, 0, 1) !== '.') {		// Ignore special files
            $files[] = $file;				// Store the file URIs in an array
            $doc6->setContentFile($dir . '/' . $file);	// Set the file as the document content
            $doc6->setContentType('application/xml');	// Set the content type for the document
            $uri6 = '/' . $file;			// Define the URI for the document
            $doc6->write($uri6);			// Write the document to the database
        }
    }
    closedir($handle);
}

// Read the documents from the database
echo "Read the documents:\n";
foreach ($files as $i => $file) {	// Loop through the file URIs
    echo 'File #' . ($i + 1) . ': ';
    $uri6 = '/' . $file;			// Define the URI for the document

    // Display each document
    echo htmlspecialchars($doc6->read($uri6)) . "\n";
}

// Delete a document
echo "Delete document '/example.txt'\n";
$doc1->delete('/example.txt');

// Attempt to read and display deleted document (error occurs)
echo "Attempt to read:\n";
try {
    echo $doc1->read('/example.txt') . "\n";
} catch(Exception $e) {
    if ($e->getCode() === 404) {
        echo 'Error code 404, resource not available';
    } else {
        echo 'Error code ' . $e->getCode();
    }
}


function displayImage($uri, $contentType) {
    echo '<img src="binary.php?uri=' . $uri . '&type=' . $contentType . '"/>';
}

function displayLink($uri, $contentType, $text) {
    echo '<a href="binary.php?uri=' . $uri . '&type=' . $contentType . '">' . $text . '</a>';
}

?>
