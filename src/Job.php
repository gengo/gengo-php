<?php

/**
 * PHP version 5.6.
 *
 * @package Gengo
 */

namespace Gengo;

/**
 * Job API client class.
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
class Job extends ApproveRejectValidator
{
    /**
     * Retrieves a specific job.
     *
     * Calls translate/job/{id} (GET)
     *
     * @param int $id The ID of the job to retrieve
     *
     * @return string Gengo response
     *
     * @untranslatable job_id
     * @untranslatable v2/translate/job/
     *
     * @api
     */
    public function getJob($id = null)
    {
        $id = $this->getID($id, 'job_id');

        return $this->storeResponse(Client::get('v2/translate/job/'.$id));
    } //end getJob()

    /**
     * Updates a job to translate. Returns this job back to the translator for revisions.
     *
     * Calls translate/job/{id} (PUT)
     *
     * @param int    $id      The ID of the job to revise
     * @param string $comment The reason to the translator for sending the job back for revisions
     *
     * @return string Gengo response
     *
     * @throws Exception ID and comment are required
     *
     * @exceptioncode GENGO_EXCEPTION_COMMENT_REQUIRED
     *
     * @untranslatable revise
     * @untranslatable : \"comment\"
     * @untranslatable job_id
     * @untranslatable v2/translate/job/
     *
     * @api
     */
    public function revise($id, $comment)
    {
        if (empty($comment) === false) {
            $data = array(
                 'action' => 'revise',
                 'comment' => $comment,
                );

            $params = array('data' => json_encode($data));
        } else {
            throw new \Exception(
                _('In method').' '.__METHOD__.': "comment" '._('is required'),
                GENGO_EXCEPTION_COMMENT_REQUIRED
            );
        } //end if

        $id = $this->getID($id, 'job_id');

        return $this->storeResponse(Client::put('v2/translate/job/'.$id, $params));
    } //end revise()

    /**
     * Updates a job to translate. Approves job.
     *
     * Calls translate/job/{id} (PUT)
     *
     * @param int   $id   The ID of the job to approve
     * @param array $args Contains the parameters for the approval:
     *                    rating (optional) - 1 (poor) to 5 (fantastic)
     *                    for_translator (optional) - comments for the translator
     *                    for_mygengo (optional) - comments for Gengo staff (private)
     *                    public (optional) - 1 (true) / 0 (false, default); whether Gengo can share this feedback publicly
     *
     * @return string Gengo response
     *
     * @untranslatable approve
     * @untranslatable job_id
     * @untranslatable v2/translate/job/
     *
     * @api
     */
    public function approve($id = null, array $args = array())
    {
        $data = $this->validateApprove($args);
        $data['action'] = 'approve';

        $params = array('data' => json_encode($data));

        $id = $this->getID($id, 'job_id');

        return $this->storeResponse(Client::put('v2/translate/job/'.$id, $params));
    } //end approve()

    /**
     * Updates a job to translate. Rejects the translation.
     *
     * Calls translate/job/{id} (PUT)
     *
     * @param int   $id   The ID of the job to reject
     * @param array $args Contains the parameters for the rejection:
     *                    reason (required) - "quality", "incomplete", "other"
     *                    comment (required)
     *                    follow_up (optional) - "requeue" (default) or "cancel"
     *
     * @return string Gengo response
     *
     * @untranslatable reject
     * @untranslatable job_id
     * @untranslatable v2/translate/job/
     *
     * @api
     */
    public function reject($id = null, array $args = array())
    {
        $data = $this->validateReject($args);
        $data['action'] = 'reject';

        $params = array('data' => json_encode($data));

        $id = $this->getID($id, 'job_id');

        return $this->storeResponse(Client::put('v2/translate/job/'.$id, $params));
    } //end reject()

    /**
     * Archive job.
     *
     * Calls translate/job/{id} (PUT)
     *
     * @param int $id The ID of the job to archive
     *
     * @return string Gengo response
     *
     * @untranslatable archive
     * @untranslatable job_id
     * @untranslatable v2/translate/jobs/
     *
     * @api
     */
    public function archive($id = null)
    {
        $data = array('action' => 'archive');

        $params = array('data' => json_encode($data));

        $id = $this->getID($id, 'job_id');

        return $this->storeResponse(Client::put('v2/translate/job/'.$id, $params));
    } //end archive()

    /**
     * Cancels the job. You can only cancel a job if it has not been started already by a translator.
     *
     * Calls translate/job/{id} (DELETE)
     *
     * @param int $id The ID of the job to cancel
     *
     * @return string Gengo response
     *
     * @untranslatable job_id
     * @untranslatable v2/translate/job/
     *
     * @api
     */
    public function cancel($id = null)
    {
        $id = $this->getID($id, 'job_id');

        return $this->storeResponse(Client::delete('v2/translate/job/'.$id));
    } //end cancel()

    /**
     * Gets list of revision resources for a job.
     *
     * Calls translate/job/{id}/revisions (GET)
     *
     * @param int $id The ID of the job to retrieve
     *
     * @return string Gengo response
     *
     * @untranslatable job_id
     * @untranslatable v2/translate/job/
     * @untranslatable /revisions
     *
     * @api
     */
    public function getRevisions($id = null)
    {
        $id = $this->getID($id, 'job_id');

        return $this->storeResponse(Client::get('v2/translate/job/'.$id.'/revisions'));
    } //end getRevisions()

    /**
     * Gets specific revision for a job.
     *
     * Calls translate/job/{id}/revision/{rev_id}
     *
     * @param int $id    The ID of the job to retrieve
     * @param int $revid The ID of the revision to retrieve
     *
     * @return string Gengo response
     *
     * @untranslatable job_id
     * @untranslatable revision_id
     * @untranslatable v2/translate/job/
     * @untranslatable /revision/
     *
     * @api
     */
    public function getRevision($id = null, $revid = null)
    {
        $id = $this->getID($id, 'job_id');
        $revid = $this->getID($revid, 'revision_id');

        return $this->storeResponse(Client::get('v2/translate/job/'.$id.'/revision/'.$revid));
    } //end getRevision()

    /**
     * Retrieves the feedback.
     *
     * Calls translate/job/{id}/feedback (GET)
     *
     * @param int $id The ID of the job to retrieve
     *
     * @return string Gengo response
     *
     * @untranslatable job_id
     * @untranslatable v2/translate/job/
     * @untranslatable /feedback
     *
     * @api
     */
    public function getFeedback($id = null)
    {
        $id = $this->getID($id, 'job_id');

        return $this->storeResponse(Client::get('v2/translate/job/'.$id.'/feedback'));
    } //end getFeedback()

    /**
     * Retrieves the comment thread for a job.
     *
     * Calls translate/job/{id}/comments (GET)
     *
     * @param int $id The ID of the job to retrieve
     *
     * @return string Gengo response
     *
     * @untranslatable job_id
     * @untranslatable v2/translate/job/
     * @untranslatable /comments
     *
     * @api
     */
    public function getComments($id = null)
    {
        $id = $this->getID($id, 'job_id');

        return $this->storeResponse(Client::get('v2/translate/job/'.$id.'/comments'));
    } //end getComments()

    /**
     * Submits a new comment to the job's comment thread.
     *
     * Calls translate/job/{id}/comment (POST)
     *
     * @param int    $id   The ID of the job to comment on
     * @param string $body The comment's actual contents
     *
     * @return string Gengo response
     *
     * @throws Exception Valid parameter is required
     *
     * @exceptioncode GENGO_EXCEPTION_VALID_PARAMETER_IS_REQUIRED
     *
     * @untranslatable post
     * @untranslatable \"body\"
     * @untranslatable job_id
     * @untranslatable v2/translate/job/
     * @untranslatable /comment
     *
     * @api
     */
    public function postComment($id, $body)
    {
        if (empty($body) === false) {
            $data = array('body' => $body);

            $params = array(
                   '_method' => 'post',
                   'data' => json_encode($data),
                  );
        } else {
            throw new \Exception(
                _('In method').' '.__METHOD__.': '._('must contain a valid').' "body" '._('parameter as the comment'),
                GENGO_EXCEPTION_VALID_PARAMETER_IS_REQUIRED
            );
        }

        $id = $this->getID($id, 'job_id');

        return $this->storeResponse(Client::post('v2/translate/job/'.$id.'/comment', $params));
    } //end postComment()
} //end class
