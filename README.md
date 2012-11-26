# MLPHP

PHP API for MarkLogic

Developers can now build powerful search applications in PHP using MarkLogic
Server with an open source PHP API. The PHP API makes it easy for PHP
developers to store documents, manage document metadata, and create
sophisticated search queries on a web server running PHP 5. The PHP classes
communicate with the MarkLogic via the MarkLogic 6 REST API.

After installing MLPHP (see INSTALL.txt), you can load documents into
MarkLogic with just a couple lines of PHP code:

    $document = new Document($client);
    $document->setContentFile('myfile.xml')->write('/myfile.xml');

Searching is just as easy:

    $search = new Search($client);
    $results = $search->retrieve('cat');

MLPHP requires MarkLogic 6. To download the server and use the free Express
license, see:

http://developer.marklogic.com/express

If you want to build applications in Java, MarkLogic 6 also has a Java API.

MLPHP is licensed under the Apache License, Version 2.0 (see LICENSE.txt).

Copyright 2002-2012 MarkLogic Corporation.  All Rights Reserved.