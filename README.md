# MLPHP

## PHP API for MarkLogic

Developers can now build powerful search applications in PHP using MarkLogic
Server with an open source PHP API. The PHP API makes it easy to store
documents, manage document metadata, and create sophisticated search queries
on a web server running PHP (version 5.3 or greater). The PHP classes
communicate with the MarkLogic via the MarkLogic 6 REST API.

After installing MLPHP (see INSTALL.txt), you can load documents into
MarkLogic with just a couple lines of PHP code:

    $document = new MLPHP\Document($client);
    $document->setContentFile('myfile.xml')->write('/myfile.xml');

Searching is just as easy:

    $search = new MLPHP\Search($client);
    $results = $search->retrieve('cat');

## Dependencies
MLPHP requires [MarkLogic 6 or later](http://developer.marklogic.com/products). Download the server, start it, and request a  a free [Developer or Express license](http://developer.marklogic.com/licensing) directly from the Admin UI on port 8001.


## Installation
You can install MLPHP via [Composer](http://getcomposer.org) by adding MLPHP to your requirements

    {
        requires: {
            "MarkLogic\MLPHP" : "*"
        }
    }

## Examples
MLPHP comes with a series of examples.  

## API Documentation
API Docs are [here](https://marklogic.github.io/mlphp)

## License 
MLPHP is licensed under the Apache License, Version 2.0 (see LICENSE.txt).

## Copyright
Copyright 2002-2013 MarkLogic Corporation.  All Rights Reserved.

