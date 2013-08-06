# MLPHP

## PHP API for MarkLogic

MLPHP is a PHP API for MarkLogic that makes it easy to store documents, manage document metadata, and create sophisticated search queries on a web server running PHP (version 5.3 or greater). The PHP classes communicate with the MarkLogic via the [MarkLogic REST API](http://developer.marklogic.com/learn/rest).

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
* [Composer](http://getcomposer.org).

## Installation
To add MLPHP to your project, simply add MLPHP to your Composer requirement in composer.json:

    {
        require: {
            "marklogic\mlphp" : "dev-master"
        }
    }

And then, depending on how you installed Composer, you can do

    % composer install

or

    % php composer.phar install


## Examples
MLPHP comes with a series of examples. To run them, see the [instructions](https://github.com/marklogic/mlphp/blob/master/examples/README.md).  

## API Documentation
API docs are available [online](http://marklogic.github.io/mlphp).  A copy is also provided inside the MLPHP repo under `api/docs` and surfaces from the examples index.

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
        % vendor/bin/phpdoc mlphp.ini
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
        % vendor/bin/phpdoc mlphp-ghpages.ini
        % cd PATH_TO_MLPHP/mlphp/api/docs-ghpages
        % git add .
        % git commit -a -m "New docs"
        % git push origin gh-pages
        
### Unit tests
Beginnings of unit tests can be found under `tests`.  To run, 

1. Create a database for the tests, named `mlphp-tests` and attache a REST API instance to it. You can do this by importing the DB configuration via the MarkLogic Configuration manager 
UI at `http://localhost:8002` (change your hostname as you need) found under `tests/setup/package.xml`.
2. Edit `phpunit-bootstrap.php` and set configuration variables specific to your REST API instance.
3. Run the tests 

        % vendor/bin/phpunit --bootstrap phpunit-bootstrap.php tests

### Contributing
TBD...

## Copyright
Copyright 2002-2013 MarkLogic Corporation.  All Rights Reserved.

