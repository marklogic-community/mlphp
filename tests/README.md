# MLPHP Test Notes

MLPHP uses [PHPUnit](https://phpunit.de) for testing. PHPUnit is loaded as a
[Composer](https://getcomposer.org) dependency.

To run all tests from the root folder (after loading the Composer
dependencies), run:

```
vendor/bin/phpunit tests
```

To run a specific test class (e.g. BucketTest):

```
vendor/bin/phpunit tests/MarkLogic/MLPHP/Test/BucketTest
```

To run a specific test (e.g. testBucket) in a class:

```
vendor/bin/phpunit --filter testBucket tests/MarkLogic/MLPHP/Test/BucketTest
```

For the most part, each MLPHP API class has a corresponding test file
under the tests directory. (However, search tests are split up in to multiple
files.)

Tests that rely on certain REST-based database configuration are skipped
if the MarkLogic version is < 8 since this configuration debuted in version
8.

TestBase classes provide setup, teardown, and other supporting functions.
They are extended by the test classes as needed. For example, methods in
[TestBaseSearch.php](https://github.com/marklogic/mlphp/blob/dev/tests/MarkLogic/MLPHP/Test/TestBaseSearch.php) load test documents and set up search indexes, and are used
by the search tests.

[TestData.php](https://github.com/marklogic/mlphp/blob/dev/tests/MarkLogic/MLPHP/Test/TestData.php) includes functions that return XML, JSON, and PHP mock data
used by tests.

Documents in the [docs folder](https://github.com/marklogic/mlphp/tree/dev/tests/MarkLogic/MLPHP/Test/docs) are loaded to support tests of search
functionality.
