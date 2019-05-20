<?php

/**
 * PHP version 5.6.
 *
 * @package Gengo
 */

namespace Gengo;

/**
 * Gengo configuration class.
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
 *
 * @untranslatable json
 */
class Config
{
    /**
     * Configuration options.
     *
     * @var array
     */
    private static $_settings = array(
                     'baseurl' => 'https://api.sandbox.gengo.com/',
                     'format' => 'json',
                     'timeout' => 120,
                     'api_key' => false,
                     'private_key' => false,
                     'job_id' => false,
                     'rev_id' => false,
                    );

    /**
     * Get configuration option.
     *
     * @param string $name Option name
     *
     * @return mixed Configuration option
     *
     * @internal
     */
    public static function get($name)
    {
        return (array_key_exists($name, self::$_settings) === true) ? self::$_settings[$name] : false;
    } //end get()

    /**
     * Switch over to live API.
     *
     *
     * @api
     */
    public static function useProduction()
    {
        self::$_settings['baseurl'] = 'https://api.gengo.com/';
    } //end useProduction()

    /**
     * Set HTTP response format.
     *
     * @param string $format Set the requested response format (xml or json)
     *
     * @throws Exception Response format is invalid
     *
     * @exceptioncode GENGO_EXCEPTION_INVALID_RESPONSE_FORMAT
     *
     * @untranslatable xml
     * @untranslatable json
     *
     * @api
     */
    public static function setResponseFormat($format)
    {
        $format = strtolower($format);
        $valid = array(
               'xml',
               'json',
              );
        if (in_array($format, $valid) === false) {
            throw new \Exception(
                _('Invalid response format').' '.$format.', '._('accepted formats are: xml or json'),
                GENGO_EXCEPTION_INVALID_RESPONSE_FORMAT
            );
        }

        self::$_settings['format'] = $format;
    } //end setResponseFormat()

    /**
     * Set API key.
     *
     * @param string $apikey Gengo API key
     *
     * @throws Exception Invalid API key
     *
     * @exceptioncode GENGO_EXCEPTION_INVALID_API_KEY
     *
     * @api
     */
    public static function setAPIkey($apikey)
    {
        if (is_string($apikey) === false) {
            throw new \Exception(_('Invalid API key'), GENGO_EXCEPTION_INVALID_API_KEY);
        }

        self::$_settings['api_key'] = $apikey;
    } //end setAPIkey()

    /**
     * Set private key.
     *
     * @param string $privatekey Gengo private key
     *
     * @throws Exception Invalid private key
     *
     * @exceptioncode GENGO_EXCEPTION_INVALID_PRIVATE_KEY
     *
     * @api
     */
    public static function setPrivateKey($privatekey)
    {
        if (is_string($privatekey) === false) {
            throw new \Exception(_('Invalid private key'), GENGO_EXCEPTION_INVALID_PRIVATE_KEY);
        }

        self::$_settings['private_key'] = $privatekey;
    } //end setPrivateKey()

    /**
     * Set job ID.
     *
     * @param int $id Job ID
     *
     * @throws Exception Invalid job ID
     *
     * @exceptioncode GENGO_EXCEPTION_INVALID_JOB_ID
     *
     * @api
     */
    public static function setJobID($id)
    {
        if (is_int($id) === false) {
            throw new \Exception(_('Invalid job ID'), GENGO_EXCEPTION_INVALID_JOB_ID);
        }

        self::$_settings['job_id'] = $id;
    } //end setJobID()

    /**
     * Set revision ID.
     *
     * @param int $id Revision ID
     *
     * @throws Exception Invalid revision ID
     *
     * @exceptioncode GENGO_EXCEPTION_INVALID_REVISION_ID
     *
     * @api
     */
    public static function setRevisionID($id)
    {
        if (is_int($id) === false) {
            throw new \Exception(_('Invalid revision ID'), GENGO_EXCEPTION_INVALID_REVISION_ID);
        }

        self::$_settings['revision_id'] = $id;
    } //end setRevisionID()

    /**
     * Set base URL.
     *
     * @param string $url URL to be used
     *
     * @api
     */
    public static function setBaseUrl($url)
    {
        self::$_settings['baseurl'] = rtrim($url, '/').'/';
    } //end setBaseUrl()
} //end class
