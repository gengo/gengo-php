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

class Gengo_Api_Glossary extends Gengo_Api
{
    /**
     * @param string $api_key the public API key.
     * @param string $private_key the private API key.
     */
    public function __construct($api_key = null, $private_key = null)
    {
        parent::__construct($api_key, $private_key);
    }

    private function get_query()
    {
        $params = array();
        $params['ts'] = gmdate('U');
        $params['api_key'] = $this->config->get('api_key', null, true);
        $private_key = $this->config->get('private_key', null, true);
        $params['api_sig'] = Gengo_Crypto::sign($params['ts'], $private_key);
        $query = http_build_query($params);
        return $query;
    }

	public function downloadGlossary($glossary_id)
	{
        $params = array();
        $params['ts'] = gmdate('U');
        $params['api_key'] = $this->config->get('api_key', null, true);
        $private_key = $this->config->get('private_key', null, true);
        ksort($params);
        $query = http_build_query($params);
        $params['api_sig'] = Gengo_Crypto::sign($query, $private_key);

        $this->setParamsNotId($format, $params);
        $baseurl = $this->config->get('baseurl', null, true);
        $baseurl .= "v2/translate/glossary/download/{$glossary_id}";
        $this->response = $this->client->get($baseurl, $format, $params);
	}

    /**
     * translate/glossary (GET)
     *
     * Retrieves a list of glossaries
     *
     * @param int|string $page_size Either all or the max number of glossary to return
     * @param string $format The response format, xml or json
     */
    public function getGlossaries($page_size = 'all', $format = null)
    {
        $params = array();
        $params['ts'] = gmdate('U');
        $params['page_size'] = $page_size;
        $params['api_key'] = $this->config->get('api_key', null, true);
        $private_key = $this->config->get('private_key', null, true);
        ksort($params);
        $query = http_build_query($params);
        $params['api_sig'] = Gengo_Crypto::sign($query, $private_key);

        $this->setParamsNotId($format, $params);
        $baseurl = $this->config->get('baseurl', null, true);
        $baseurl .= "v2/translate/glossary";
        $this->response = $this->client->get($baseurl, $format, $params);
    }

    /**
     * translate/glossary (GET)
     *
     * Retrieves a glossary
     *
     * @param int $glossary_id The ID of the glossary to return.
     * @param string $format The response format, xml or json
     */
    public function getGlossary($glossary_id, $format = null)
    {
        $params = array();
        $params['ts'] = gmdate('U');
        $params['api_key'] = $this->config->get('api_key', null, true);
        $private_key = $this->config->get('private_key', null, true);
        ksort($params);
        $query = http_build_query($params);
        $params['api_sig'] = Gengo_Crypto::sign($params['ts'], $private_key);

        //$this->setParamsNotId($format, $params);
        $baseurl = $this->config->get('baseurl', null, true);
        $baseurl .= "v2/translate/glossary/{$glossary_id}";
        $this->response = $this->client->get($baseurl, $format, $params);
    }

    /**
     * glossary/:id (GET)
     *
     * Retrieves a full representation of a glossary object.
     *
     * @param int $glossary_id The ID of the glossary to return.
     */
    public function getGlossaryDetails($glossary_id)
    {
        $query = $this->get_query();

        $baseurl = $this->config->get('baseurl', null, true);
        $baseurl .= "glossary/{$glossary_id}";
        $this->response = $this->client->get($baseurl . '?' . $query);
    }

    /**
     * glossary (POST)
     *
     * Creates a new glossary from a given CSV file.
     *
     * @param int $glossary_id The ID of the glossary to return.
     */
    public function postGlossary($file_path)
    {
        $query = $this->get_query();

        $baseurl = $this->config->get('baseurl', null, true);
        $baseurl .= "glossary";

        $post_params = array();
        $post_params['file_path'] = $file_path;

        $this->response = $this->client->post($baseurl . '?' . $query, NULL, $post_params);
    }

    /**
     * glossary/:id (PUT)
     *
     * Updates the glossary with the given ID with given CSV file.
     *
     * @param int $glossary_id The ID of the glossary to return.
     */
    public function putGlossary($glossary_id, $file_path)
    {
        $query = $this->get_query();

        $baseurl = $this->config->get('baseurl', null, true);
        $baseurl .= "glossary/{$glossary_id}";

        $put_params = array();
        $put_params['file_path'] = $file_path;

        $this->response = $this->client->put($baseurl . '?' . $query, NULL, $put_params);
    }

    /**
     * glossary/:id (DELETE)
     *
     * Deletes a glossary with the given ID.
     *
     * @param int $glossary_id The ID of the glossary to delete.
     */
    public function deleteGlossary($glossary_id)
    {
        $query = $this->get_query();

        $baseurl = $this->config->get('baseurl', null, true);
        $baseurl .= "glossary/{$glossary_id}";

        $this->response = $this->client->delete($baseurl . '?' . $query);
    }
}
