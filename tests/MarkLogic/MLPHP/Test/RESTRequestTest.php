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

use MarkLogic\MLPHP;

/**
 * @package MLPHP\Test
 * @author Mike Wooldridge <mike.wooldridge@marklogic.com>
 */
class RESTRequestTest extends TestBase
{
    function testRESTRequest()
    {
        parent::$logger->debug('testRESTRequest');
        $req = new MLPHP\RESTRequest(
            'PUT',
            'documents',
            array('uri' => '/file.txt'),
            'Text content',
            array('Content-type' => 'text/text')
        );

        $this->assertEquals($req->getVerb(), 'PUT');
        $this->assertEquals($req->getResource(), 'documents');
        $this->assertEquals($req->getParams()['uri'], '/file.txt');
        $this->assertEquals($req->getBody(), 'Text content');
        $this->assertEquals($req->getHeaders()['Content-type'], 'text/text');
        $this->assertEquals($req->getUrlStr(), 'documents?uri=%2Ffile.txt');
    }

    function testIsWWWFormURLEncodedPost()
    {
        parent::$logger->debug('testIsWWWFormURLEncodedPost');
        $req = new MLPHP\RESTRequest(
            'POST',
            'foo',
            array('uri' => '/bar'),
            '',
            array('Content-type' => 'application/x-www-form-urlencoded')
        );

        $this->assertTrue($req->isWWWFormURLEncodedPost());
        $this->assertEquals($req->getUrlStr(), 'foo');
    }


    function testComplexParams()
    {
        parent::$logger->debug('testComplexParams');
        $req = new MLPHP\RESTRequest(
            'POST',
            'documents',
            array('foo' => ['bar', 'baz']),
            '',
            array('Content-type' => 'text/text')
        );
        $this->assertEquals($req->getUrlStr(), 'documents?foo=bar&foo=baz');
    }

}

