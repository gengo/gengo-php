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

class Gengo_Api_Job extends Gengo_Api
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
     * translate/job/{id} (GET)
     *
     * Retrieves a specific job
     *
     * @param int $id The id of the job to retrieve
     * @param string $format The response format, xml or json
     * @param array|string $params (DEPRECATED) If passed should contain all the
     * necessary parameters for the request including the api_key and
     * api_sig
     */
    public function getJob($id = null, $format = null, $params = null)
    {
        $this->setParams($id, $format, $params);
        $baseurl = $this->config->get('baseurl', null, true);
        $baseurl .= "v2/translate/job/{$id}";
        $this->response = $this->client->get($baseurl, $format, $params);
    }

    /**
     * translate/job/{id}/comments (GET)
     *
     * Retrieves the comment thread for a job
     *
     * @param int $id The id of the job to retrieve
     * @param string $format The OPTIONAL response format: xml or json (default).
     * @param array|string $params (DEPRECATED) If passed should contain all the
     * necessary parameters for the request including the api_key and
     * api_sig
     */
    public function getComments($id, $format = null, $params = null)
    {
        $this->setParams($id, $format, $params);
        $baseurl = $this->config->get('baseurl', null, true);
        $baseurl .= "v2/translate/job/{$id}/comments";
        $this->response = $this->client->get($baseurl, $format, $params);
    }

    /**
     * translate/job/{id}/feedback (GET)
     *
     * Retrieves the feedback
     *
     * @param int $id The id of the job to retrieve
     * @param string $format The OPTIONAL response format: xml or json (default).
     * @param array|string $params (DEPRECATED) If passed should contain all the
     * necessary parameters for the request including the api_key and
     * api_sig
     */
    public function getFeedback($id, $format = null, $params = null)
    {
        $this->setParams($id, $format, $params);
        $baseurl = $this->config->get('baseurl', null, true);
        $baseurl .= "v2/translate/job/{$id}/feedback";
        $this->response = $this->client->get($baseurl, $format, $params);
    }

    /**
     * translate/job/{id}/revisions (GET)
     *
     * Gets list of revision resources for a job.
     *
     * @param int $id The id of the job to retrieve
     * @param string $format The OPTIONAL response format: xml or json
     * @param array|string $params (DEPRECATED) If passed should contain all the
     * necessary parameters for the request including the api_key and
     * api_sig
     */
    public function getRevisions($id, $format = null, $params = null)
    {
        $this->setParams($id, $format, $params);
        $baseurl = $this->config->get('baseurl', null, true);
        $baseurl .= "v2/translate/job/{$id}/revisions";
        $this->response = $this->client->get($baseurl, $format, $params);
    }

    /**
     * translate/job/{id}/revision/{rev_id}
     *
     * Gets specific revision for a job.
     *
     * @param int $id The id of the job to retrieve
     * @param int $rev_id The id of the revision to retrieve
     * @param string $format The OPTIONAL response format: xml or json
     * @param array|string $params (DEPRECATED) If passed should contain all the
     * necessary parameters for the request including the api_key and
     * api_sig
     */
    public function getRevision($id, $rev_id, $format = null, $params = null)
    {
        $this->setParams($id, $format, $params);
        if (is_null($rev_id))
        {
            $rev_id = $this->config->get('rev_id', null, true);
        }
        $baseurl = $this->config->get('baseurl', null, true);
        $baseurl .= "v2/translate/job/{$id}/revision/{$rev_id}";
        $this->response = $this->client->get($baseurl, $format, $params);
    }

    /**
     * translate/job/{id} (PUT)
     *
     * Updates a job to translate. returns this job back to the translator for revisions.
     *
     * @param int $id The id of the job to revise
     * @param string $comment (required) the reason to the translator for sending the job back for revisions.
     */
    public function revise($id, $comment)
    {
        if (!(empty($id) || empty($comment)))
        {
            // pack the jobs
            $data = array('action' => 'revise', 'comment' => $comment);

            $ts = gmdate('U');
            // create the query
            $params = array('api_key' => $this->config->get('api_key', null, true),
                            'ts'      => $ts,
                            'data'    => json_encode($data),
                            'api_sig' => Gengo_Crypto::sign($ts, $this->config->get('private_key', null, true)),
            );
        }
        else {
            throw new Gengo_Exception(
                    sprintf('In method %s: "id" and "comment" are required', __METHOD__)
                    );
        }

        $format = $this->config->get('format', null, true);
        $this->setParams($id, $format, $params);
        $baseurl = $this->config->get('baseurl', null, true);
        $baseurl .= "v2/translate/job/{$id}";
        $this->response = $this->client->put($baseurl, $format, $params);
    }

    /**
     * translate/job/{id} (PUT)
     *
     * Updates a job to translate. Approves job.
     *
     * @param int $id The id of the job to approve
     * @param array|string $args contains the parameters for the approval:
     *  rating (optional) - 1 (poor) to 5 (fantastic)
     *  for_translator (optional) - comments for the translator
     *  for_mygengo (optional) - comments for Gengo staff (private)
     *  public (optional) - 1 (true) / 0 (false, default); whether Gengo can share this feedback publicly
     */
    public function approve($id, $args = array())
    {
        if (!is_null($id))
        {
            if (isset($args['rating']) &&
                !(is_numeric($args['rating']) && $args['rating'] >= 1 && $args['rating'] <= 5))
            {
                throw new Gengo_Exception(
                        sprintf('In method %s: "params" must contain a valid rating', __METHOD__)
                        );
            }

            // pack the jobs
            $data = array('action' => 'approve', 'public' => (isset($args['public']) && !empty($args['public'])) ? 1 : 0);
            if (isset($args['rating']))
            {
                $data['rating'] = $args['rating'];
            }
            if (isset($args['for_translator']))
            {
                $data['for_translator'] = $args['for_translator'];
            }
            if (isset($args['for_mygengo']))
            {
                $data['for_mygengo'] = $args['for_mygengo'];
            }

            $ts = gmdate('U');
            // create the query
            $params = array('api_key' => $this->config->get('api_key', null, true),
                            'ts'      => $ts,
                            'data'    => json_encode($data),
                            'api_sig' => Gengo_Crypto::sign($ts, $this->config->get('private_key', null, true)),
            );
        }
        else
        {
            throw new Gengo_Exception(
                    sprintf('In method %s: "id" is required.', __METHOD__)
                    );
        }

        $format = $this->config->get('format', null, true);
        $this->setParams($id, $format, $params);
        $baseurl = $this->config->get('baseurl', null, true);
        $baseurl .= "v2/translate/job/{$id}";
        $this->response = $this->client->put($baseurl, $format, $params);
    }

    /**
     * translate/job/{id} (PUT)
     *
     * Updates a job to translate. rejects the translation
     *
     * @param int $id The id of the job to reject
     * @param string $format The response format, xml or json
     * @param array|string $args contains the parameters for the rejection:
     *  reason (required) - "quality", "incomplete", "other"
     *  comment (required)
     *  captcha (required) - the captcha image text. Each job in a "reviewable" state will
     *  have a captcha_url value, which is a URL to an image.  This
     *  captcha value is required only if a job is to be rejected.
     *  follow_up (optional) - "requeue" (default) or "cancel"
     */
    public function reject($id, $args)
    {
        if (!empty($id) && isset($args['reason']) && isset($args['comment']) && isset($args['captcha']))
        {
            $reason = $args['reason'];
            $comment = $args['comment'];
            $captcha = $args['captcha'];

            $valid_reasons = array("quality", "incomplete", "other");
            if (!in_array($reason, $valid_reasons))
            {
                throw new Gengo_Exception(
                        sprintf('In method %s: "params" must contain a valid reason', __METHOD__)
                        );
            }
            // pack the jobs
            $data = array('action' => 'reject', 'reason' => $reason, 'comment' => $comment, 'captcha' => $captcha);

            $valid_follow_ups = array("requeue", "cancel");
            if (isset($args['follow_up']))
            {
                if (!in_array($args['follow_up'], $valid_follow_ups))
                {
                    throw new Gengo_Exception(
                            sprintf('In method %s: if set, "params" must contain a valid follow up', __METHOD__)
                            );
                }
                $data['follow_up'] = $args['follow_up'];
            }

            $ts = gmdate('U');
            // create the query
            $params = array('api_key' => $this->config->get('api_key', null, true),
                            'ts'      => $ts,
                            'data'    => json_encode($data),
                            'api_sig' => Gengo_Crypto::sign($ts, $this->config->get('private_key', null, true)),
            );
        }
        else {
            throw new Gengo_Exception(
                    sprintf('In method %s: "id" is required and "args" must contain a reason, a comment and a captcha', __METHOD__)
                    );
        }

        $format = $this->config->get('format', null, true);
        $this->setParams($id, $format, $params);
        $baseurl = $this->config->get('baseurl', null, true);
        $baseurl .= "v2/translate/job/{$id}";
        $this->response = $this->client->put($baseurl, $format, $params);
    }

    /**
     * translate/job/{id}/comment (POST)
     *
     * Submits a new comment to the job's comment thread.
     *
     * @param int $id The id of the job to comment on
     * @param string $body The comment's actual contents.
     * @param string $format The OPTIONAL response format: xml or json (default).
     * @param array|string $params (DEPRECATED) If passed should contain all the
     * necessary parameters for the request including the api_key and
     * api_sig
     */
    public function postComment($id, $body, $format = null, $params = null)
    {
        if (!(is_null($id) || is_null($body))) // If nor the id or the body are null, we override params.
        {
            // pack the jobs
            $data = array('body' => $body);

            $ts = gmdate('U');
            // create the query
            $params = array('api_key' => $this->config->get('api_key', null, true), '_method' => 'post',
                            'ts'      => $ts,
                            'data'    => json_encode($data),
                            'api_sig' => Gengo_Crypto::sign($ts, $this->config->get('private_key', null, true)),
            );
        }

        if (empty($params))
        {
            throw new Gengo_Exception(
                sprintf('In method %s: "params" must contain a valid "body" parameter as the comment', __METHOD__)
                );
        }
        $this->setParams($id, $format, $params);
        $baseurl = $this->config->get('baseurl', null, true);
        $baseurl .= "v2/translate/job/{$id}/comment";
        $this->response = $this->client->post($baseurl, $format, $params);
    }

    /**
     * translate/job/{id} (DELETE)
     *
     * Cancels the job. You can only cancel a job if it has not been
     * started already by a translator.
     *
     * @param int $id The id of the job to cancel
     * @param string $format The response format, xml or json
     * @param array|string $params (DEPRECATED) If passed should contain all the
     * necessary parameters for the request including the api_key and
     * api_sig
     */
    public function cancel($id, $format = null, $params = null)
    {
        $this->setParams($id, $format, $params);
        $baseurl = $this->config->get('baseurl', null, true);
        $baseurl .= "v2/translate/job/{$id}";
        $this->response = $this->client->delete($baseurl, $format, $params);
    }
}
