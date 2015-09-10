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

class Gengo_Api_Jobs extends Gengo_Api
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
     * translate/jobs (POST)
     *
     * Submits a job or group of jobs to translate.
     *
     * @param array $jobs An array of jobs
     * @param int $as_group Use 1 (the default) to have submitted jobs translated but 1 translator only
     * @param string $version Version of the API to use. Defaults to 'v2'.
     */
    public function postJobs(array $jobs, $as_group = 1, $version = 'v2')
    {
        $attachments = array();
        foreach ($jobs as $job)
        {
            if (isset($job['comment']) && is_array($job['comment']))
            {
                // comment attachments validation
                if (! isset($job['comment']['attachments']) || !is_array($job['comment']['attachments']))
                {
                    throw new Gengo_Exception(sprintf('Job comment missing attachments key: %s', print_r($job['comment'], true)));
                }
                // validate each attachment
                foreach ($job['comment']['attachments'] as $attachment)
                {
                    // file_key: is equivalent to the "name" attribute of an html input tag
                    // filepath: is an absolute path to a file
                    if (! isset($attachment['file_key']) && !isset($attachment['filepath']))
                    {
                        throw new Gengo_Exception(sprintf('Comment attachment missing file_key or filepath: %s', print_r($attachment, true)));
                    }
                    if (! is_file($attachment['filepath']))
                    {
                        throw new Gengo_Exception(sprintf('Comment attachment filepath could not be found: %s', print_r($attachment['filepath'], true)));
                    }
                }
            }
        }


        $data = array('jobs'     => $jobs,
                      'as_group' => intval($as_group),
                      'process'  => 1);

        $ts = gmdate('U');
        // create the query
        $params = array('api_key' => $this->config->get('api_key', null, true),
                        '_method' => 'post',
                        'ts'      => $ts,
                        'data'    => json_encode($data),
                        'api_sig' => Gengo_Crypto::sign($ts, $this->config->get('private_key', null, true)),
        );

        $format = $this->config->get('format', null, true);
        $baseurl = $this->config->get('baseurl', null, true);
        $baseurl .= "{$version}/translate/jobs";
        $this->response = $this->client->post($baseurl, $format, $params);
    }

    /**
     * translate/jobs (GET)
     *
     * Retrieves a list of resources for the most recent jobs filtered
     * by the given parameters.
     *
     * @param array $ids An OPTIONAL array of ids.
     * @param string $format The OPTIONAL response format: xml or json (default).
     * @param array|string $params (DEPRECATED) If passed should contain all the
     * necessary parameters for the request including the api_key and
     * api_sig
     * @param string $version Version of the API to use. Defaults to 'v2'.
     */
    public function getJobs($ids = null, $format = null, $params = null, $version = 'v2')
    {
        $this->setParamsNotId($format, $params);
        $baseurl = $this->config->get('baseurl', null, true);
        $baseurl .= "{$version}/translate/jobs";
        if (!is_null($ids))
        {
            $baseurl .= '/' . implode(',', $ids);
        }

        $this->response = $this->client->get($baseurl, $format, $params);
    }

    /**
     * translate/jobs/ (PUT)
     *
     * Updates jobs to translate. returns these jobs back to the translators for revisions.
     *
     * @param array $jobs (required) An array of arrays i.e. $jobs[] = array('job_id' => 12345, 'comment' => 'A comment here')
     * Note: the comment in the above $jobs array is optional if the $comment parameters is passed to this method
     * @param string $version Version of the API to use. Defaults to 'v2'.
     * @param string $comment The comment that will be applied to all of the jobs that don't have one
     */
    public function revise(array $jobs, $version = 'v2', $comment = NULL)
    {
        // pack the jobs
        $data = array('action' => 'revise',
                      'job_ids' => $jobs);

        // add all jobs level comment if present
        if (mb_strlen($comment) > 0)
        {
            $data['comment'] = $comment;
        }

        $ts = gmdate('U');
        // create the query
        $params = array('api_key' => $this->config->get('api_key', null, true),
                        'ts'      => $ts,
                        'data'    => json_encode($data),
                        'api_sig' => Gengo_Crypto::sign($ts, $this->config->get('private_key', null, true)),
        );

        $format = $this->config->get('format', null, true);
        $this->setParamsNotId($format, $params);
        $baseurl = $this->config->get('baseurl', null, true);
        $baseurl .= "{$version}/translate/jobs/";
        $this->response = $this->client->put($baseurl, $format, $params);
    }

    /**
     * translate/jobs/ (PUT)
     *
     * Updates jobs to translate. Approves jobs.
     *
     * @param array $jobs (required) the payloads to identify the jobs sent back for approval:
     *  - "job_id" the job's id
     *  - "rating" (optional) - 1 (poor) to 5 (fantastic)
     *  - "for_translator" (optional) - comments for the translator
     *  - "for_mygengo" (optional) - comments for Gengo staff (private)
     *  - "public" (optional) - 1 (true) / 0 (false, default); whether Gengo can share this feedback publicly
     * @param string $version Version of the API to use. Defaults to 'v2'.
     */
    public function approve(array $jobs, $version='v2')
    {
        $data = array('action' => 'approve',
                      'job_ids' => $jobs);

        $ts = gmdate('U');
        // create the query
        $params = array('api_key' => $this->config->get('api_key', null, true),
                        'ts'      => $ts,
                        'data'    => json_encode($data),
                        'api_sig' => Gengo_Crypto::sign($ts, $this->config->get('private_key', null, true)),
        );

        $format = $this->config->get('format', null, true);
        $this->setParamsNotId($format, $params);
        $baseurl = $this->config->get('baseurl', null, true);
        $baseurl .= "{$version}/translate/jobs/";
        $this->response = $this->client->put($baseurl, $format, $params);
    }

    /**
     * translate/jobs/ (PUT)
     *
     * Updates jobs to translate. returns these jobs back to the translators for rejection.
     *
     * @param array $jobs (required) the payloads to identify the jobs sent back for rejections:
     *  - reason (required) - "quality", "incomplete", "other"
     *  - comment (required)
     *  - captcha (required) - the captcha image text. Each job in a "reviewable" state will
     *  - job_id (required) - The id of the job to reject
     *  - have a captcha_url value, which is a URL to an image.  This
     *  - captcha value is required only if a job is to be rejected.
     *  - follow_up (optional) - "requeue" (default) or "cancel"
     * @param string $version Version of the API to use. Defaults to 'v2'.
     * @param string $comment The comment that will be applied to all of the jobs that don't have one
     */
    public function reject(array $jobs, $version='v2', $comment = NULL)
    {
        $data = array('action' => 'reject',
                      'job_ids' => $jobs);

        // add all jobs level comment if present
        if (mb_strlen($comment) > 0)
        {
            $data['comment'] = $comment;
        }

        $ts = gmdate('U');
        // create the query
        $params = array('api_key' => $this->config->get('api_key', null, true),
                        'ts'      => $ts,
                        'data'    => json_encode($data),
                        'api_sig' => Gengo_Crypto::sign($ts, $this->config->get('private_key', null, true))
        );

        $format = $this->config->get('format', null, true);
        $this->setParamsNotId($format, $params);
        $baseurl = $this->config->get('baseurl', null, true);
        $baseurl .= "{$version}/translate/jobs/";
        $this->response = $this->client->put($baseurl, $format, $params);
    }

    /**
     * translate/jobs/ (PUT)
     *
     * Archive jobs
     *
     * @param array $jobs (required) An array containing the job ids to archive
     * @param string $version Version of the API to use. Defaults to 'v2'.
     */
    public function archive(array $jobs, $version = 'v2')
    {
        // pack the jobs
        $data = array('action' => 'archive',
                      'job_ids' => $jobs);

        $ts = gmdate('U');
        // create the query
        $params = array('api_key' => $this->config->get('api_key', null, true),
                        'ts'      => $ts,
                        'data'    => json_encode($data),
                        'api_sig' => Gengo_Crypto::sign($ts, $this->config->get('private_key', null, true)),
        );

        $format = $this->config->get('format', null, true);
        $this->setParamsNotId($format, $params);
        $baseurl = $this->config->get('baseurl', null, true);
        $baseurl .= "{$version}/translate/jobs/";
        $this->response = $this->client->put($baseurl, $format, $params);
    }

    /**
     * translate/jobs/ (DELETE)
     *
     * Cancels the jobs. You can only cancel a job if it has not been
     * started already by a translator.
     *
     * @param array (required) $ids Array of ids of the jobs to cancel
     * @param string $version Version of the API to use. Defaults to 'v2'.
     */
    public function cancel($ids, $version='v2')
    {
        $data = array('job_ids' => $ids);
        $params = array('api_key' => $this->config->get('api_key', null, true), 'ts' => gmdate('U'), 'data'=> json_encode($data));
        ksort($params);
        $query = http_build_query($params);
        $hmac = hash_hmac('sha1', $query, $this->config->get('private_key', null, true));
        $params['api_sig'] = $hmac;

        $format = $this->config->get('format', null, true);
        $this->setParamsNotId($format, $params);
        $baseurl = $this->config->get('baseurl', null, true);
        $baseurl .= "{$version}/translate/jobs/";
        $this->response = $this->client->delete($baseurl, $format, $params);
    }
}
