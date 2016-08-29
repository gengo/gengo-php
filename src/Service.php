<?php

/**
 * PHP version 5.6
 *
 * @package Gengo
 */

namespace Gengo;

use \Exception;

/**
 * Service API client class
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

class Service extends API
    {

	/**
	 * Returns supported translation language pairs, tiers, and credit prices.
	 *
	 * Calls translate/service/language_pairs (GET)
	 *
	 * @param string $lcsrc Source language code. Submitting this will filter the response to only relevant language pairs.
	 *
	 * @return string Gengo response
	 *
	 * @untranslatable v2/translate/service/language_pairs
	 *
	 * @api
	 */

	public function getLanguagePairs($lcsrc = null)
	    {
		$data   = ($lcsrc !== null) ? array("lc_src" => $lcsrc) : array();
		$params = (count($data) > 0) ? array("data" => json_encode($data)) : array();

		return $this->storeResponse(Client::get("v2/translate/service/language_pairs", $params));
	    } //end getLanguagePairs()


	/**
	 * Returns a list of supported languages and their language codes.
	 *
	 * Calls translate/service/languages (GET)
	 *
	 * @return string Gengo response
	 *
	 * @untranslatable v2/translate/service/languages
	 *
	 * @api
	 */

	public function getLanguages()
	    {
		return $this->storeResponse(Client::get("v2/translate/service/languages"));
	    } //end getLanguages()


	/**
	 * Submits a job or group of jobs to quote.
	 *
	 * Calls translate/service/quote (POST)
	 *
	 * @param array $jobs     The parameter is an array of jobs. If you set custom keys, they will be
	 *                        mirrored in the response. Otherwise, default numerical keying applies. This
	 *                        helps to keep track of which job corresponds to which quote.
	 * @param array $filepath A key/value pair as in file_key => path/to/file
	 *
	 * @return string Gengo response
	 *
	 * @throws Exception Incomplete or invalid calling parameters
	 *
	 * @exceptioncode GENGO_EXCEPTION_JOB_MISSING_FILE_KEY
	 * @exceptioncode GENGO_EXCEPTION_IS_NOT_VALID_FILE_KEY
	 * @exceptioncode GENGO_EXCEPTION_FILE_KEY_MISSING
	 * @exceptioncode GENGO_EXCEPTION_COULD_NOT_FIND_FILE
	 *
	 * @untranslatable file
	 * @untranslatable file_key: \"
	 * @untranslatable post
	 * @untranslatable v2/translate/service/quote
	 *
	 * @api
	 */

	public function quote(array $jobs, array $filepath = array())
	    {
		foreach ($jobs as $job)
		    {
			if ($job["type"] === "file")
			    {
				if (isset($job["file_key"]) === false)
				    {
					throw new Exception(
					    _("Job") . " " . var_export($job, true) . " " . _("is missing file_key parameter"),
					    GENGO_EXCEPTION_JOB_MISSING_FILE_KEY
					);
				    }

				if (preg_match("/^[a-z0-9_-]+$/i", $job["file_key"]) === 0)
				    {
					throw new Exception(
					    "\"" . $job["file_key"] . "\" " . _("is not a valid file_key parameter"),
					    GENGO_EXCEPTION_IS_NOT_VALID_FILE_KEY
					);
				    }

				if (array_key_exists($job["file_key"], $filepath) === false)
				    {
					throw new Exception(
					    "file_key: \"" . $job["file_key"] . "\" " . _("is missing in filepath array"),
					    GENGO_EXCEPTION_FILE_KEY_MISSING
					);
				    }

				if (is_file($filepath[$job["file_key"]]) === false)
				    {
					throw new Exception(
					    _("Could not find file: " . $filepath[$job["file_key"]]),
					    GENGO_EXCEPTION_COULD_NOT_FIND_FILE
					);
				    } //end if
			    } //end if
		    } //end foreach

		$data = array("jobs" => $jobs);

		$params = array(
			   "_method" => "post",
			   "data"    => json_encode($data),
			  );

		return $this->storeResponse(Client::post("v2/translate/service/quote", $params, $filepath));
	    } //end quote()


    } //end class

?>
