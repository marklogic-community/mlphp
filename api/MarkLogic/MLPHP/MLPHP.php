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
namespace MarkLogic\MLPHP;

use Psr\Log\NullLogger;

/**
 * MLPHP Global State
 *
 * @package MLPHP
 * @author Eric Bloch <eric.bloch@gmail.com>
 * @author Mike Wooldridge <mike.wooldridge@marklogic.com>
 */
class MLPHP
{
    /**
     *  Array of configuration parameters used to create clients and REST APIs.
     *  @var mixed[]
     *  @see MLPHP#__construct
     */
    public $config = array();

    /**
     * Constructor, used to set configuration parameters.
     *
     * @param array config Configuration settings.
     *
     */
    public function __construct($config = array())
    {
        $this->config = array_merge(array(
            'host' => '127.0.0.1',
            'port' => 8003,
            'managePort' => 8002,
            'adminPort' => 8001,
            'api' => 'mlphp-rest-api',
            'db' => 'mlphp-db',
            'username' => 'admin',
            'password' => 'admin',
            'path' => '',
            'managePath' => 'manage',
            'adminPath' => 'admin',
            'version' => 'v1',
            'manageVersion' => 'v2',
            'adminVersion' => 'v1',
            'auth' => 'digest',
            'options' => 'mlphp-options',
            'logger' => new NullLogger()
        ), $config);

    }

    /**
     * Merge the passed in config parameters.  Only affects future clients.
     *
     * @param config
     */
    public function mergeConfig($config)
    {
        $this->config = array_merge($this->config, $config);
    }

    /**
     * Return a REST client based on current configuration.
     *
     * @return RESTClient
     */
    public function getClient()
    {
        return new RESTClient(
            $this->config['host'],
            $this->config['port'],
            $this->config['path'],
            $this->config['version'],
            $this->config['username'],
            $this->config['password'],
            $this->config['auth'],
            $this->config['logger']
        );
    }

    /**
     * Return a REST client to the management API.
     *
     * @return RESTClient
     */
    public function getManageClient()
    {
        return new RESTClient(
            $this->config['host'],
            $this->config['managePort'],
            $this->config['managePath'],
            $this->config['manageVersion'],
            $this->config['username'],
            $this->config['password'],
            $this->config['auth'],
            $this->config['logger']
        );
    }

    /**
     * Return a REST client to the admin API.
     *
     * @return RESTClient
     */
    public function getAdminClient()
    {
        return new RESTClient(
            $this->config['host'],
            $this->config['adminPort'],
            $this->config['adminPath'],
            $this->config['adminVersion'],
            $this->config['username'],
            $this->config['password'],
            $this->config['auth'],
            $this->config['logger']
        );
    }

    /**
     * Create and return a REST API based on current configuration.
     *
     * @return RESTAPI
     */
    public function getAPI()
    {
        return new RESTAPI(
            $this->config['api'],
            $this->config['host'],
            $this->config['db'],
            $this->config['port'],
            $this->config['username'],
            $this->config['password']
        );
    }

    /**
     * Return a Document object.
     *
     * @return Document
     */
    public function getDocument($uri = null)
    {
        return new Document(
            $this->getClient(),
            $uri
        );
    }

    /**
     * Return a Database object.
     *
     * @return Database
     */
    public function getDatabase($name = null)
    {
        $name = $name ? $name : $this->config['db'];
        return new Database(
            $this->getManageClient(),
            $name
        );
    }

    /**
     * Return an Options object.
     *
     * @return Options
     */
    public function getOptions($name = null)
    {
        $name = $name ? $name : $this->config['options'];
        return new Options(
            $this->getClient(),
            $name
        );
    }

    /**
     * Return an assoc array of MarkLogic server config information.
     * Keys: 'version', 'platform', 'edition'
     * @see http://docs.marklogic.com/REST/GET/admin/v1/server-config
     *
     * @return array
     */
    public function getServerConfig()
    {
        $adminClient = $this->getAdminClient();
        $request = new RESTRequest('GET', 'server-config');
        try {
            $response = $adminClient->send($request);
            $dom = new \DOMDocument();
            $dom->loadXML($response->getBody());
            return array(
              'version' => $dom->getElementsByTagName('version')->item(0)->nodeValue,
              'platform' => $dom->getElementsByTagName('platform')->item(0)->nodeValue,
              'edition' => $dom->getElementsByTagName('edition')->item(0)->nodeValue
            );
        } catch(Exception $e) {
            echo 'MLPHP::getServerConfig() failed.' . PHP_EOL;
            echo $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine() . PHP_EOL;
        }
    }

    /**
     * PSR-0 autoloader.
     *
     * Do NOT use if you are using Composer to autoload dependencies.
     *
     * @todo Delete this if we are requiring Composer for MLPHP.
     *
     * @param $className
     */
    public static function autoload($className)
    {
        $thisClass = str_replace(__NAMESPACE__ . '\\', '', __CLASS__);

        $baseDir = __DIR__;

        if (substr($baseDir, -strlen($thisClass)) === $thisClass) {
            $baseDir = substr($baseDir, 0, -strlen($thisClass));
        }

        $className = ltrim($className, '\\');
           // echo "class " . $className;
           // echo '<br/>';
        $fileName  = (dirname($baseDir)) . DIRECTORY_SEPARATOR;
                // echo "file " . $fileName;
                // echo '<br/>';
        if ($lastNsPos = strripos($className, '\\')) {
            $namespace = substr($className, 0, $lastNsPos);
               // echo "ns " . $namespace;
               // echo '<br/>';
            $className = substr($className, $lastNsPos + 1);
               // echo "class " . $className;
               // echo '<br/>';
            $fileName .= str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
               // echo "file " . $fileName;
               // echo '<br/>';
        }
        $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

           // echo "FILE: " . $fileName;

        if (file_exists($fileName)) {
            require $fileName;
        }
    }

    /**
     * Register PSR-0 autoloader.
     *
     * Do NOT use if you are using Composer to
     * autoload dependencies.
     */
    public static function registerAutoloader()
    {
        $n = __NAMESPACE__; // workaround for bug in phpdoctor
        spl_autoload_register($n . '\\MLPHP::autoload');
    }
}
