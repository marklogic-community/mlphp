# MLPHP Test Notes

MLPHP uses [PHPUnit](https://phpunit.de) for testing. PHPUnit is loaded as a
[Composer](https://getcomposer.org) dependency.

To run all tests from the root folder (after loading the Composer
dependencies), run:

```
vendor/bin/phpunit tests/MarkLogic/MLPHP/Test
```

To run a specific test class (e.g. BucketTest):

```
vendor/bin/phpunit tests/MarkLogic/MLPHP/Test/BucketTest
```

To run a specific test in a test class:

```
vendor/bin/phpunit --filter testBucket tests/MarkLogic/MLPHP/Test/BucketTest
```

For the most part, each MLPHP API class has a corresponding test file
under the tests directory. (Search tests are split up in to multiple files.)

Tests that rely on certain REST-based database configuration are skipped
if the MarkLogic version is < 8, since this configuration debuted in version
8.

TestBase classes provide setup, teardown, and other supporting functions.
They are extended by the test classes as needed. For example, methods in
TestBaseSearch.php load test documents, set up search indexes, and are used
by the search tests.

TestData.php includes functions that return XML, JSON, and PHP mock data
used by tests.

Various government documents are located in the docs folder. These are
loaded to support tests of search functionality.
