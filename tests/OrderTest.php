<?php

require_once './init.php';

class OrderTest extends PHPUnit_Framework_TestCase
{
    private $key;
    private $secret;

    public function setUp()
    {
        $this->key = getenv('GENGO_PUBKEY');
        $this->secret = getenv('GENGO_PRIVKEY');
    }

    public function test_create_delete_order()
    {
        $account_client = Gengo_Api::factory('account', $this->key, $this->secret);
        $account_client->setBaseUrl('http://api.gengo.dev/');
        $account_client->getBalance();
        $body = $account_client->getResponseBody();
        $response = json_decode($body, TRUE);
        $this->assertEquals($response['opstat'], 'ok');
        $this->assertTrue(isset($response['response']));
        $this->assertTrue(isset($response['response']['credits']));

        $start_credits = floatval($response['response']['credits']);

        $job1 = array(
          'type' => 'text',
          'slug' => 'API Liverpool 1',
          'body_src' => 'Liverpool_1 Football Club is an English Premier League football club based in Liverpool, Merseyside.',
          'lc_src' => 'en',
          'lc_tgt' => 'ja',
          'tier' => 'standard',
          'force' => 1,
        );
        $job2 = array(
          'type' => 'text',
          'slug' => 'API Manchester United',
          'body_src' => 'Manchester United Football Club is an English Premier League football club based in Old Trafford, Greater Manchester.',
          'lc_src' => 'en',
          'lc_tgt' => 'ja',
          'tier' => 'standard',
          'force' => 1,
        );

        $jobs = array($job1, $job2);

        // Get an instance of Jobs Client
        $jobs_client = Gengo_Api::factory('jobs', $this->key, $this->secret);
        $jobs_client->setBaseUrl('http://api.gengo.dev/');

        $jobs_client->postJobs($jobs);
        // make sure order is processed by the jobs processor
        sleep(10);

        // get the server response.
        $body = $jobs_client->getResponseBody();
        $response = json_decode($body, true);
        $this->assertEquals($response['opstat'], 'ok');
        $this->assertTrue(isset($response['response']));
        $this->assertTrue(isset($response['response']['credits_used']));
        // get the order id
        $order_id = $response['response']['order_id'];

        $used_credits = floatval($response['response']['credits_used']);
        $current_credits = floatval($start_credits - $used_credits);

        // get the balance again
        $account_client->getBalance();
        $body = $account_client->getResponseBody();
        $response = json_decode($body, TRUE);
        $this->assertEquals($response['opstat'], 'ok');
        $this->assertTrue(isset($response['response']));
        $this->assertTrue(isset($response['response']['credits']));

        // check current credits is equal to retreived balance
        $this->assertEquals($current_credits, floatval($response['response']['credits']));

        // cancel the order
        $order_client = Gengo_Api::factory('order', $this->key, $this->secret);
        $order_client->setBaseUrl('http://api.gengo.dev/');
        $order_client->cancel($order_id);

        // get the balance again and check that is equal to the start_credits
        $account_client->getBalance();
        $body = $account_client->getResponseBody();
        $response = json_decode($body, TRUE);
        $this->assertEquals($response['opstat'], 'ok');
        $this->assertTrue(isset($response['response']));
        $this->assertTrue(isset($response['response']['credits']));
        // check current credits is equal to retreived balance
        $this->assertEquals($start_credits, floatval($response['response']['credits']));
    }
}
