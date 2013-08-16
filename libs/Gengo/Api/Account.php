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

class Gengo_Api_Account extends Gengo_Api
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
     * account/balance (GET)
     * Retrieves account balance in credits
     *
     * @param string $format The OPTIONAL response format: xml or json.
     * @param array|string $params (DEPRECATED) If passed should contain all the
     * necessary parameters for the request including the api_key and
     * api_sig
     */
    public function getBalance($format = null, $params = null)
    {
        $this->setParamsNotId($format, $params);
        $baseurl = $this->config->get('baseurl', null, true);
        $baseurl .= "account/balance";
        $this->response = $this->client->get($baseurl, $format, $params);
    }

    /**
     * account/stats (GET)
     * Retrieves account stats, such as orders made.
     *
     * @param string $format The OPTIONAL response format: xml or json.
     * @param array|string $params (DEPRECATED) If passed should contain all the
     * necessary parameters for the request including the api_key and
     * api_sig
     */
    public function getStats($format = null, $params = null)
    {
        $this->setParamsNotId($format, $params);
        $baseurl = $this->config->get('baseurl', null, true);
        $baseurl .= "account/stats";
        $this->response = $this->client->get($baseurl, $format, $params);
    }

    /**
     * account/preferred_translators (GET)
     * Retrieves preferred translators as array by langs and tier.
     *
     * @param string $format The OPTIONAL response format: xml or json.
     * @param array|string $params (DEPRECATED) If passed should contain all the
     * necessary parameters for the request including the api_key and
     * api_sig
     */
    public function getPreferredTranslators($format = null, $params = null)
    {
        $this->setParamsNotId($format, $params);
        $baseurl = $this->config->get('baseurl', null, true);
        $baseurl .= "account/preferred_translators";
        $this->response = $this->client->get($baseurl, $format, $params);
    }
}
