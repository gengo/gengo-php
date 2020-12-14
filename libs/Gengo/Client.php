<?php
/**
 * Gengo API Client
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that came
 * with this package in the file LICENSE.txt. It is also available
 * through the world-wide-web at this URL:
 * http://gengo.com/services/api/dev-docs/gengo-code-license
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@gengo.com so we can send you a copy immediately.
 *
 * @category   Gengo
 * @package    API Client Library
 * @copyright  Copyright (c) 2009-2012 Gengo, Inc. (http://gengo.com)
 * @license    http://gengo.com/services/api/dev-docs/gengo-code-license   New BSD License
 */

class Gengo_Client
{
    protected static $instance = null;
    protected $config;
    protected $client;

    protected function __construct()
    {
        $this->config = Gengo_Config::getInstance();

        // default user agent string
        $user_agent = 'Gengo PHP Library; Version 2.1.6; http://gengo.com/';
        $user_agent = $this->config->get('useragent', $user_agent);

        $config = array('maxredirects' => 1,
                        'useragent' => $user_agent,
                        'timeout' => $this->config->get('timeout', 120),
                        'keepalive' => false);
        $this->client = new Zend_Http_Client(null, $config);
    }

    public function get($url, $format = null, array $params = null)
    {
        $this->client->resetParameters(true);
        return $this->request($url, Zend_Http_Client::GET, $format, $params);
    }

    public function post($url, $format = null, array $params = null)
    {
        $this->client->resetParameters(true);
        return $this->request($url, Zend_Http_Client::POST, $format, $params);
    }

    public function put($url, $format = null, array $params = null)
    {
        $this->client->resetParameters(true);
        return $this->request($url, Zend_Http_Client::PUT, $format, $params);
    }

    public function delete($url, $format = null, array $params = null)
    {
        $this->client->resetParameters(true);
        return $this->request($url, Zend_Http_Client::DELETE, $format, $params);
    }

    public function upload($url, array $filepath, $format = null, array $params = null)
    {
        $this->client->resetParameters(true);
        foreach ($filepath as $file_key => $fp)
        {
            $this->client->setFileUpload($fp, $file_key);
        }
        return $this->request($url, Zend_Http_Client::POST, $format, $params);
    }

    protected function request($url, $method, $format = null, $params = null)
    {
        $method = strtoupper($method);
        $methods = array('GET','POST','PUT','DELETE');
        if (! in_array($method, $methods))
        {
            throw new Gengo_Exception("HTTP method: {$method} not supported");
        }
        if (! is_null($format) && is_string($format))
        {
            $format = strtolower($format);
            $formats = array('json', 'xml');
            if (! in_array($format, $formats))
            {
                throw new Gengo_Exception("Invalid response format: {$format}, accepted formats are: json or xml.");
            }
            switch ($format)
            {
                case 'xml':
                    $this->client->setHeaders('Accept', 'application/xml');
                    break;
                case 'json':
                    $this->client->setHeaders('Accept', 'application/json');
                    break;
            }
        }
        if (! is_null($params))
        {
            switch ($method)
            {
                case 'DELETE':
                case 'GET':
                    $this->client->setParameterGet($params);
                    break;
                case 'POST':
                    if (isset($params['file_path']))
                    {
                        $this->client->setFileUpload($params['file_path'], 'file_path');
                        unset($params['file_path']);
                    }
                    $this->client->setParameterPost($params);
                    break;
                case 'PUT':
                    if (is_array($params))
                    {
                        if (isset($params['file_path']))
                        {
                            $this->client->setFileUpload($params['file_path'], 'file_path');
                            unset($params['file_path']);
                        }
                        if (count($params) > 0)
                        {
                            $this->client->setHeaders('Content-Type', 'application/x-www-form-urlencoded');
                            $params = http_build_query($params);
                        }
                        else {
                            $params = NULL;
                        }

                    }
                    $this->client->setRawData($params, Zend_Http_Client::ENC_URLENCODED);
                    break;
            }
        }
        try {
            $this->client->setUri($url);
            return $this->client->request($method);
        }
        catch (Exception $ex)
        {
            throw new Gengo_Exception($ex->getMessage(), $ex->getCode());
        }
    }

    public static function getInstance()
    {
        if (null === self::$instance)
        {
            self::$instance = new self();
        }
        return self::$instance;
    }
}
