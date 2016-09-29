<?php

/**
 * PHP version 5.6.
 */

namespace Gengo\Tests;

use Gengo\Account;
use Gengo\Config;
use PHPUnit_Framework_TestCase;

/**
 * Accounts class tests.
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
class AccountTest extends PHPUnit_Framework_TestCase
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
     * Test retrieval of account stats.
     */
    public function testRetrievesAccountStatsSuchAsOrdersMade()
    {
        $accountAPI = new Account();

        $response = json_decode($accountAPI->getStats(), true);
        $this->assertEquals('ok', $response['opstat']);
        $this->assertTrue(isset($response['response']));
        $this->assertTrue(isset($response['response']['credits_spent']));
        $this->assertTrue(isset($response['response']['user_since']));
    } //end testRetrievesAccountStatsSuchAsOrdersMade()

    /**
     * Test retrieval of account balance.
     */
    public function testRetrievesAccountBalanceInCredits()
    {
        $accountAPI = new Account();

        $response = json_decode($accountAPI->getBalance(), true);
        $this->assertEquals('ok', $response['opstat']);
        $this->assertTrue(isset($response['response']));
        $this->assertTrue(isset($response['response']['credits']));
    } //end testRetrievesAccountBalanceInCredits()

    /**
     * Test retrieval of preferred translators set by user.
     */
    public function testRetrievesPreferredTranslatorsSetByUser()
    {
        $accountAPI = new Account();

        $response = json_decode($accountAPI->getPreferredTranslators(), true);
        $this->assertEquals('ok', $response['opstat']);
        $this->assertTrue(isset($response['response']));
        $this->assertTrue(empty($response['response']));
    } //end testRetrievesPreferredTranslatorsSetByUser()

    /**
     * Test retrieval of authenticated user details.
     */
    public function testRetrievesAuthenticateUserDetails()
    {
        $accountAPI = new Account();

        $response = json_decode($accountAPI->getMe(), true);
        $this->assertEquals('ok', $response['opstat']);
        $this->assertTrue(isset($response['response']));
        $this->assertTrue(isset($response['response']['email']));
        $this->assertTrue(array_key_exists('full_name', $response['response']));
        $this->assertTrue(array_key_exists('display_name', $response['response']));
        $this->assertTrue(array_key_exists('language_code', $response['response']));
    } //end testRetrievesAuthenticateUserDetails()
} //end class
