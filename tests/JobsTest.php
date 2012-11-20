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
        $job_client = Gengo_Api::factory('jobs', $this->key, $this->secret);
        $job_client->setBaseUrl('http://sandbox.gengo.com/v2/');

        // Post the jobs. The second parameter is optional and determines whether or
        // not the jobs are submitted as a group (default: false).
        $job_client->postJobs($jobs);

        // Display the server response.
        $job_client->getResponseBody();
        $body = $job_client->getResponseBody();
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
        $job_client = Gengo_Api::factory('order', $this->key, $this->secret);
        $job_client->setBaseUrl('http://sandbox.gengo.com/v2/');
        sleep(10);
        $job_client->getOrder($order_id);
        $job_client->getResponseBody();
        $body = $job_client->getResponseBody();
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
        $job_client->setBaseUrl('http://sandbox.gengo.com/v2/');
        $job_client->getJob($job_id);
        $job_client->getResponseBody();
        $body = $job_client->getResponseBody();
        $response = json_decode($body, true);
        $this->assertEquals($response['opstat'], 'ok');
        $this->assertTrue(isset($response['response']));
        $this->assertEquals($response['response']['job']['body_src'], 'Liverpool_1 Football Club is an English Premier League football club based in Liverpool, Merseyside.');
    }
}
