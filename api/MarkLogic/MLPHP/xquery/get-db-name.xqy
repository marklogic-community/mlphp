xquery version "1.0-ml";

module namespace mlphp-get-db-name = "http://marklogic.com/rest-api/resource/get-db-name";

declare function mlphp-get-db-name:get(
    $context as map:map,
    $params  as map:map
) as document-node()*
{

let $output-types := map:put($context, "output-types", "text/plain") 

return document { text { xdmp:database-name(xdmp:database()) } }

};
