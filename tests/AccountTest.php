<?php

require_once './init.php';

class AccountMethodsTest extends PHPUnit_Framework_TestCase
{
    private $key;
    private $secret;

    public function setUp()
    {
        $this->key = getenv('GENGO_PUBKEY');
        $this->secret = getenv('GENGO_PRIVKEY');
    }

    public function test_get_balance()
    {
        $service = Gengo_Api::factory('account', $this->key, $this->secret);
        $service->setBaseUrl('http://sandbox.gengo.com/v2/');

        $service->getBalance();
        $body = $service->getResponseBody();
        $response = json_decode($body, true);
        $this->assertEquals($response['opstat'], 'ok');
        $this->assertTrue(isset($response['response']));
        $this->assertTrue(isset($response['response']['credits']));

    }

    public function test_get_stats()
    {
        $service = Gengo_Api::factory('account', $this->key, $this->secret);
        $service->setBaseUrl('http://sandbox.gengo.com/v2/');

        $service->getStats();
        $body = $service->getResponseBody();
        $response = json_decode($body, true);
        $this->assertEquals($response['opstat'], 'ok');
        $this->assertTrue(isset($response['response']));
        $this->assertTrue(isset($response['response']['credits_spent']));
        $this->assertTrue(isset($response['response']['user_since']));

    }
}
