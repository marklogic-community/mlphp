<?php
/*
Copyright 2002-2012 MarkLogic Corporation.  All Rights Reserved.

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

     http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
*/
namespace MarkLogic\MLPHP\Test;

/**
 * @package MLPHP\Test
 * @author Eric Bloch <eric.bloch@gmail.com>
 * @author Mike Wooldridge <mike.wooldridge@marklogic.com>
 */
class TestData
{

    public static function getSearchResult()
    {
        $result = '
          <search:response snippet-format="snippet" total="37" start="11" page-length="5" xmlns:search="http://marklogic.com/appservices/search">
            <search:result index="11" uri="/bills/111/h1258.xml" path="fn:doc(&quot;/bills/111/h1258.xml&quot;)" score="50688" confidence="0.4909869" fitness="0.8105518" href="/v1/documents?uri=%2Fbills%2F111%2Fh1258.xml" mimetype="application/xml" format="xml">
              <search:similar>/bills/111/h1258.xml</search:similar>
              <search:similar>/bills/110/h1117.xml</search:similar>
              <search:snippet>
                <search:match path="fn:doc(&quot;/bills/111/h1258.xml&quot;)/bill/summary">...United States, in connection with any real time voice communications, regardless of the <search:highlight>technology</search:highlight> or network used, to cause any caller identification service to transmit misleading or...</search:match>
              </search:snippet>
              <search:metadata>
                <search:constraint-meta name="title">Truth in Caller ID Act of 2009</search:constraint-meta>
                <search:constraint-meta name="status">vote</search:constraint-meta>
                <search:constraint-meta name="subject">Administrative law and regulatory procedures</search:constraint-meta>
                <search:constraint-meta name="subject">Federal Communications Commission (FCC)</search:constraint-meta>
                <search:constraint-meta name="subject">Telephone and wireless communication</search:constraint-meta>
                <search:constraint-meta name="introduced">2009-03-03</search:constraint-meta>
                <search:constraint-meta name="link">http://thomas.loc.gov/cgi-bin/query/z?c111:H.R.1258:</search:constraint-meta>
                <search:constraint-meta name="session">111</search:constraint-meta>
                <search:constraint-meta name="abbrev">H.R. 1258</search:constraint-meta>
                <search:bar>baz</search:bar>
              </search:metadata>
            </search:result>
            <search:result index="12" uri="/bills/111/h1262.xml" path="fn:doc(&quot;/bills/111/h1262.xml&quot;)" score="50688" confidence="0.4909869" fitness="0.8105518" href="/v1/documents?uri=%2Fbills%2F111%2Fh1262.xml" mimetype="application/xml" format="xml">
              <search:similar>/bills/111/h1262.xml</search:similar>
              <search:similar>/bills/112/h1189.xml</search:similar>
              <search:snippet>
                <search:match path="fn:doc(&quot;/bills/111/h1262.xml&quot;)/bill/summary">...of a municipality-wide plan that identifies the most effective placement of stormwater <search:highlight>technologies</search:highlight> and management approaches to reduce water quality impairments from storm water on...</search:match>
              </search:snippet>
              <search:metadata>
                <search:constraint-meta name="title">Water Quality Investment Act of 2009</search:constraint-meta>
                <search:constraint-meta name="status">vote</search:constraint-meta>
                <search:constraint-meta name="subject">Advanced technology and technological innovations</search:constraint-meta>
                <search:constraint-meta name="subject">Buy American requirements</search:constraint-meta>
                <search:constraint-meta name="subject">Chesapeake Bay</search:constraint-meta>
                <search:constraint-meta name="subject">Congressional oversight</search:constraint-meta>
                <search:constraint-meta name="introduced">2009-03-03</search:constraint-meta>
                <search:constraint-meta name="link">http://thomas.loc.gov/cgi-bin/query/z?c111:H.R.1262:</search:constraint-meta>
                <search:constraint-meta name="session">111</search:constraint-meta>
                <search:constraint-meta name="abbrev">H.R. 1262</search:constraint-meta>
                <search:bar>baz</search:bar>
              </search:metadata>
            </search:result>
            <search:result index="13" uri="/bills/111/h1011.xml" path="fn:doc(&quot;/bills/111/h1011.xml&quot;)" score="50688" confidence="0.4909869" fitness="0.8105518" href="/v1/documents?uri=%2Fbills%2F111%2Fh1011.xml" mimetype="application/xml" format="xml">
              <search:similar>/bills/111/h1011.xml</search:similar>
              <search:snippet>
                <search:match path="fn:doc(&quot;/bills/111/h1011.xml&quot;)/bill/subjects/subject[2]">Computers and information <search:highlight>technology</search:highlight></search:match>
                <search:match path="fn:doc(&quot;/bills/111/h1011.xml&quot;)/bill/subjects/subject[12]">Health <search:highlight>technology</search:highlight>, devices, supplies</search:match>
              </search:snippet>
              <search:metadata>
                <search:constraint-meta name="title">Community Mental Health Services Improvement Act</search:constraint-meta>
                <search:constraint-meta name="status">introduced</search:constraint-meta>
                <search:constraint-meta name="introduced">2009-02-12</search:constraint-meta>
                <search:constraint-meta name="link">http://thomas.loc.gov/cgi-bin/query/z?c111:H.R.1011:</search:constraint-meta>
                <search:constraint-meta name="session">111</search:constraint-meta>
                <search:constraint-meta name="abbrev">H.R. 1011</search:constraint-meta>
                <search:bar>baz</search:bar>
              </search:metadata>
            </search:result>
            <search:result index="14" uri="/bills/111/h1147.xml" path="fn:doc(&quot;/bills/111/h1147.xml&quot;)" score="50688" confidence="0.4909869" fitness="0.8105518" href="/v1/documents?uri=%2Fbills%2F111%2Fh1147.xml" mimetype="application/xml" format="xml">
              <search:similar>/bills/111/h1147.xml</search:similar>
              <search:snippet>
                <search:match path="fn:doc(&quot;/bills/111/h1147.xml&quot;)/bill/subjects/subject[1]">Science, <search:highlight>technology</search:highlight>, communications</search:match>
                <search:match path="fn:doc(&quot;/bills/111/h1147.xml&quot;)/bill/subjects/subject[3]">Broadcasting, cable, digital <search:highlight>technologies</search:highlight></search:match>
              </search:snippet>
              <search:metadata>
                <search:constraint-meta name="title">Local Community Radio Act of 2009</search:constraint-meta>
                <search:constraint-meta name="status">vote</search:constraint-meta>
                <search:constraint-meta name="subject">Administrative law and regulatory procedures</search:constraint-meta>
                <search:constraint-meta name="introduced">2009-02-24</search:constraint-meta>
                <search:constraint-meta name="link">http://thomas.loc.gov/cgi-bin/query/z?c111:H.R.1147:</search:constraint-meta>
                <search:constraint-meta name="session">111</search:constraint-meta>
                <search:constraint-meta name="abbrev">H.R. 1147</search:constraint-meta>
                <search:bar>baz</search:bar>
              </search:metadata>
            </search:result>
            <search:result index="15" uri="/bills/110/h1068.xml" path="fn:doc(&quot;/bills/110/h1068.xml&quot;)" score="50688" confidence="0.4909869" fitness="0.8105518" href="/v1/documents?uri=%2Fbills%2F110%2Fh1068.xml" mimetype="application/xml" format="xml">
              <search:similar>/bills/110/h1068.xml</search:similar>
              <search:similar>/bills/110/h121.xml</search:similar>
              <search:similar>/bills/111/h1.xml</search:similar>
              <search:snippet>
                <search:match path="fn:doc(&quot;/bills/110/h1068.xml&quot;)/bill/summary">...National High-Performance Computing Program. Requires the Director of the Office of Science and <search:highlight>Technology</search:highlight> Policy to: (1) establish the goals and priorities for federal high-performance...</search:match>
              </search:snippet>
              <search:metadata>
                <search:constraint-meta name="title">To amend the High-Performance Computing Act of 1991.</search:constraint-meta>
                <search:constraint-meta name="status">vote</search:constraint-meta>
                <search:constraint-meta name="subject">Computer networks</search:constraint-meta>
                <search:constraint-meta name="subject">Computer security measures</search:constraint-meta>
                <search:constraint-meta name="introduced">2007-02-15</search:constraint-meta>
                <search:constraint-meta name="link">http://thomas.loc.gov/cgi-bin/query/z?c110:H.R.1068:</search:constraint-meta>
                <search:constraint-meta name="session">110</search:constraint-meta>
                <search:constraint-meta name="abbrev">H.R. 1068</search:constraint-meta>
                <search:bar>baz</search:bar>
              </search:metadata>
            </search:result>
            <search:facet name="status" type="xs:string">
              <search:facet-value name="enacted" count="6">enacted</search:facet-value>
              <search:facet-value name="introduced" count="16">introduced</search:facet-value>
              <search:facet-value name="vote" count="15">vote</search:facet-value>
            </search:facet>
            <search:facet name="subject" type="xs:string">
              <search:facet-value name="Government investigations" count="12">Government investigations</search:facet-value>
              <search:facet-value name="Science, technology, communications" count="12">Science, technology, communications</search:facet-value>
              <search:facet-value name="Congressional reporting requirements" count="11">Congressional reporting requirements</search:facet-value>
              <search:facet-value name="Department of Health and Human Services" count="11">Department of Health and Human Services</search:facet-value>
              <search:facet-value name="Government operations and politics" count="11">Government operations and politics</search:facet-value>
            </search:facet>
            <search:qtext>technology</search:qtext>
            <search:metrics>
              <search:query-resolution-time>PT0.013339S</search:query-resolution-time>
              <search:facet-resolution-time>PT0.004632S</search:facet-resolution-time>
              <search:snippet-resolution-time>PT0.021303S</search:snippet-resolution-time>
              <search:metadata-resolution-time>PT0.007252S</search:metadata-resolution-time>
              <search:total-time>PT0.370925S</search:total-time>
            </search:metrics>
          </search:response>
        ';

        return $result;
    }

    public static function getMetadata()
    {
        $result = '
          <metadata xmlns="http://marklogic.com/rest-api">
            <collections>
              <collection>coll1</collection>
              <collection>coll2</collection>
            </collections>
            <permissions>
              <permission>
                <role-name>myRole</role-name>
                <capability>myCap</capability>
              </permission>
            </permissions>
            <prop:properties xmlns:prop="http://marklogic.com/xdmp/property">
              <propKey xmlns="">propVal</propKey>
            </prop:properties>
            <quality>1</quality>
          </metadata>
        ';

        return $result;
    }

    public static function getCurlExec()
    {
        $result = '
          <rapi:rest-api xmlns:rapi="http://marklogic.com/rest-api">
            <rapi:name>test-mlphp-rest-api</rapi:name>
            <rapi:group>Default</rapi:group>
            <rapi:database>mlphp-test</rapi:database>
            <rapi:modules-database>mlphp-test-modules</rapi:modules-database>
            <rapi:port>8234</rapi:port>
            <rapi:error-format>json</rapi:error-format>
            <rapi:xdbc-enabled>true</rapi:xdbc-enabled>
          </rapi:rest-api>
        ';

        return $result;
    }

    public static function getCurlInfo()
    {
        $result = array (
          'url' => 'http://127.0.0.1:8002/v1/rest-apis/test-mlphp-rest-api',
          'content_type' => 'application/xml; charset=UTF-8',
          'http_code' => '200',
          'header_size' => '430',
          'request_size' => '459',
          'filetime' => '-1',
          'ssl_verify_result' => '0',
          'redirect_count' => '1',
          'total_time' => '1.063445',
          'namelookup_time' => '2.6E-5',
          'connect_time' => '2.6E-5',
          'pretransfer_time' => '0.000374',
          'size_upload' => '0',
          'size_download' => '390',
          'speed_download' => '366',
          'speed_upload' => '0',
          'download_content_length' => '390',
          'upload_content_length' => '-1',
          'starttransfer_time' => '1.060233',
          'redirect_time' => '0.003167',
          'certinfo' => array(),
          'primary_ip' => '127.0.0.1',
          'primary_port' => '8002',
          'local_ip' => '127.0.0.1',
          'local_port' => '55845',
          'redirect_url' => '',
          'request_header' => 'GET /v1/rest-apis/test-mlphp-rest-api HTTP/1.1
Authorization: Digest username="admin", realm="public", nonce="2e581af17ff67ab498f3e0e7f9a9f45b", uri="/v1/rest-apis/test-mlphp-rest-api", cnonce="YmE5MWZkNDQzOGM2OWNjYmY1MTg2MzM1MDZjZmZjNjI=", nc=00000001, qop=auth, response="e702918e7e630cc824bbc330cbb3e4f5", opaque="d3e0cdb1f14a80c4"
Host: 127.0.0.1:8002
Accept: */*'
        );

        return $result;
    }



    public static function getCurlExecErrorJSON()
    {
        // Using 404 response from documents request, unknown uri, uri = 'foo'
        $result = '{"errorResponse":{"statusCode":404, "status":"Not Found", "messageCode":"RESTAPI-NODOCUMENT", "message":"RESTAPI-NODOCUMENT: (err:FOER0000) Resource or document does not exist:  category: content message: foo"}}';

        return $result;
    }

    public static function getCurlExecErrorXML()
    {
        // Using 401 response from bad auth credentials
        $result = '<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>401 Unauthorized</title>
    <meta name="robots" content="noindex,nofollow"/>
  </head>
  <body>
    <h1>401 Unauthorized</h1>
  </body>
</html>';

        return $result;
    }

    public static function getCurlExecErrorText()
    {
        $result = 'Unstructured error message';
        return $result;
    }
}
