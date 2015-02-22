# MLPHP

## PHP API for MarkLogic

MLPHP is a PHP API for MarkLogic that makes it easy to store documents, manage document metadata, and create sophisticated search queries on a web server running PHP (version 5.4 or greater). The PHP classes communicate with the MarkLogic via the [MarkLogic REST API](http://developer.marklogic.com/learn/rest).

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

* [MarkLogic 7 or later](http://developer.marklogic.com/products).  Download the server, start it, and request a free [Developer or Express license](http://developer.marklogic.com/licensing) directly from the Admin UI on port 8001.
* PHP 5.4-or-later-enabled web server (e.g., Apache running PHP) with the following extension libraries (typically available by default)
	* DOM
	* cURL
	* XSL
* [MLPHP](https://github.com/marklogic/mlphp)
* [Composer](http://getcomposer.org).

## Installation
To add MLPHP to your project, simply add MLPHP as a Composer requirement in composer.json:

    {
        "require": {
            "marklogic/mlphp" : "dev-master"
        }
    }

And then, depending on how you installed Composer, run

    % composer install

or

    % php composer.phar install


## API Documentation
API docs are available [online](http://marklogic.github.io/mlphp).  A copy is also provided inside the MLPHP repo under `api/docs`.

## License
MLPHP is licensed under the Apache License, Version 2.0 (see LICENSE.txt).

## Status
MLPHP is in early-stage development, but ready for use.  The API is subject to change.

## Examples
MLPHP examples are being revised.


## Development

### Building

    % git clone git@github.com:marklogic/mlphp mlphp
    % cd mlphp
    % composer install

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
Unit tests can be found under `tests`. To run,

1. Edit `phpunit-config.php` and set configuration variables specific to your MarkLogic setup.
2. Run the tests:

        % vendor/bin/phpunit tests

MLPHP uses [PHPUnit](https://phpunit.de) for testing. See the tests [README.md](https://github.com/marklogic/mlphp/blob/dev/tests/README.md) for more.

### Contributing
You can request a new feature by submitting an issue to the project's [GitHub Issue Tracker]
(https://github.com/marklogic/mlphp/issues).

Please submit [pull requests](https://help.github.com/articles/using-pull-requests/) to the `develop` branch.

## Copyright
Copyright 2002-2015 MarkLogic Corporation.  All Rights Reserved.

