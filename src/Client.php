<?php

/**
 * PHP version 5.6.
 *
 * @package Gengo
 */

namespace Gengo;

use GuzzleHttp\Client as HTTPClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;

/**
 * HTTP client class.
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
 * @author    Vladimir Bashkirtsev <vladimir@bashkirtsev.com>
 * @copyright 2009-2016 Gengo, Inc. (http://gengo.com)
 * @license   http://gengo.com/services/api/dev-docs/gengo-code-license New BSD License
 *
 * @version   GIT: $Id:$
 *
 * @link      https://github.com/gengo/gengo-php
 */
class Client
{
    /**
     * HTTP client.
     *
     * @var \GuzzleHttp\Client
     */
    protected static $http = null;

    /**
     * HTTP response.
     *
     * @var ResponseInterface
     */
    protected static $response = null;

    /**
     * Get request.
     *
     * @param string $url    URL
     * @param array  $params HTTP request parameters
     *
     * @return string HTTP response
     *
     * @untranslatable baseurl
     * @untranslatable GET
     *
     * @internal
     */
    public static function get($url, array $params = array())
    {
        return self::_request($url, 'GET', $params);
    } //end get()

    /**
     * Post request.
     *
     * @param string $url    URL
     * @param array  $params HTTP request parameters
     * @param array  $files  Files to upload
     *
     * @return string HTTP response
     *
     * @untranslatable baseurl
     * @untranslatable POST
     *
     * @internal
     */
    public static function post($url, array $params = array(), array $files = array())
    {
        return self::_request($url, 'POST', $params, $files);
    } //end post()

    /**
     * Put request.
     *
     * @param string $url    URL
     * @param array  $params HTTP request parameters
     *
     * @return string HTTP response
     *
     * @untranslatable baseurl
     * @untranslatable PUT
     *
     * @internal
     */
    public static function put($url, array $params = array())
    {
        return self::_request($url, 'PUT', $params);
    } //end put()

    /**
     * Delete request.
     *
     * @param string $url    URL
     * @param array  $params HTTP request parameters
     *
     * @return string HTTP response
     *
     * @untranslatable baseurl
     * @untranslatable DELETE
     *
     * @internal
     */
    public static function delete($url, array $params = array())
    {
        return self::_request($url, 'DELETE', $params);
    } //end delete()

    /**
     * Return last HTTP response code.
     *
     * @return mixed Last HTTP response code
     *
     * @internal
     */
    public static function getCode()
    {
        return self::$response instanceof ResponseInterface
            ? self::$response->getStatusCode()
            : null;
    } //end getCode()

    /**
     * Return last HTTP response headers.
     *
     * @return mixed Last HTTP response headers
     *
     * @internal
     */
    public static function getHeaders()
    {
        return self::$response instanceof ResponseInterface
            ? self::$response->getHeaders()
            : null;
    } //end getHeaders()

    /**
     * Send HTTP request.
     *
     * @param string $url    URL
     * @param string $method HTTP method to use
     * @param array  $params HTTP request parameters
     * @param array  $files  Files to upload
     *
     * @return string HTTP response
     *
     * @untranslatable application/
     * @untranslatable format
     * @untranslatable U
     * @untranslatable api_key
     * @untranslatable private_key
     * @untranslatable Content-Type
     * @untranslatable Gengo PHP Library; Version 3.0.3; http://gengo.com/
     * @untranslatable timeout
     * @untranslatable application/x-www-form-urlencoded
     *
     * @internal
     */
    private static function _request($url, $method, array $params, array $files = [])
    {
        $params['ts'] = gmdate('U');
        $params['api_key'] = Config::get('api_key');
        $params['api_sig'] = self::sign($params['ts'], Config::get('private_key'));

        if (self::$http instanceof HTTPClient === false) {
            self::$http = new HTTPClient([
                'defaults' => [
                    'timeout' => Config::get('timeout'),
                    'allow_redirects' => ['max' => 1],
                ],
            ]);
        }

        $url = Config::get('baseurl').$url;
        $options = [
            'query' => $params,
            'headers' => [
                'Accept' => 'application/'.Config::get('format'),
                'User-Agent' => 'Gengo PHP Library; Version 3.0.3; http://gengo.com/',
            ],
        ];

        try {
            switch ($method) {
                case 'DELETE':
                    self::$response = self::$http->delete($url, $options);
                    break;
                case 'GET':
                    self::$response = self::$http->get($url, $options);
                    break;
                case 'POST':
                    self::$response = self::$http->post($url, self::getPostOptions($files, $params));
                    break;
                case 'PUT':
                    $options[RequestOptions::FORM_PARAMS] = $params;
                    self::$response = self::$http->put($url, $options);
                    break;
            } //end switch
        } catch (ClientException $e) {
            self::$response = $e->getResponse();
        }

        return self::$response->getBody()->getContents();
    } //end _request()

    /**
     * Create params for POST request.
     *
     * @param array $files
     * @param array $params
     *
     * @return array
     */
    protected static function getPostOptions(array $files, array $params)
    {
        if (!count($files)) {
            return [
                RequestOptions::FORM_PARAMS => $params,
            ];
        }

        $options = [];

        foreach ($files as $key => $file) {
            $options[RequestOptions::MULTIPART][] = [
                'name' => $key,
                'contents' => fopen($file, 'r'),
            ];
        }

        foreach ($params as $paramKey => $paramValue) {
            $options[RequestOptions::MULTIPART][] = [
                'name' => $paramKey,
                'contents' => $paramValue,
            ];
        }

        return $options;
    }

    /**
     * Sign data with private key.
     *
     * @param string $data       The data to sign
     * @param string $privatekey The key used to sign the data
     *
     * @return string Base64 signature of the data
     *
     * @untranslatable sha1
     *
     * @internal
     */
    protected static function sign($data, $privatekey)
    {
        return hash_hmac('sha1', $data, $privatekey);
    } //end sign()
} //end class
