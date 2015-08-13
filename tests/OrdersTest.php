<?php

require_once './init.php';

class OrderCommentTest extends PHPUnit_Framework_TestCase
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
                'body_src' => 'This job is test for Comment functionarity on Order.',
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

        $test_bag['order_id'] = $response['response']['order_id'];

        sleep(10);
        return $test_bag;
    }

    /**
     * @depends test_post_jobs
     */
    public function test_post_translation_order_comment($test_bag)
    {
        $order_client = Gengo_Api::factory('order', $this->key, $this->secret);
        $test_bag['comment_text'] = "this is a comment on Order.";
        $order_client->postComment($test_bag['order_id'], $test_bag['comment_text']);
        $body = $order_client->getResponseBody();
        $response = json_decode($body, true);
        $this->assertEquals($response['opstat'], 'ok');
        $this->assertTrue(isset($response['response']));

        return $test_bag;
    }

    /**
     * @depends test_post_translation_order_comment
     */
    public function test_get_translation_order_comment($test_bag)
    {
        $order_client = Gengo_Api::factory('order', $this->key, $this->secret);
        $order_client->getComment($test_bag['order_id']);
        $body = $order_client->getResponseBody();
        $response = json_decode($body, true);
        $this->assertEquals($response['opstat'], 'ok');
        $this->assertTrue(isset($response['response']));
        $this->assertEquals($response['response']['thread'][0]['body'], $test_bag['comment_text']);
    }

}
