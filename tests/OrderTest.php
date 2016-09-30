<?php

/**
 * PHP version 5.6.
 *
 * @package Gengo\Tests
 */

namespace Gengo\Tests;

use Gengo\Config;
use Gengo\Jobs;
use Gengo\Order;
use PHPUnit_Framework_TestCase;

/**
 * Order class tests.
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
 * @runTestsInSeparateProcesses
 *
 * @donottranslate
 */
class OrderTest extends PHPUnit_Framework_TestCase
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
     * Test posting of new jobs.
     *
     * @return int Order ID
     */
    public function testAllowsToPostNewJobs()
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

        return $response['response']['order_id'];
    } //end testAllowsToPostNewJobs()

    /**
     * Test retrieval of a group of jobs that were previously submitted together by their order ID.
     *
     * @param int $orderid Order ID
     *
     *
     * @depends testAllowsToPostNewJobs
     */
    public function testRetrievesAGroupOfJobsThatWerePreviouslySubmittedTogetherByTheirOrderId($orderid)
    {
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
    } //end testRetrievesAGroupOfJobsThatWerePreviouslySubmittedTogetherByTheirOrderId()

    /**
     * Test submission of a new comment to the order's comment thread.
     *
     * @param int $orderid Order ID
     *
     *
     * @depends testAllowsToPostNewJobs
     */
    public function testSubmitsANewCommentToTheOrdersCommentThread($orderid)
    {
        $orderAPI = new Order();

        $response = json_decode($orderAPI->postComment($orderid, 'test comment'), true);
        $this->assertEquals('ok', $response['opstat']);
    } //end testSubmitsANewCommentToTheOrdersCommentThread()

    /**
     * Test refusal to post empty comment to order comment thread.
     *
     * @param int $orderid Order ID
     *
     *
     * @expectedException        Exception
     * @expectedExceptionMessage must contain a valid "body" parameter as the comment
     *
     * @depends testAllowsToPostNewJobs
     */
    public function testRefusesToPostEmptyCommentToOrderCommentThread($orderid)
    {
        $orderAPI = new Order();

        $orderAPI->postComment($orderid, '');
    } //end testRefusesToPostEmptyCommentToOrderCommentThread()

    /**
     * Test retrieval of the comment thread for an order.
     *
     * @param int $orderid Order ID
     *
     *
     * @depends testAllowsToPostNewJobs
     */
    public function testRetrievesTheComentThreadForAnOrder($orderid)
    {
        $orderAPI = new Order();

        $response = json_decode($orderAPI->getComments($orderid), true);
        $this->assertEquals('ok', $response['opstat']);
        $this->assertTrue(isset($response['response']['thread'][0]['body']));
        $this->assertEquals('test comment', $response['response']['thread'][0]['body']);
    } //end testRetrievesTheComentThreadForAnOrder()

    /**
     * Test cancellation of all jobs in an order.
     *
     * @param int $orderid Order ID
     *
     *
     * @depends testAllowsToPostNewJobs
     */
    public function testCancelsAllJobsInAnOrder($orderid)
    {
        $orderAPI = new Order();

        $response = json_decode($orderAPI->getOrder($orderid), true);
        $this->assertEquals('ok', $response['opstat']);
        $this->assertTrue(isset($response['response']['order']['total_jobs']));
        $this->assertEquals(1, $response['response']['order']['total_jobs']);

        $response = json_decode($orderAPI->cancel($orderid), true);
        $this->assertEquals('ok', $response['opstat']);
        $this->assertTrue(isset($response['response']));

        $response = json_decode($orderAPI->getOrder($orderid), true);
        $this->assertEquals('ok', $response['opstat']);
        $this->assertTrue(isset($response['response']['order']['total_jobs']));
        $this->assertEquals(0, $response['response']['order']['total_jobs']);
    } //end testCancelsAllJobsInAnOrder()
} //end class
