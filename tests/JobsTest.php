<?php

require_once './init.php';

class PostJobsTest extends PHPUnit_Framework_TestCase
{
    private $key;
    private $secret;

    public function setUp()
    {
        $this->key = getenv('GENGO_PUBKEY');
        $this->secret = getenv('GENGO_PRIVKEY');
    }

    public function test_post_jobs()
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

        // Get an instance of Jobs Client
        $jobs_client = Gengo_Api::factory('jobs', $this->key, $this->secret);

        // Post the jobs. The second parameter is optional and determines whether or
        // not the jobs are submitted as a group (default: false).
        $jobs_client->postJobs($jobs);

        // Display the server response.
        $body = $jobs_client->getResponseBody();
        $response = json_decode($body, true);
        $this->assertEquals($response['opstat'], 'ok');
        $this->assertTrue(isset($response['response']));
        $order_id = $response['response']['order_id'];

        return $order_id;
    }

    /**
     * @depends test_post_jobs
     */
    public function test_get_translation_order_jobs($order_id)
    {
        $order_client = Gengo_Api::factory('order', $this->key, $this->secret);
        sleep(10);
        $order_client->getOrder($order_id);
        $body = $order_client->getResponseBody();
        $response = json_decode($body, true);
        $this->assertEquals($response['opstat'], 'ok');
        $this->assertTrue(isset($response['response']));
        $job_id = $response['response']['order']['jobs_available'][0];

        return $job_id;
    }

    /**
     * @depends test_get_translation_order_jobs
     */
    public function test_get_translation_job($job_id)
    {
        $job_client = Gengo_Api::factory('job', $this->key, $this->secret);
        $job_client->getJob($job_id);
        $body = $job_client->getResponseBody();
        $response = json_decode($body, true);
        $this->assertEquals($response['opstat'], 'ok');
        $this->assertTrue(isset($response['response']));
        $this->assertEquals($response['response']['job']['body_src'], 'Liverpool_1 Football Club is an English Premier League football club based in Liverpool, Merseyside.');
        return $job_id;
    }

    /**
     * @depends test_get_translation_order_jobs
     */
    public function test_post_translation_job_comment($job_id)
    {
        $job_client = Gengo_Api::factory('job', $this->key, $this->secret);
        $comment = 'Test comment';
        $job_client->postComment($job_id, $comment);
        $body = $job_client->getResponseBody();
        $response = json_decode($body, true);
        $this->assertEquals($response['opstat'], 'ok');
        $this->assertTrue(isset($response['response']));

        return $job_id;
    }

    /**
     * @depends test_get_translation_order_jobs
     */
    public function test_get_translation_job_comments($job_id)
    {
        $job_client = Gengo_Api::factory('job', $this->key, $this->secret);
        $job_client->getComments($job_id);
        $body = $job_client->getResponseBody();
        $response = json_decode($body, true);
        $this->assertEquals($response['opstat'], 'ok');
        $this->assertTrue(isset($response['response']));
        $this->assertEquals($response['response']['thread'][0]['body'], 'Test comment');
    }

    public function test_quote()
    {
        $jobs = array();
        $files = array();
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

        $files['file_01'] = 'examples/testfiles/test_file1.txt';
        $files['file_02'] = 'examples/testfiles/test_file2.txt';

        $jobs = array('job_01' => $job1, 'job_02' => $job2);

        $service = Gengo_Api::factory('service', $this->key, $this->secret);

        $service->quote($jobs, $files);

        $service->getResponseBody();
        $body = $service->getResponseBody();
        $response = json_decode($body, true);

        $this->assertEquals($response['opstat'], 'ok');
        $this->assertTrue(isset($response['response']));

        $job1_identifier = $response['response']['jobs']['job_01']['identifier'];
        $job2_identifier = $response['response']['jobs']['job_02']['identifier'];

        return array($job1_identifier, $job2_identifier);

    }

    /**
     * @depends test_quote
     */
    public function test_file_upload($identifiers)
    {
        $job1 = array('type' => 'file',
                      'identifier' => $identifiers[0],
                      'comment' => "Test comment",
                      'force' => true,);

        $job2 = array('type' => 'file',
                      'identifier' => $identifiers[1],
                      'comment' => "Test comment",
                      'force' => true,);

        $jobs_client = Gengo_Api::factory('jobs', $this->key, $this->secret);
        $jobs = array('filejob_01' => $job1, 'filejob_02' => $job2);
        $jobs_client->postJobs($jobs);

        $body = $jobs_client->getResponseBody();
        $response = json_decode($body, true);
        $this->assertEquals($response['opstat'], 'ok');
        $this->assertTrue(isset($response['response']));
        $this->assertTrue(isset($response['response']['order_id']));
        $this->assertTrue(isset($response['response']['credits_used']));
        $this->assertEquals($response['response']['job_count'], 2);
    }
}
