<?php

/**
 * PHP version 5.6.
 *
 * @package Gengo\Tests
 */

namespace Gengo\Tests;

use Exception;
use Gengo\Account;
use Gengo\Config;
use PHPUnit_Framework_TestCase;

/**
 * API class tests.
 *
 * As API class is abstract we will use Account class to perform the testing
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
class APITest extends PHPUnit_Framework_TestCase
{
    /**
     * Test ability to retrieve response body, response code and response headers.
     *
     *
     * @internalconst GENGO_PUBKEY  "pubkeyfortests"                               Gengo test public key
     * @internalconst GENGO_PRIVKEY "privatekeyfortestuserthatcontainsonlyletters" Gengo test private key
     */
    public function testProvidesResponseBodyAlongWithResponseCodeAndResponseHeaders()
    {
        Config::setAPIkey(GENGO_PUBKEY);
        Config::setPrivateKey(GENGO_PRIVKEY);

        $accountAPI = new Account();

        $accountAPI->getBalance();

        $response = json_decode($accountAPI->getResponseBody(), true);
        $this->assertTrue(is_array($response));

        $code = $accountAPI->getResponseCode();
        $this->assertEquals(200, $code);

        $header = $accountAPI->getResponseHeaders();
        $this->assertEquals('application/json', $header['Content-Type'][0]);
    } //end testProvidesResponseBodyAlongWithResponseCodeAndResponseHeaders()

    /**
     * Test exception if no API key is set.
     */
    public function testThrowsExceptionIfNoApiKeyIsSet()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('No API key is set');
        $accountAPI = new Account();
        unset($accountAPI);
    } //end testThrowsExceptionIfNoApiKeyIsSet()

    /**
     * Test exception if no private key is set.
     *
     *
     * @internalconst GENGO_PUBKEY "pubkeyfortests" Gengo test public key
     */
    public function testThrowsExceptionIfNoPrivateKeyIsSet()
    {
        Config::setAPIkey(GENGO_PUBKEY);
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('No private key is set');
        $accountAPI = new Account();
        unset($accountAPI);
    } //end testThrowsExceptionIfNoPrivateKeyIsSet()

    /**
     * Test exception on attempt to retrieve a response before making a request.
     *
     *
     * @internalconst GENGO_PUBKEY  "pubkeyfortests"                               Gengo test public key
     * @internalconst GENGO_PRIVKEY "privatekeyfortestuserthatcontainsonlyletters" Gengo test private key
     */
    public function testThrowsExceptionOnAttemptToRetrieveAResponseBeforeMakingARequest()
    {
        Config::setAPIkey(GENGO_PUBKEY);
        Config::setPrivateKey(GENGO_PRIVKEY);

        $accountAPI = new Account();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('A valid response is not yet available, please make a request first');
        $body = $accountAPI->getResponseBody();
        unset($body);
    } //end testThrowsExceptionOnAttemptToRetrieveAResponseBeforeMakingARequest()
} //end class
