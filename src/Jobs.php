<?php

/**
 * PHP version 5.6.
 *
 * @package Gengo
 */

namespace Gengo;

/**
 * Jobs API client class.
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
class Jobs extends ApproveRejectValidator
{
    /**
     * Submits a job or group of jobs to translate.
     *
     * Calls translate/jobs (POST)
     *
     * @param array $jobs    An array of jobs
     * @param bool  $asgroup Use true (the default) to have submitted jobs translated but 1 translator only
     *
     * @return string Gengo response
     *
     * @untranslatable post
     * @untranslatable v2/translate/jobs
     *
     * @api
     */
    public function postJobs(array $jobs, $asgroup = true)
    {
        $this->_validateAttachments($jobs);

        foreach ($jobs as $job) {
            if (is_array($job) === true) {
                $this->_validateAttachments($job);
            }
        }

        $data = array();
        $data['as_group'] = ($asgroup === true) ? 1 : 0;
        $data['process'] = 1;

        if (isset($jobs['tone']) === true) {
            $data['tone'] = $jobs['tone'];
            unset($jobs['tone']);
        }

        if (isset($jobs['purpose']) === true) {
            $data['purpose'] = $jobs['purpose'];
            unset($jobs['purpose']);
        }

        if (isset($jobs['reference_id']) === true) {
            $data['reference_id'] = $jobs['reference_id'];
            unset($jobs['reference_id']);
        }

        if (isset($jobs['comment']) === true) {
            $data['comment'] = $jobs['comment'];
            unset($jobs['comment']);
        }

        $data['jobs'] = $jobs;

        $params = array(
               '_method' => 'post',
               'data' => json_encode($data),
              );

        return $this->storeResponse(Client::post('v2/translate/jobs', $params));
    } //end postJobs()

    /**
     * Validate URL attachments.
     *
     * @param array $job Job payload
     *
     * @throws Exception URL attachments are not valid
     *
     * @exceptioncode GENGO_EXCEPTION_URL_ATTACHMENTS_NOT_ARRAY
     * @exceptioncode GENGO_EXCEPTION_URL_ATTACHMENT_IS_NOT_HTTP_URL
     * @exceptioncode GENGO_EXCEPTION_URL_ATTACHMENT_NEEDS_FILENAME
     * @exceptioncode GENGO_EXCEPTION_URL_ATTACHMENT_NEEDS_MIMETYPE
     *
     * @internal
     */
    private function _validateAttachments(array &$job)
    {
        if (isset($job['url_attachments']) === true && is_array($job['url_attachments']) === true) {
            if (isset($job['attachments']) === false) {
                $job['attachments'] = array();
            }

            foreach ($job['url_attachments'] as $attachment) {
                if (is_array($attachment) === false) {
                    throw new \Exception(_('URL attachment must be an array'), GENGO_EXCEPTION_URL_ATTACHMENTS_NOT_ARRAY);
                }

                if (isset($attachment['url']) === false || preg_match("/^https?:\/\/.*/", $attachment['url']) === 0) {
                    throw new \Exception(
                        _('URL attachment must point to public URL with http(s) scheme'),
                        GENGO_EXCEPTION_URL_ATTACHMENT_IS_NOT_HTTP_URL
                    );
                }

                if (isset($attachment['filename']) === false || strlen($attachment['filename']) === 0) {
                    throw new \Exception(_('URL attachment filename must be specified'), GENGO_EXCEPTION_URL_ATTACHMENT_NEEDS_FILENAME);
                }

                if (isset($attachment['mime_type']) === false || strlen($attachment['mime_type']) === 0) {
                    throw new \Exception(_('URL attachment MIME type must be specified'), GENGO_EXCEPTION_URL_ATTACHMENT_NEEDS_MIMETYPE);
                }

                $job['attachments'][] = $attachment;
            } //end foreach
        } //end if

        unset($job['url_attachments']);
    } //end _validateAttachments()

    /**
     * Retrieves a list of resources for the most recent jobs filtered by the given parameters.
     *
     * Calls translate/jobs (GET)
     *
     * @param string $status         One of "available”, “pending”, “reviewable”, “approved”, “rejected”, or “canceled”
     * @param int    $timestampafter Epoch timestamp from which to filter submitted jobs
     * @param int    $count          Defaults to 10 (maximum 200)
     *
     * @return string Gengo response
     *
     * @throws Exception Valid arguments required
     *
     * @exceptioncode GENGO_EXCEPTION_MUST_CONTAIN_VALID_STATUS
     * @exceptioncode GENGO_EXCEPTION_TIMESTAMP_MUST_BE_VALID
     * @exceptioncode GENGO_EXCEPTION_COUNT_MUST_MUST_BE_VALID
     *
     * @untranslatable available
     * @untranslatable pending
     * @untranslatable reviewable
     * @untranslatable approved
     * @untranslatable rejected
     * @untranslatable canceled
     * @untranslatable : \"status\"
     * @untranslatable : \"timestampafter\"
     * @untranslatable : \"count\"
     * @untranslatable v2/translate/jobs
     *
     * @api
     */
    public function getJobs($status = null, $timestampafter = null, $count = null)
    {
        $data = array();

        if ($status !== null) {
            $validstatus = array(
                    'available',
                    'pending',
                    'reviewable',
                    'approved',
                    'rejected',
                    'canceled',
                       );
            if (in_array($status, $validstatus) === false) {
                throw new \Exception(
                    _('In method').' '.__METHOD__.': "status" '._('must contain a valid status'),
                    GENGO_EXCEPTION_MUST_CONTAIN_VALID_STATUS
                );
            }

            $data['status'] = $status;
        }

        if ($timestampafter !== null) {
            if (is_int($timestampafter) === false || $timestampafter < 0) {
                throw new \Exception(
                    _('In method').' '.__METHOD__.': "timestampafter" '._('must be non-negative integer'),
                    GENGO_EXCEPTION_TIMESTAMP_MUST_BE_VALID
                );
            }

            $data['timestamp_after'] = $timestampafter;
        }

        if ($count !== null) {
            if (is_int($count) === false || $count < 1 || $count > 200) {
                throw new \Exception(
                    _('In method').' '.__METHOD__.': "count" '._('must be integer in range between 1 and 200'),
                    GENGO_EXCEPTION_COUNT_MUST_MUST_BE_VALID
                );
            }

            $data['count'] = $count;
        }

        return $this->storeResponse(Client::get('v2/translate/jobs', $data));
    } //end getJobs()

    /**
     * Retrieves a list of jobs. They are requested by a comma-separated list of job ids.
     *
     * Calls translate/jobs/{ids} (GET)
     *
     * @param array $ids An array of IDs
     *
     * @return string Gengo response
     *
     * @untranslatable v2/translate/jobs
     *
     * @api
     */
    public function getJobsByID(array $ids)
    {
        $url = 'v2/translate/jobs';
        if (is_array($ids) === true) {
            $url .= '/'.implode(',', $ids);
        }

        return $this->storeResponse(Client::get($url));
    } //end getJobsByID()

    /**
     * Updates jobs to translate. Returns these jobs back to the translators for revisions.
     *
     * Calls translate/jobs/ (PUT)
     *
     * @param array  $jobs    An array of arrays i.e. $jobs[] = array("job_id" => 12345, "comment" => "A comment here")
     *                        Note: the comment in the above $jobs array is optional if the $comment parameters is passed to this method
     * @param string $comment The comment that will be applied to all of the jobs that don't have one
     *
     * @return string Gengo response
     *
     * @throws Exception Valid comment is requried
     *
     * @exceptioncode GENGO_EXCEPTION_COMMENT_REQUIRED
     *
     * @untranslatable revise
     * @untranslatable : \"comment\"
     * @untranslatable v2/translate/jobs/
     *
     * @api
     */
    public function revise(array $jobs, $comment = null)
    {
        if (empty($comment) === false) {
            $this->_validateJobIDs($jobs);

            $data = array(
                 'action' => 'revise',
                 'job_ids' => $jobs,
                 'comment' => $comment,
                );

            $params = array('data' => json_encode($data));
        } else {
            throw new \Exception(
                _('In method').' '.__METHOD__.': "comment" '._('is required'),
                GENGO_EXCEPTION_COMMENT_REQUIRED
            );
        } //end if

        return $this->storeResponse(Client::put('v2/translate/jobs/', $params));
    } //end revise()

    /**
     * Updates jobs to translate. Approves jobs.
     *
     * Calls translate/jobs/ (PUT)
     *
     * @param array $jobs The payloads to identify the jobs sent back for approval:
     *                    - "job_id" the job's ID
     *                    - "rating" (optional) - 1 (poor) to 5 (fantastic)
     *                    - "for_translator" (optional) - comments for the translator
     *                    - "for_mygengo" (optional) - comments for Gengo staff (private)
     *                    - "public" (optional) - 1 (true) / 0 (false, default); whether Gengo can share this feedback publicly
     *
     * @return string Gengo response
     *
     * @untranslatable approve
     * @untranslatable v2/translate/jobs/
     *
     * @api
     */
    public function approve(array $jobs)
    {
        $this->_validateJobIDs($jobs);

        foreach ($jobs as $idx => $job) {
            $jobid = $job['job_id'];
            $data = $this->validateApprove($job);
            $data['job_id'] = $jobid;
            $jobs[$idx] = $data;
        }

        $data = array(
             'action' => 'approve',
             'job_ids' => $jobs,
            );

        $params = array('data' => json_encode($data));

        return $this->storeResponse(Client::put('v2/translate/jobs/', $params));
    } //end approve()

    /**
     * Updates jobs to translate. Returns these jobs back to the translators for rejection.
     *
     * Calls translate/jobs/ (PUT)
     *
     * @param array  $jobs    The payloads to identify the jobs sent back for rejections:
     *                        - "job_id" (required) - The ID of the job to reject
     *                        - "reason" (required) - "quality", "incomplete", "other"
     *                        - "comment" (required)
     *                        - "captcha" (required) - the captcha image text. Each job in a "reviewable" state will
     *                        have a captcha_url value, which is a URL to an image.  This
     *                        captcha value is required only if a job is to be rejected.
     *                        - "follow_up" (optional) - "requeue" (default) or "cancel"
     * @param string $comment The comment that will be applied to all of the jobs that don't have one
     *
     * @return string Gengo response
     *
     * @untranslatable reject
     * @untranslatable v2/translate/jobs/
     *
     * @api
     */
    public function reject(array $jobs, $comment = null)
    {
        $this->_validateJobIDs($jobs);

        foreach ($jobs as $idx => $job) {
            $jobid = $job['job_id'];
            $data = $this->validateReject($job);
            $data['job_id'] = $jobid;
            $jobs[$idx] = $data;
        }

        $data = array(
             'action' => 'reject',
             'job_ids' => $jobs,
             'comment' => $comment,
            );

        $params = array('data' => json_encode($data));

        return $this->storeResponse(Client::put('v2/translate/jobs/', $params));
    } //end reject()

    /**
     * Archive jobs.
     *
     * Calls translate/jobs/ (PUT)
     *
     * @param array $jobs An array containing the job IDs to archive
     *
     * @return string Gengo response
     *
     * @untranslatable archive
     * @untranslatable v2/translate/jobs/
     *
     * @api
     */
    public function archive(array $jobs)
    {
        $this->_validateJobIDs($jobs);

        $data = array(
             'action' => 'archive',
             'job_ids' => $jobs,
            );

        $params = array('data' => json_encode($data));

        return $this->storeResponse(Client::put('v2/translate/jobs/', $params));
    } //end archive()

    /**
     * Validate job_id fields in jobs.
     *
     * @param array $jobs Job payloads
     *
     * @throws Exception Invalid or no job_id found
     *
     * @exceptioncode GENGO_EXCEPTION_JOB_ID_REQUIRED
     * @exceptioncode GENGO_EXCEPTION_INVALID_JOB_ID_SUPPLIED
     *
     * @internal
     */
    private function _validateJobIDs(array $jobs)
    {
        foreach ($jobs as $job) {
            if (isset($job['job_id']) === false) {
                throw new \Exception(_('All jobs require job_id field'), GENGO_EXCEPTION_JOB_ID_REQUIRED);
            }

            if (is_numeric($job['job_id']) === false) {
                throw new \Exception(_('Invalid job_id supplied'), GENGO_EXCEPTION_INVALID_JOB_ID_SUPPLIED);
            }
        }
    } //end _validateJobIDs()
} //end class
