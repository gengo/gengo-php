<?php

/**
 * PHP version 5.6
 *
 * @package Gengo
 */

namespace Gengo;

use \Logics\Foundation\HTTP\HTTPclient;

/**
 * HTTP client class
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
 * @version   GIT: $Id:$
 * @link      https://github.com/gengo/gengo-php
 */

class Client
    {

	/**
	 * HTTP client
	 *
	 * @var \Logics\Foundation\Web\HTTPclient
	 */
	protected static $http = null;

	/**
	 * Get request
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
		return self::_request(Config::get("baseurl") . $url, "GET", $params);
	    } //end get()


	/**
	 * Post request
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
		return self::_request(Config::get("baseurl") . $url, "POST", $params, $files);
	    } //end post()


	/**
	 * Put request
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
		return self::_request(Config::get("baseurl") . $url, "PUT", $params);
	    } //end put()


	/**
	 * Delete request
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
		return self::_request(Config::get("baseurl") . $url, "DELETE", $params);
	    } //end delete()


	/**
	 * Return last HTTP response code
	 *
	 * @return mixed Last HTTP response code
	 *
	 * @internal
	 */

	public static function getCode()
	    {
		return (self::$http instanceof HTTPclient === true) ? self::$http->lastcode() : null;
	    } //end getCode()


	/**
	 * Return last HTTP response headers
	 *
	 * @return mixed Last HTTP response headers
	 *
	 * @internal
	 */

	public static function getHeaders()
	    {
		return (self::$http instanceof HTTPclient === true) ? self::$http->lastheaders() : null;
	    } //end getHeaders()


	/**
	 * Send HTTP request
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
	 * @untranslatable Gengo PHP Library; Version 3.0.0; http://gengo.com/
	 * @untranslatable timeout
	 * @untranslatable application/x-www-form-urlencoded
	 *
	 * @internal
	 */

	private static function _request($url, $method, array $params, array $files = array())
	    {
		$headers = array("Accept" => "application/" . Config::get("format"));

		$params["ts"]      = gmdate("U");
		$params["api_key"] = Config::get("api_key");
		$params["api_sig"] = self::sign($params["ts"], Config::get("private_key"));

		foreach ($files as $key => $file)
		    {
			$files[$key] = array("name" => $file);
		    }

		if (self::$http instanceof HTTPclient === false)
		    {
			$config = array(
				   "maxredirects" => 1,
				   "useragent"    => "Gengo PHP Library; Version 3.0.0; http://gengo.com/",
				   "timeout"      => Config::get("timeout"),
				   "keepalive"    => false,
				  );

			self::$http = new HTTPclient("", array(), array(), $config);
		    }

		$response = "";
		switch ($method)
		    {
			case "DELETE":
				$headers = array("Content-Type" => "application/x-www-form-urlencoded");
				self::$http->setRequest($url, $params, $headers);
				$response = self::$http->delete();
			    break;
			case "GET":
				self::$http->setRequest($url, $params, $headers);
				$response = self::$http->get();
			    break;
			case "POST":
				self::$http->setRequest($url, $params, $headers, $files);
				$response = self::$http->post();
			    break;
			case "PUT":
				$headers = array("Content-Type" => "application/x-www-form-urlencoded");
				self::$http->setRequest($url, $params, $headers);
				$response = self::$http->put();
			    break;
		    } //end switch

		return $response;
	    } //end _request()


	/**
	 * Sign data with private key
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
		return hash_hmac("sha1", $data, $privatekey);
	    } //end sign()


    } //end class

?>
