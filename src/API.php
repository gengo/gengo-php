<?php

/**
 * PHP version 5.6
 *
 * @package Gengo
 */

namespace Gengo;

use \Exception;

/**
 * Abstract API class
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

abstract class API
    {

	/**
	 * HTTP response
	 *
	 * @var string
	 */
	protected $response;

	/**
	 * HTTP response code
	 *
	 * @var int
	 */
	protected $code;

	/**
	 * HTTP response headers
	 *
	 * @var array
	 */
	protected $headers;

	/**
	 * Initialize API client
	 *
	 * @return void
	 *
	 * @throws Exception No keys
	 *
	 * @exceptioncode GENGO_EXCEPTION_NO_API_KEY
	 * @exceptioncode GENGO_EXCEPTION_NO_PRIVATE_KEY
	 *
	 * @untranslatable api_key
	 * @untranslatable private_key
	 *
	 * @api
	 */

	public function __construct()
	    {
		if (Config::get("api_key") === false)
		    {
			throw new Exception(_("No API key is set"), GENGO_EXCEPTION_NO_API_KEY);
		    }

		if (Config::get("private_key") === false)
		    {
			throw new Exception(_("No private key is set"), GENGO_EXCEPTION_NO_PRIVATE_KEY);
		    }

		$this->response = null;
	    } //end __construct()


	/**
	 * Get HTTP response body
	 *
	 * @return string Decoded response body
	 *
	 * @api
	 */

	public function getResponseBody()
	    {
		$this->_checkResponse();
		return $this->response;
	    } //end getResponseBody()


	/**
	 * Get HTTP response status code
	 *
	 * @return int The HTTP response status code
	 *
	 * @api
	 */

	public function getResponseCode()
	    {
		$this->_checkResponse();
		return $this->code;
	    } //end getResponseCode()


	/**
	 * Get response headers
	 *
	 * @return mixed Response headers
	 *
	 * @api
	 */

	public function getResponseHeaders()
	    {
		$this->_checkResponse();
		return $this->headers;
	    } //end getResponseHeaders()


	/**
	 * Store response from Gengo web service
	 *
	 * @param string $response Web service response
	 *
	 * @return string Web service response
	 */

	protected function storeResponse($response)
	    {
		$this->response = $response;
		$this->code     = Client::getCode();
		$this->headers  = Client::getHeaders();
		return $response;
	    } //end storeResponse()


	/**
	 * Check HTTP response
	 *
	 * @return void
	 *
	 * @throws Exception No valid response available yet
	 *
	 * @exceptioncode GENGO_EXCEPTION_NO_RESPONSE_AVAILABLE_YET
	 *
	 * @internal
	 */

	private function _checkResponse()
	    {
		if ($this->response === null)
		    {
			throw new Exception(
			    _("A valid response is not yet available, please make a request first"),
			    GENGO_EXCEPTION_NO_RESPONSE_AVAILABLE_YET
			);
		    }
	    } //end _checkResponse()


	/**
	 * Check that ID is valid. If not try to get it from configuration options. Otherwise fail.
	 *
	 * @param int $id   ID
	 * @param int $name Configuration option name
	 *
	 * @return int ID
	 *
	 * @throws Exception ID is not set
	 *
	 * @exceptioncode GENGO_EXCEPTION_ID_IS_NOT_SET
	 *
	 * @untranslatable ID
	 *
	 * @internal
	 */

	protected function getID($id, $name)
	    {
		if (is_numeric($id) === false)
		    {
			$id = Config::get($name);
			if (is_numeric($id) === false)
			    {
				throw new Exception("ID " . $name . " " . _("is not set"), GENGO_EXCEPTION_ID_IS_NOT_SET);
			    }
		    }

		return (int) $id;
	    } //end getID()


    } //end class

?>
