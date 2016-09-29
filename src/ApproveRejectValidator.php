<?php

/**
 * PHP version 5.6.
 */

namespace Gengo;

/**
 * Approve/reject validator class.
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
class ApproveRejectValidator extends API
{
    /**
     * Validates job approval fields.
     *
     * @param array $job Contains the parameters for the approval:
     *                   rating (optional) - 1 (poor) to 5 (fantastic)
     *                   for_translator (optional) - comments for the translator
     *                   for_mygengo (optional) - comments for Gengo staff (private)
     *                   public (optional) - 1 (true) / 0 (false, default); whether Gengo can share this feedback publicly
     *
     * @return array Validated fields
     *
     * @throws Exception Invalid calling parameters
     *
     * @exceptioncode GENGO_EXCEPTION_JOB_SHOULD_CONTAIN_VALID_RATING
     *
     * @untranslatable rating
     * @untranslatable for_translator
     * @untranslatable for_mygengo
     *
     * @api
     */
    protected function validateApprove(array $job)
    {
        if (isset($job['rating']) === true &&
            (is_numeric($job['rating']) === true && $job['rating'] >= 1 && $job['rating'] <= 5) === false) {
            throw new \Exception(
                _('In method').' '.__METHOD__.': '._('job should contain a valid rating'),
                GENGO_EXCEPTION_JOB_SHOULD_CONTAIN_VALID_RATING
            );
        }

        $data = array();
        $data['public'] = (isset($job['public']) === true && $job['public'] === true) ? 1 : 0;
        foreach (array('rating', 'for_translator', 'for_mygengo') as $key) {
            if (isset($job[$key]) === true) {
                $data[$key] = $job[$key];
            }
        }

        return $data;
    } //end validateApprove()

    /**
     * Validates job rejection fields.
     *
     * @param array $job Contains the parameters for the rejection:
     *                   reason (required) - "quality", "incomplete", "other"
     *                   comment (required)
     *                   captcha (required) - the captcha image text. Each job in a "reviewable" state will
     *                   have a captcha_url value, which is a URL to an image.  This
     *                   captcha value is required only if a job is to be rejected.
     *                   follow_up (optional) - "requeue" (default) or "cancel"
     *
     * @return array Validated fields
     *
     * @throws Exception Incomplete or invalid calling parameters
     *
     * @exceptioncode GENGO_EXCEPTION_JOB_MUST_CONTAIN_VALID_REASON
     * @exceptioncode GENGO_EXCEPTION_JOB_SHOULD_CONTAIN_VALID_FOLLOWUP
     * @exceptioncode GENGO_EXCEPTION_REASON_REQUIRED
     *
     * @untranslatable quality
     * @untranslatable incomplete
     * @untranslatable other
     * @untranslatable requeue
     * @untranslatable cancel
     *
     * @api
     */
    protected function validateReject(array $job = array())
    {
        if (isset($job['reason']) === true && isset($job['comment']) === true && isset($job['captcha']) === true) {
            $reason = $job['reason'];
            $comment = $job['comment'];
            $captcha = $job['captcha'];

            $validreasons = array(
                     'quality',
                     'incomplete',
                     'other',
                    );
            if (in_array($reason, $validreasons) === false) {
                throw new \Exception(
                    _('In method').' '.__METHOD__.': '._('job must contain a valid reason'),
                    GENGO_EXCEPTION_JOB_MUST_CONTAIN_VALID_REASON
                );
            }

            $data = array(
                 'reason' => $reason,
                 'comment' => $comment,
                 'captcha' => $captcha,
                );

            if (isset($job['follow_up']) === true) {
                $validfollowups = array(
                           'requeue',
                           'cancel',
                          );
                if (in_array($job['follow_up'], $validfollowups) === false) {
                    throw new \Exception(
                        _('In method').' '.__METHOD__.': '._('if set, job should contain a valid follow up'),
                        GENGO_EXCEPTION_JOB_SHOULD_CONTAIN_VALID_FOLLOWUP
                    );
                }

                $data['follow_up'] = $job['follow_up'];
            }
        } else {
            throw new \Exception(
                _('In method').' '.__METHOD__.': '._('job must contain a reason, a comment and a captcha'),
                GENGO_EXCEPTION_REASON_REQUIRED
            );
        } //end if

        return $data;
    } //end validateReject()
} //end class
