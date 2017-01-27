<?php

/**
 * PHP version 5.6.
 *
 * @package Gengo\Tests
 */

namespace Gengo\Tests;

use Gengo\Config;
use Gengo\Jobs;
use Gengo\Service;
use PHPUnit_Framework_TestCase;

/**
 * Jobs class tests.
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
class JobsTest extends PHPUnit_Framework_TestCase
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
     */
    public function testAllowsToPostNewOrderWithNewJobs()
    {
        $jobsAPI = new Jobs();

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
        $jobs['tone'] = 'friendly';
        $jobs['purpose'] = 'Blog Post';

        $response = json_decode($jobsAPI->postJobs($jobs), true);
        $this->assertEquals('ok', $response['opstat']);
        $this->assertTrue(isset($response['response']['order_id']));

        $job1 = array(
             'type' => 'text',
             'slug' => 'API Job test',
             'body_src' => 'First test.',
             'comment' => 'Some awsome comment here.',
             'url_attachments' => array(
                           array(
                        'url' => 'https://gengo.github.io/style-guide/assets/images/logos/gengo_logo_circle_512.png',
                        'filename' => 'gengo_logo_circle_512.png',
                        'mime_type' => 'image/png',
                           ),
                          ),
             'lc_src' => 'en',
             'lc_tgt' => 'ja',
             'tier' => 'standard',
             'force' => 1,
             'auto_approve' => 1,
             'custom_data' => '1234567日本語',
            );

        $job2 = array(
             'type' => 'text',
             'slug' => 'API Job test',
             'body_src' => 'second test.',
             'lc_src' => 'en',
             'lc_tgt' => 'ja',
             'tier' => 'standard',
             'force' => 1,
             'auto_approve' => 1,
             'custom_data' => '1234567日本語',
            );

        $jobs = array(
             $job1,
             $job2,
            );

        $response = json_decode($jobsAPI->postJobs($jobs), true);
        $this->assertEquals('ok', $response['opstat']);
        $this->assertTrue(isset($response['response']['order_id']));
    } //end testAllowsToPostNewOrderWithNewJobs()

    /**
     * Test validation of url_attachments.
     *
     *
     * @expectedException        \Exception
     * @expectedExceptionMessage URL attachment must be an array
     */
    public function testChecksVaildityOfUrlAttachments()
    {
        $jobsAPI = new Jobs();

        $job1 = array(
             'type' => 'text',
             'slug' => 'API Job test',
             'body_src' => 'First test.',
             'comment' => 'Some awsome comment here.',
             'url_attachments' => array('bad attachment'),
             'lc_src' => 'en',
             'lc_tgt' => 'ja',
             'tier' => 'standard',
             'force' => 1,
             'auto_approve' => 1,
             'custom_data' => '1234567日本語',
            );

        $jobs = array($job1);

        $jobsAPI->postJobs($jobs);
    } //end testChecksVaildityOfUrlAttachments()

    /**
     * Test that url_attachments must point to proper http(s) resource.
     *
     *
     * @expectedException        \Exception
     * @expectedExceptionMessage URL attachment must point to public URL with http(s) scheme
     */
    public function testRequiresUrlAttachmentsToPointToHttpResource()
    {
        $jobsAPI = new Jobs();

        $job1 = array(
             'type' => 'text',
             'slug' => 'API Job test',
             'body_src' => 'First test.',
             'comment' => 'Some awsome comment here.',
             'url_attachments' => array(
                           array(
                        'url' => 'ftp://gengo.github.io/style-guide/assets/images/logos/gengo_logo_circle_512.png',
                        'filename' => 'gengo_logo_circle_512.png',
                        'mime_type' => 'image/png',
                           ),
                          ),
             'lc_src' => 'en',
             'lc_tgt' => 'ja',
             'tier' => 'standard',
             'force' => 1,
             'auto_approve' => 1,
             'custom_data' => '1234567日本語',
            );

        $jobs = array($job1);

        $jobsAPI->postJobs($jobs);
    } //end testRequiresUrlAttachmentsToPointToHttpResource()

    /**
     * Test that url_attachments must have filename.
     *
     *
     * @expectedException        \Exception
     * @expectedExceptionMessage URL attachment filename must be specified
     */
    public function testRequiresUrlAttachmentsToHaveFileName()
    {
        $jobsAPI = new Jobs();

        $job1 = array(
             'type' => 'text',
             'slug' => 'API Job test',
             'body_src' => 'First test.',
             'comment' => 'Some awsome comment here.',
             'url_attachments' => array(
                           array(
                        'url' => 'http://gengo.github.io/style-guide/assets/images/logos/gengo_logo_circle_512.png',
                        'filename' => '',
                        'mime_type' => 'image/png',
                           ),
                          ),
             'lc_src' => 'en',
             'lc_tgt' => 'ja',
             'tier' => 'standard',
             'force' => 1,
             'auto_approve' => 1,
             'custom_data' => '1234567日本語',
            );

        $jobs = array($job1);

        $jobsAPI->postJobs($jobs);
    } //end testRequiresUrlAttachmentsToHaveFileName()

    /**
     * Test that url_attachments must have MIME type.
     *
     *
     * @expectedException        \Exception
     * @expectedExceptionMessage URL attachment MIME type must be specified
     */
    public function testRequiresUrlAttachmentsToHaveMimeType()
    {
        $jobsAPI = new Jobs();

        $job1 = array(
             'type' => 'text',
             'slug' => 'API Job test',
             'body_src' => 'First test.',
             'comment' => 'Some awsome comment here.',
             'url_attachments' => array(
                           array(
                        'url' => 'http://gengo.github.io/style-guide/assets/images/logos/gengo_logo_circle_512.png',
                        'filename' => 'gengo_logo_circle_512.png',
                        'mime_type' => '',
                           ),
                          ),
             'lc_src' => 'en',
             'lc_tgt' => 'ja',
             'tier' => 'standard',
             'force' => 1,
             'auto_approve' => 1,
             'custom_data' => '1234567日本語',
            );

        $jobs = array($job1);

        $jobsAPI->postJobs($jobs);
    } //end testRequiresUrlAttachmentsToHaveMimeType()

    /**
     * Test retrieval of a list of resources for the most recent jobs filtered by given parameters.
     *
     * @return array Job IDs
     */
    public function testRetrievesAListOfResourcesForTheMostRecentJobsFilteredByTheGivenParameters()
    {
        $jobsAPI = new Jobs();

        $response = json_decode($jobsAPI->getJobs('available', 0, 10), true);
        $this->assertEquals('ok', $response['opstat']);
        $this->assertTrue(isset($response['response']));
        $this->assertTrue(is_array($response['response']));

        $jobids = array();
        foreach ($response['response'] as $job) {
            $jobids[] = $job['job_id'];
        }

        return $jobids;
    } //end testRetrievesAListOfResourcesForTheMostRecentJobsFilteredByTheGivenParameters()

    /**
     * Test refusal to retrieve a list of resources for the most recent job if wrong status is provided.
     *
     *
     * @expectedException        \Exception
     * @expectedExceptionMessage "status" must contain a valid status
     */
    public function testRefusesToRetrieveAListOfResourcesForTheMostRecentJobsIfWrongStatusIsProvided()
    {
        $jobsAPI = new Jobs();

        $jobsAPI->getJobs('wrong_status', 0, 10);
    } //end testRefusesToRetrieveAListOfResourcesForTheMostRecentJobsIfWrongStatusIsProvided()

    /**
     * Test refusal to retrieve a list of resources for the most recent job if wrong time stamp is provided.
     *
     *
     * @expectedException        \Exception
     * @expectedExceptionMessage "timestampafter" must be non-negative integer
     */
    public function testRefusesToRetrieveAListOfResourcesForTheMostRecentJobsIfWrongTimeStampIsProvided()
    {
        $jobsAPI = new Jobs();

        $jobsAPI->getJobs('available', -100, 10);
    } //end testRefusesToRetrieveAListOfResourcesForTheMostRecentJobsIfWrongTimeStampIsProvided()

    /**
     * Test refusal to retrieve a list of resources for the most recent job if wrong count requested.
     *
     *
     * @expectedException        \Exception
     * @expectedExceptionMessage "count" must be integer in range between 1 and 200
     */
    public function testRefusesToRetrieveAListOfResourcesForTheMostRecentJobsIfWrongCountRequested()
    {
        $jobsAPI = new Jobs();

        $jobsAPI->getJobs('available', 0, 0);
    } //end testRefusesToRetrieveAListOfResourcesForTheMostRecentJobsIfWrongCountRequested()

    /**
     * Test request of quotation for translations.
     *
     * @return array Job identifiers
     */
    public function testAllowsToRequestAQuotationForTranslations()
    {
        $job1 = array(
             'type' => 'file',
             'file_key' => 'file_01',
             'lc_src' => 'en',
             'lc_tgt' => 'ja',
             'tier' => 'standard',
            );

        $job2 = array(
             'type' => 'file',
             'file_key' => 'file_02',
             'lc_src' => 'en',
             'lc_tgt' => 'ja',
             'tier' => 'standard',
            );

        $jobs = array(
             'job_01' => $job1,
             'job_02' => $job2,
            );

        $files = array(
              'file_01' => __DIR__.'/testfiles/test_file1.txt',
              'file_02' => __DIR__.'/testfiles/test_file2.txt',
             );

        $serviceAPI = new Service();

        $response = json_decode($serviceAPI->quote($jobs, $files), true);
        $this->assertEquals('ok', $response['opstat']);
        $this->assertTrue(isset($response['response']));

        return array(
            $response['response']['jobs']['job_01']['identifier'],
            $response['response']['jobs']['job_02']['identifier'],
               );
    } //end testAllowsToRequestAQuotationForTranslations()

    /**
     * Test submission of a job or group of jobs for translation.
     *
     * @param array $identifiers Job identifiers
     *
     *
     * @depends testAllowsToRequestAQuotationForTranslations
     */
    public function testSubmitsAJobOrGroupOfJobsToTranslate(array $identifiers)
    {
        $job1 = array(
             'type' => 'file',
             'identifier' => $identifiers[0],
             'comment' => 'Test comment',
             'force' => true,
            );

        $job2 = array(
             'type' => 'file',
             'identifier' => $identifiers[1],
             'comment' => 'Test comment',
             'force' => true,
            );

        $jobs = array(
             'filejob_01' => $job1,
             'filejob_02' => $job2,
            );

        $jobsAPI = new Jobs();

        $response = json_decode($jobsAPI->postJobs($jobs, false), true);
        $this->assertEquals('ok', $response['opstat']);
        $this->assertTrue(isset($response['response']));
        $this->assertTrue(isset($response['response']['order_id']));
        $this->assertTrue(isset($response['response']['credits_used']));
        $this->assertEquals(2, $response['response']['job_count']);
    } //end testSubmitsAJobOrGroupOfJobsToTranslate()

    /**
     * Test retrieval of a list of jobs by a list of job IDs.
     *
     * @param array $jobs Job identifiers
     *
     * @return array Job IDs
     *
     * @depends testRetrievesAListOfResourcesForTheMostRecentJobsFilteredByTheGivenParameters
     */
    public function testRetrievesAListOfJobsByAListOfJobIds(array $jobs)
    {
        $jobsAPI = new Jobs();

        $response = json_decode($jobsAPI->getJobsByID($jobs), true);
        $this->assertEquals('ok', $response['opstat']);
        $this->assertTrue(isset($response['response']));
        $this->assertTrue(isset($response['response']['jobs']));
        $this->assertEquals(10, count($response['response']['jobs']));

        $jobids = array();
        foreach ($response['response']['jobs'] as $job) {
            if ($job['status'] === 'available') {
                $jobids[] = $job['job_id'];
            }
        }

        return $jobids;
    } //end testRetrievesAListOfJobsByAListOfJobIds()

    /**
     * Test return of a job back to the translator for revisions.
     *
     * For some unknown reason Gengo's sandbox on jobs revision returns the error:
     *
     *   invalid job status
     *
     * even when job is in "available" state. Our call made it to Gengo and so will assert against the error.
     * Of course it should be fixed on Gengo side. Hopefully this issue would not apply to production server.
     *
     * @param array $jobids Job identifiers
     *
     *
     * @depends testRetrievesAListOfJobsByAListOfJobIds
     */
    public function testReturnsAJobBackToTheTranslatorForRevisions(array $jobids)
    {
        $jobsAPI = new Jobs();

        $jobs = array(
             array(
              'job_id' => array_shift($jobids),
              'comment' => 'specific comment',
             ),
             array('job_id' => array_shift($jobids)),
            );

        $response = json_decode($jobsAPI->revise($jobs, 'test comment'), true);

        $this->assertEquals('error', $response['opstat']);
        $this->assertEquals('invalid job status', $response['err']['msg']);
    } //end testReturnsAJobBackToTheTranslatorForRevisions()

    /**
     * Test refusal to return of a job back to the translator for revisions without a comment.
     *
     * @param array $jobids Job identifiers
     *
     *
     * @expectedException        \Exception
     * @expectedExceptionMessage "comment" is required
     *
     * @depends testRetrievesAListOfJobsByAListOfJobIds
     */
    public function testRefusesToReturnAJobToTheTranslatorWithoutAComment(array $jobids)
    {
        $jobsAPI = new Jobs();

        $jobs = array(
             array('job_id' => array_shift($jobids)),
             array('job_id' => array_shift($jobids)),
            );

        $jobsAPI->revise($jobs, '');
    } //end testRefusesToReturnAJobToTheTranslatorWithoutAComment()

    /**
     * Test refusal to return of a job back to the translator for revisions if no job IDs are specified.
     *
     * @param array $jobids Job identifiers
     *
     *
     * @expectedException        \Exception
     * @expectedExceptionMessage All jobs require job_id field
     *
     * @depends testRetrievesAListOfJobsByAListOfJobIds
     */
    public function testRefusesToReturnAJobToTheTranslatorIfNoJobIdsAreSpecified(array $jobids)
    {
        $jobsAPI = new Jobs();

        $jobs = array(
             array('no_job_id' => array_shift($jobids)),
             array('job_id' => array_shift($jobids)),
            );

        $jobsAPI->revise($jobs, 'test comment');
    } //end testRefusesToReturnAJobToTheTranslatorIfNoJobIdsAreSpecified()

    /**
     * Test refusal to return of a job back to the translator for revisions if job IDs are invalid.
     *
     * @param array $jobids Job identifiers
     *
     *
     * @expectedException        \Exception
     * @expectedExceptionMessage Invalid job_id supplied
     *
     * @depends testRetrievesAListOfJobsByAListOfJobIds
     */
    public function testRefusesToReturnAJobToTheTranslatorIfJobIdsAreInvalid(array $jobids)
    {
        $jobsAPI = new Jobs();

        $jobs = array(
             array('job_id' => 'invalid job id'),
             array('job_id' => array_shift($jobids)),
            );

        $jobsAPI->revise($jobs, 'test comment');
    } //end testRefusesToReturnAJobToTheTranslatorIfJobIdsAreInvalid()

    /**
     * Test job approval.
     *
     * For some unknown reason Gengo's sandbox on jobs apptove returns the error:
     *
     *   invalid job status
     *
     * even when job is in "available" state. Our call made it to Gengo and so will assert against the error.
     * Of course it should be fixed on Gengo side. Hopefully this issue would not apply to production server.
     *
     * @param array $jobids Job identifiers
     *
     *
     * @depends testRetrievesAListOfJobsByAListOfJobIds
     */
    public function testApprovesJob(array $jobids)
    {
        $jobsAPI = new Jobs();

        $jobs = array(
             array(
              'job_id' => array_shift($jobids),
              'rating' => 4,
              'public' => true,
             ),
             array(
              'job_id' => array_shift($jobids),
             ),
            );

        $response = json_decode($jobsAPI->approve($jobs), true);
        $this->assertEquals('error', $response['opstat']);
        $this->assertEquals('invalid job status', $response['err']['msg']);
    } //end testApprovesJob()

    /**
     * Test rejection of jobs.
     *
     * For some unknown reason Gengo's sandbox on jobs rejection returns the error:
     *
     *   invalid job status
     *
     * even when job is in "available" state. Our call made it to Gengo and so will assert against the error.
     * Of course it should be fixed on Gengo side. Hopefully this issue would not apply to production server.
     *
     * @param array $jobids Job identifiers
     *
     *
     * @depends testRetrievesAListOfJobsByAListOfJobIds
     */
    public function testRejectsTheTranslation(array $jobids)
    {
        $jobsAPI = new Jobs();

        $jobs = array(
             array(
              'job_id' => array_shift($jobids),
              'reason' => 'other',
              'comment' => 'comment',
              'captcha' => 'captcha',
             ),
             array(
              'job_id' => array_shift($jobids),
              'reason' => 'other',
              'comment' => 'comment',
              'captcha' => 'captcha',
              'follow_up' => 'cancel',
             ),
            );

        $response = json_decode($jobsAPI->reject($jobs), true);
        $this->assertEquals('error', $response['opstat']);
        $this->assertEquals('invalid job status', $response['err']['msg']);
    } //end testRejectsTheTranslation()

    /**
     * Test archive jobs.
     *
     * For some unknown reason Gengo's sandbox on jobs archive returns the error:
     *
     *   unauthorized job access
     *
     * even when job is in "available" state. Our call made it to Gengo and so will assert against the error.
     * Of course it should be fixed on Gengo side. Hopefully this issue would not apply to production server.
     *
     * @param array $jobids Job identifiers
     *
     *
     * @depends testRetrievesAListOfJobsByAListOfJobIds
     */
    public function testArchiveApprovedJob(array $jobids)
    {
        $jobsAPI = new Jobs();

        $jobs = array(
             array(
              'job_id' => array_shift($jobids),
             ),
             array(
              'job_id' => array_shift($jobids),
             ),
            );

        $response = json_decode($jobsAPI->archive($jobs), true);
        $this->assertEquals('error', $response['opstat']);
        $this->assertEquals('unauthorized job access', $response['err']['msg']);
    } //end testArchiveApprovedJob()

    /**
     * Test jobs with reference_id.
     *
     * @param  array
     * @param  bool
     * @dataProvider jobWithReferenceProvider
     */
    public function testJobWithReferenceId($data, $valid)
    {
        $jobsAPI = new Jobs();
        $response = json_decode($jobsAPI->postJobs($data), true);

        if (true === $valid) {
            $this->assertEquals('ok', $response['opstat']);
            $this->assertTrue(isset($response['response']['order_id']));
        } else {
            $this->assertEquals('error', $response['opstat']);
            $this->assertEquals($response['err']['msg'], '"reference_id" must be an integer greater or equal 0');
        }
    } //end testJobWithReferenceId()

    /**
     * Data provider for testJobWithReferenceId.
     *
     * @return array
     */
    public function jobWithReferenceProvider()
    {
        return array(
            // invalid reference_id
            array(
                array(
                    'job_01' => array(
                        'type' => 'text',
                        'slug' => 'API Liverpool 1',
                        'body_src' => 'Liverpool_1 Football Club is an English Premier League football club based in Liverpool, Merseyside.',
                        'lc_src' => 'en',
                        'lc_tgt' => 'ja',
                        'tier' => 'standard',
                        'force' => 1,
                    ),
                    'tone' => 'friendly',
                    'purpose' => 'Blog Post',
                    'reference_id' => 'gengo',
                ),
                false,
            ),
            // valid reference_id
            array(
                array(
                    'job_01' => array(
                        'type' => 'text',
                        'slug' => 'API Liverpool 1',
                        'body_src' => 'Liverpool_1 Football Club is an English Premier League football club based in Liverpool, Merseyside.',
                        'lc_src' => 'en',
                        'lc_tgt' => 'ja',
                        'tier' => 'standard',
                        'force' => 1,
                    ),
                    'tone' => 'friendly',
                    'purpose' => 'Blog Post',
                    'reference_id' => '1234',
                ),
                true,
            ),
        );
    } //end jobWithReferenceProvider()
} //end class
