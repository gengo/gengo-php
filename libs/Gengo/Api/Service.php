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

class Gengo_Api_Service extends Gengo_Api
{
    /**
     * @param string $api_key the public API key.
     * @param string $private_key the private API key.
     */
    public function __construct($api_key = null, $private_key = null)
    {
        parent::__construct($api_key, $private_key);
    }

    /**
     * translate/service/languages (GET)
     *
     * Returns a list of supported languages and their language codes.
     *
     * @param string $format The OPTIONAL response format: xml or json (default).
     * @param array|string $params (DEPRECATED) If passed should contain all the
     * necessary parameters for the request including the api_key and
     * api_sig
     */
    public function getLanguages($format = null, $params = null)
    {
        $this->setParamsNotId($format, $params);
        $baseurl = $this->config->get('baseurl', null, true);
        $baseurl .= "v2/translate/service/languages/";
        $this->response = $this->client->get($baseurl, $format, $params);
    }

    /**
     * translate/service/language_pairs (GET)
     *
     * Returns supported translation language pairs, tiers, and credit
     * prices.
     *
     * @param string $lc_src (OPTIONAL) Submitting this will filter the response
     * to only relevant language pairs.
     * @param string $format The OPTIONAL response format: xml or json (default).
     * @param array|string $params (DEPRECATED) If passed should contain all the
     * necessary parameters for the request including the api_key and
     * api_sig
     */
    public function getLanguagePairs($lc_src = null, $format = null, $params = null)
    {
        $this->setParamsNotId($format, $params);
        $baseurl = $this->config->get('baseurl', null, true);
        $baseurl .= "v2/translate/service/language_pairs/";
        if (!is_null($lc_src))
        {
            $params['lc_src'] = $lc_src;
        }
        $this->response = $this->client->get($baseurl, $format, $params);
    }



    /**
     * translate/service/quote (POST)
     *
     * Submits a job or group of jobs to quote.
     *
     * @param array $jobs An array of payloads (a payload being itself an array of string)
     * of jobs to create.
     * @param array $filepath Either a key/value pair as in file_key => path/to/file or file_key => array('filename' => name, 'data' => data)
     */
    public function quote(array $jobs, array $filepath = null)
    {
        // parameters check
        if (! empty($filepath))
        {
            foreach ($jobs as &$job)
            {
                if ( $job['type'] == 'file' )
                {
                    // make sure job has a file_key parameter
                    if (! isset($job['file_key']))
                    {
                        throw new Gengo_Exception(sprintf('Job %s is missing file_key parameter', print_r($job, true)));
                    }
                    // make sure the file_key parameter is a valid alphanumeric parameter
                    if (! preg_match('/^[a-z0-9_-]+$/i', $job['file_key']))
                    {
                        throw new Gengo_Exception(sprintf('"%s" is not a valid file_key parameter', $job['file_key']));
                    }
                    // make sure file_key parameter is a key in filepath array
                    if (! array_key_exists($job['file_key'], $filepath))
                    {
                        throw new Gengo_Exception(sprintf('file_key: "%s" is missing in filepath array', $job['file_key']));
                    }
                    // make sure we have valid file paths and or data
                    if (is_array($filepath[$job['file_key']]))
                    {
                        // checking we have a valid file name
                        if (! isset($filepath[$job['file_key']]['filename']) ||
                            ! is_string($filepath[$job['file_key']]['filename']) ||
                            empty($filepath[$job['file_key']]['filename']))
                        {
                            throw new GengoException(sprintf('file_key: %s is missing a valid file name', $job['file_key']));
                        }
                        // checking we have valid data
                        if (! isset($filepath[$job['file_key']]['data']) ||
                            empty($filepath[$job['file_key']]['data']))
                        {
                            throw new GengoException(sprintf('file_key: %s is missing valid data', $job['file_key']));
                        }
                    }
                    else if (! is_file($filepath[$job['file_key']]))
                    {
                        throw new Gengo_Exception(sprintf('Could not find file: %s', $filepath[$job['file_key']]));
                    }
                }
            }
        }
        // end parameters check
        $data = array('jobs' => $jobs);

        // create the query
        $ts = gmdate('U');
        $params = array('api_key' => $this->config->get('api_key', null, true),
                        '_method' => 'post',
                        'ts'      => $ts,
                        'data'    => json_encode($data),
                        'api_sig' => Gengo_Crypto::sign($ts, $this->config->get('private_key', null, true)),
        );

        $format = $this->config->get('format', null, true);
        $baseurl = $this->config->get('baseurl', null, true);
        $baseurl .= 'v2/translate/service/quote';
        if (! empty($filepath))
        {
            foreach ($filepath as $file_key => $fp)
            {
                if ( is_null( $fp ) )
                {
                    unset($filepath[$file_key]);
                }
            }
            $this->response = $this->client->upload($baseurl, $filepath, $format, $params);
        }
        else {
            $this->response = $this->client->post($baseurl, $format, $params);
        }
    }
}
