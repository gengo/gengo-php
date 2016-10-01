<?php

/**
 * PHP version 5.6.
 *
 * @package Gengo\Tests
 */

namespace Gengo\Tests;

use Gengo\Config;
use Gengo\Job;
use Gengo\Jobs;
use Gengo\Order;
use PHPUnit_Framework_TestCase;

/**
 * Job class tests.
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
 * @donottranslate
 */
class JobTest extends PHPUnit_Framework_TestCase
{
    /**
     * Set up tests.
     *
     *
     * @requiredconst GENGO_PUBKEY  "pubkeyfortests"                               Gengo test public key
     * @requiredconst GENGO_PRIVKEY "privatekeyfortestuserthatcontainsonlyletters" Gengo test private key
     */
    public function setUp()
    {
        Config::setAPIkey(GENGO_PUBKEY);
        Config::setPrivateKey(GENGO_PRIVKEY);
    } //end setUp()

    /**
     * Test posting of new order with jobs.
     *
     * @return int Order ID
     */
    public function testAllowsToPostNewOrderWithNewJobs()
    {
        $job1 = array(
             'type' => 'text',
             'slug' => 'API Liverpool 1',
             'body_src' => 'Liverpool_1 Football Club is an English Premier League football club based in Liverpool, Merseyside.',
             'lc_src' => 'en',
             'lc_tgt' => 'ja',
             'tier' => 'standard',
             'force' => 1,
            );

        $jobs = array('job_01' => $job1);

        $jobsAPI = new Jobs();

        $response = json_decode($jobsAPI->postJobs($jobs), true);
        $this->assertEquals('ok', $response['opstat']);
        $this->assertTrue(isset($response['response']['order_id']));
        $orderid = $response['response']['order_id'];

        $orderAPI = new Order();

        $i = 0;
        do {
            $response = json_decode($orderAPI->getOrder($orderid), true);
            $this->assertEquals('ok', $response['opstat']);
            $this->assertTrue(isset($response['response']['order']['jobs_queued']));
            $this->assertTrue(is_array($response['response']['order']['jobs_available']));
            $queued = $response['response']['order']['jobs_queued'];
            $available = count($response['response']['order']['jobs_available']);
            sleep(10);
            ++$i;
        } while (($queued > 0 || $available === 0) && $i < 10);

        $this->assertNotEquals(10, $i, 'Gengo did not process the job in 100 seconds');

        return $response['response']['order']['jobs_available'][0];
    } //end testAllowsToPostNewOrderWithNewJobs()

    /**
     * Test retrieval of specific translation job.
     *
     * @param int $jobid Job ID
     *
     *
     * @depends testAllowsToPostNewOrderWithNewJobs
     */
    public function testRetrievesASpecificJob($jobid)
    {
        $jobAPI = new Job();

        $response = json_decode($jobAPI->getJob($jobid), true);
        $this->assertEquals('ok', $response['opstat']);
        $this->assertTrue(isset($response['response']));
        $this->assertEquals(
            'Liverpool_1 Football Club is an English Premier League football club based in Liverpool, Merseyside.',
            $response['response']['job']['body_src']
        );
    } //end testRetrievesASpecificJob()

    /**
     * Test return of a job to the translator for revision with a comment.
     *
     * @param int $jobid Job ID
     *
     *
     * @depends testAllowsToPostNewOrderWithNewJobs
     */
    public function testReturnsAJobBackToTheTranslatorForRevisions($jobid)
    {
        $jobAPI = new Job();

        $response = json_decode($jobAPI->revise($jobid, 'Test comment'), true);
        $this->assertEquals('error', $response['opstat']);
        $this->assertTrue(isset($response['err']['msg']));
        $this->assertEquals('job is not reviewable', $response['err']['msg']);
    } //end testReturnsAJobBackToTheTranslatorForRevisions()

    /**
     * Test refusal to return a job without a comment.
     *
     * @param int $jobid Job ID
     *
     *
     * @expectedException        Exception
     * @expectedExceptionMessage "comment" is required
     *
     * @depends testAllowsToPostNewOrderWithNewJobs
     */
    public function testRefusesToReturnAJobToTheTranslatorWithoutAComment($jobid)
    {
        $jobAPI = new Job();

        $jobAPI->revise($jobid, '');
    } //end testRefusesToReturnAJobToTheTranslatorWithoutAComment()

    /**
     * Test get list of revision resources for a job.
     *
     * @param int $jobid Job ID
     *
     *
     * @depends testAllowsToPostNewOrderWithNewJobs
     */
    public function testGetsListOfRevisionResourcesForAJob($jobid)
    {
        $jobAPI = new Job();

        $response = json_decode($jobAPI->getRevisions($jobid), true);
        $this->assertEquals('ok', $response['opstat']);
        $this->assertTrue(isset($response['response']['revisions']));
        $this->assertTrue(is_array($response['response']['revisions']));
    } //end testGetsListOfRevisionResourcesForAJob()

    /**
     * Test get a specific revision for a job.
     *
     * Gengo's sandbox is broken for this call. It returns following response:
     *
     *  {"opstat":"error","err":{"msg":"Internal Server Error","code":500}}
     *  {"opstat":"error","err":{"msg":"Internal Server Error","code":500}}
     *  {"opstat":"error","err":{"code":2200,"msg":"unauthorized revision access"}}
     *
     * Clearly it is broken JSON and therefore we will assert against string only.
     *
     * @param int $jobid Job ID
     *
     *
     * @depends testAllowsToPostNewOrderWithNewJobs
     */
    public function testGetsASpecificRevisionForAJob($jobid)
    {
        $jobAPI = new Job();

        $this->assertContains('unauthorized revision access', $jobAPI->getRevision($jobid, 1));
    } //end testGetsASpecificRevisionForAJob()

    /**
     * Test retrivieal of the feedback submitted for a particular job.
     *
     * @param int $jobid Job ID
     *
     *
     * @depends testAllowsToPostNewOrderWithNewJobs
     */
    public function testRetrievesTheFeedbackSubmittedForAParticularJob($jobid)
    {
        $jobAPI = new Job();

        $response = json_decode($jobAPI->getFeedback($jobid), true);
        $this->assertEquals('ok', $response['opstat']);
        $this->assertTrue(isset($response['response']['feedback']));
        $this->assertTrue(is_array($response['response']['feedback']));
    } //end testRetrievesTheFeedbackSubmittedForAParticularJob()

    /**
     * Test job approval.
     *
     * @param int $jobid Job ID
     *
     *
     * @depends testAllowsToPostNewOrderWithNewJobs
     */
    public function testApprovesJob($jobid)
    {
        $jobAPI = new Job();

        $response = json_decode($jobAPI->approve($jobid, array('rating' => 5)), true);
        $this->assertEquals('error', $response['opstat']);
        $this->assertTrue(isset($response['err']['msg']));
        $this->assertEquals('job is not reviewable', $response['err']['msg']);
    } //end testApprovesJob()

    /**
     * Test refusal to approve a job with invalid rating.
     *
     * @param int $jobid Job ID
     *
     *
     * @expectedException        Exception
     * @expectedExceptionMessage job should contain a valid rating
     *
     * @depends testAllowsToPostNewOrderWithNewJobs
     */
    public function testRefusesToApproveAJobWithInvalidRating($jobid)
    {
        $jobAPI = new Job();

        $jobAPI->approve($jobid, array('rating' => 0));
    } //end testRefusesToApproveAJobWithInvalidRating()

    /**
     * Test rejection of translation.
     *
     * @param int $jobid Job ID
     *
     *
     * @depends testAllowsToPostNewOrderWithNewJobs
     */
    public function testRejectsTheTranslation($jobid)
    {
        $jobAPI = new Job();

        $response = json_decode($jobAPI->reject($jobid, array('reason' => 'other', 'comment' => 'comment', 'captcha' => 'captcha', 'follow_up' => 'requeue')), true);
        $this->assertEquals('error', $response['opstat']);
        $this->assertTrue(isset($response['err']['msg']));
        $this->assertEquals('invalid captcha challenge', $response['err']['msg']);
    } //end testRejectsTheTranslation()

    /**
     * Test refusal of rejection of a translation with wrong reason.
     *
     * @param int $jobid Job ID
     *
     *
     * @expectedException        Exception
     * @expectedExceptionMessage job must contain a valid reason
     *
     * @depends testAllowsToPostNewOrderWithNewJobs
     */
    public function testRefusesToRejectTheTranslationWithWrongReason($jobid)
    {
        $jobAPI = new Job();

        $jobAPI->reject($jobid, array('reason' => 'wrong_reason', 'comment' => 'comment', 'captcha' => 'captcha'));
    } //end testRefusesToRejectTheTranslationWithWrongReason()

    /**
     * Test refusal of rejection of a translation with wrong follow up.
     *
     * @param int $jobid Job ID
     *
     *
     * @expectedException        Exception
     * @expectedExceptionMessage if set, job should contain a valid follow up
     *
     * @depends testAllowsToPostNewOrderWithNewJobs
     */
    public function testRefusesToRejectTheTranslationWithWrongFollowUp($jobid)
    {
        $jobAPI = new Job();

        $jobAPI->reject($jobid, array('reason' => 'other', 'comment' => 'comment', 'captcha' => 'captcha', 'follow_up' => 'wrong_followup'));
    } //end testRefusesToRejectTheTranslationWithWrongFollowUp()

    /**
     * Test refusal of rejection of a translation with wrong arguments.
     *
     * @param int $jobid Job ID
     *
     *
     * @expectedException        Exception
     * @expectedExceptionMessage job must contain a reason, a comment and a captcha
     *
     * @depends testAllowsToPostNewOrderWithNewJobs
     */
    public function testRefusesToRejectTheTranslationWithWrongArguments($jobid)
    {
        $jobAPI = new Job();

        $jobAPI->reject($jobid, array());
    } //end testRefusesToRejectTheTranslationWithWrongArguments()

    /**
     * Test archiving of a job.
     *
     * Gengo's sandbox is broken for this call. It returns following response:
     *
     *  {"opstat":"error","err":{"msg":"Internal Server Error","code":500}}
     *  {"opstat":"error","err":{"msg":"Internal Server Error","code":500}}
     *  {"opstat":"error","err":{"msg":"Internal Server Error","code":500}}
     *  {"opstat":"error","err":{"msg":"Internal Server Error","code":500}}
     *  {"opstat":"error","err":{"msg":"Internal Server Error","code":500}}
     *  {"opstat":"error","err":{"code":400,"msg":"Bad Request"}}
     *
     * Clearly it is broken JSON and therefore we will assert against string only.
     *
     * @param int $jobid Job ID
     *
     *
     * @depends testAllowsToPostNewOrderWithNewJobs
     */
    public function testArchivesApprovedJob($jobid)
    {
        $jobAPI = new Job();

        $this->assertContains('Bad Request', $jobAPI->archive($jobid));
    } //end testArchivesApprovedJob()

    /**
     * Test of comment submission to job comment thread.
     *
     * @param int $jobid Job ID
     *
     *
     * @depends testAllowsToPostNewOrderWithNewJobs
     */
    public function testSubmitsANewCommentToTheJobCommentThread($jobid)
    {
        $jobAPI = new Job();

        $response = json_decode($jobAPI->postComment($jobid, 'Test comment'), true);
        $this->assertEquals('ok', $response['opstat']);
        $this->assertTrue(isset($response['response']));
    } //end testSubmitsANewCommentToTheJobCommentThread()

    /**
     * Test of refusal to post an empty comment.
     *
     * @param int $jobid Job ID
     *
     *
     * @expectedException        Exception
     * @expectedExceptionMessage must contain a valid "body" parameter as the comment
     *
     * @depends testAllowsToPostNewOrderWithNewJobs
     */
    public function testRefusesToPostEmptyCommentToJobCommentThread($jobid)
    {
        $jobAPI = new Job();

        $jobAPI->postComment($jobid, '');
    } //end testRefusesToPostEmptyCommentToJobCommentThread()

    /**
     * Test retrieval of comment thread for a job.
     *
     * @param int $jobid Job ID
     *
     *
     * @depends testAllowsToPostNewOrderWithNewJobs
     */
    public function testRetrievesTheCommentThreadForAJob($jobid)
    {
        $jobAPI = new Job();

        $response = json_decode($jobAPI->getComments($jobid), true);
        $this->assertEquals('ok', $response['opstat']);
        $this->assertTrue(isset($response['response']));
        $this->assertEquals('Test comment', $response['response']['thread'][0]['body']);
    } //end testRetrievesTheCommentThreadForAJob()

    /**
     * Test job cancellation.
     *
     * @param int $jobid Job ID
     *
     *
     * @depends testAllowsToPostNewOrderWithNewJobs
     */
    public function testCancelsTheJob($jobid)
    {
        $jobAPI = new Job();

        $response = json_decode($jobAPI->cancel($jobid), true);
        $this->assertEquals('ok', $response['opstat']);
    } //end testCancelsTheJob()
} //end class
