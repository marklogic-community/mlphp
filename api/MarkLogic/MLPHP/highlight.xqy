xquery version "1.0-ml";

(:
Copyright 2002-2013 MarkLogic Corporation.  All Rights Reserved.

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

     http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
:)

module namespace mlphp-highlight = "http://marklogic.com/rest-api/resource/highlight";

(:
    Hit highlights incoming content
:)

declare function mlphp-highlight:post(
    $context as map:map,
    $params  as map:map,
    $input  as document-node()*
) as document-node()*
{

    let $class := xdmp:get-request-field("class") (: name of css class to be applied to hits :)
    let $c := xdmp:get-request-field("c") (: seralized content :)
    let $ct := xdmp:get-request-field("ct") (: content type ; text/plain or otherwise assumed to be XML :)
    let $q := xdmp:get-request-field("q") (: query - for now assumed to be string query :)

    let $_ := map:put($context, "output-types", $ct) (: put out what we take in :)

    return
        if ($ct eq 'text/plain') then
            cts:highlight(document { text {$c} } , $q, '<span class="' || $class || '">' || $cts:text || '</span>')
        else (: application/xml :)
            cts:highlight(xdmp:unquote($c), $q, <span>{attribute class {$class}}{$cts:text}</span>) 
};
