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
class RESTResponseTest extends TestBase
{

    function testRESTResponse()
    {
        parent::$logger->debug('testRESTResponse');

        $curl_exec = TestData::getCurlExec();
        $curl_getinfo = TestData::getCurlInfo();

        $resp = new MLPHP\RESTResponse();
        $resp->setBody($curl_exec);
        $resp->setInfo($curl_getinfo);

        $this->assertEquals($resp->getUrl(), $curl_getinfo['url']);
        $this->assertEquals($resp->getContentType(), $curl_getinfo['content_type']);
        $this->assertEquals($resp->getHttpCode(), $curl_getinfo['http_code']);
        $this->assertEquals($resp->getRedirectUrl(), $curl_getinfo['redirect_url']);
    }

    function testErrorJSON()
    {
        parent::$logger->debug('testErrorJSON');

        $curl_exec = TestData::getCurlExecErrorJSON();

        $resp = new MLPHP\RESTResponse();
        $resp->setBody($curl_exec);

        $this->assertEquals($resp->getBodyType(), 'json');
        $this->assertEquals(substr($resp->getErrorMessage(), 0, 9), 'Error 404');
    }

    function testErrorXML()
    {
        parent::$logger->debug('testErrorXML');

        $curl_exec = TestData::getCurlExecErrorXML();

        $resp = new MLPHP\RESTResponse();
        $resp->setBody($curl_exec);

        $this->assertEquals($resp->getBodyType(), 'xml');
        $this->assertEquals(substr($resp->getErrorMessage(), 0, 6), 'Error:');
    }

    function testErrorText()
    {
        parent::$logger->debug('testErrorText');

        $curl_exec = TestData::getCurlExecErrorText();

        $resp = new MLPHP\RESTResponse();
        $resp->setBody($curl_exec);

        $this->assertEquals($resp->getBodyType(), 'other');
        $this->assertEquals(substr($resp->getErrorMessage(), 0, 6), 'Error:');
    }
}

