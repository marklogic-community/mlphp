xquery version "1.0-ml";

module namespace mlphp-clear-db = "http://marklogic.com/rest-api/resource/clear-db";

declare function mlphp-clear-db:post(
    $context as map:map,
    $params  as map:map,
    $input  as document-node()*
) as document-node()*
{

xdmp:forest-clear(xdmp:database-forests(xdmp:database("mlphp-test")))

};
