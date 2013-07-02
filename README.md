# MLPHP

## PHP API for MarkLogic

Developers can now build powerful search applications in PHP using MarkLogic Server with an open source PHP API. The PHP API makes it easy to store documents, manage document metadata, and create sophisticated search queries on a web server running PHP (version 5.3 or greater). The PHP classes communicate with the MarkLogic via the [MarkLogic REST API](http://developer.marklogic.com/learn/rest).

After installing MLPHP (see below), you can load documents into
MarkLogic with just a couple lines of PHP code:

    use MarkLogic\MLPHP;
    
    $document = new MLPHP\Document($client);
    $document->setContentFile('myfile.xml')->write('/myfile.xml');

Searching is just as easy:

    use MarkLogic\MLPHP;
    
    $search = new MLPHP\Search($client);
    $results = $search->retrieve('cat');
    
More details are available in the examples described below.

## Dependencies

* [MarkLogic 6 or later](http://developer.marklogic.com/products).  Download the server, start it, and request a  a free [Developer or Express license](http://developer.marklogic.com/licensing) directly from the Admin UI on port 8001.
* PHP 5.3-or-later-enabled web server (e.g., Apache running PHP) with the following extension libraries (typically available by default)
	* DOM
	* cURL 
	* XSL 
* [MLPHP](https://github.com/marklogic/mlphp)

Development of MLPHP also requires [Composer](http://getcomposer.org).

## Installation
You can add MLPHP to your project via [Composer](http://getcomposer.org) by adding MLPHP to your requirements

    {
        requires: {
            "MarkLogic\MLPHP" : "*"
        }
    }

Alternatively, you can just grab a copy of the [MLPHP GitHub repository](https://github.com/marklogic/mlphp).

## Examples
MLPHP comes with a series of examples. To run them, see the [instructions](https://github.com/marklogic/mlphp/blob/master/examples/README.md).  

## API Documentation
API docs are available [online](https://marklogic.github.io/mlphp).  A copy is also provided inside the MLPHP repo under `api/docs` and surfaces from the examples index.

## License 
MLPHP is licensed under the Apache License, Version 2.0 (see LICENSE.txt).

## Status
MLPHP is in early-stage development, but ready-for-use.  The API is subject to change.

## Development

### Building

    % git clone git@github.com:marklogic/mlphp mlphp
    % cd mlphp
    % composer update 

### To generate a clean set of new API docs from source

1. Update master branch

        % cd $PATH_TO_MLPHP/mlphp/api/docs
        % git rm -rf *
        % cd $PATH_TO_MLPHP/mlphp/
        % php vendor/bin/phpdoc.php mlphp.ini
        % cd $PATH_TO_MLPHP/mlphp/api/docs
        % git add .
        % git commit -a -m "New docs"
        % git push origin master

2. Update gh-pages copy  (Technique borrowed from https://gist.github.com/825950)

        % cd $PATH_TO_MLPHP/mlphp/api
        
   If this is the first time,

        % git clone -b gh-pages \
            --single-branch git@github.com:marklogic/mlphp docs-ghpages
        
   Then...

        % cd $PATH_TO_MLPHP/mlphp/api/docs-ghpages
        % git rm -rf [a-z]*
        % cd $PATH_TO_MLPHP/mlphp
        % php vendor/bin/phpdoc.php mlphp-ghpages.ini
        % cd PATH_TO_MLPHP/mlphp/api/docs-ghpages
        % git commit -a -m "New docs"
        % git push origin gh-pages
        
### Unit tests
TBD…

### Contributing
TBD...

## Copyright
Copyright 2002-2013 MarkLogic Corporation.  All Rights Reserved.

