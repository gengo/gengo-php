<?php

/**
 * PHP version 5.6.
 *
 * @package Gengo\Tests
 */

namespace Gengo\Tests;

use Exception;
use Gengo\Config;
use Gengo\Job;
use PHPUnit_Framework_TestCase;

/**
 * Config class tests.
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
class ConfigTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test production API.
     *
     * Error is expected as we do not have production keys
     *
     *
     * @internalconst GENGO_PUBKEY  "pubkeyfortests"                               Gengo test public key
     * @internalconst GENGO_PRIVKEY "privatekeyfortestuserthatcontainsonlyletters" Gengo test private key
     */
    public function testProvidesEasyWayToSwitchToGengoProductionServer()
    {
        Config::useProduction();
        Config::setAPIkey(GENGO_PUBKEY);
        Config::setPrivateKey(GENGO_PRIVKEY);

        $jobAPI = new Job();

        $response = json_decode($jobAPI->getJob(1), true);
        $this->assertEquals('error', $response['opstat']);
        $this->assertTrue(isset($response['err']['msg']));
        $this->assertEquals('Authentication failed', $response['err']['msg']);
    } //end testProvidesEasyWayToSwitchToGengoProductionServer()

    /**
     * Test different response formats.
     *
     *
     * @internalconst GENGO_PUBKEY  "pubkeyfortests"                               Gengo test public key
     * @internalconst GENGO_PRIVKEY "privatekeyfortestuserthatcontainsonlyletters" Gengo test private key
     */
    public function testAllowsSelectionOfResponseFormatAsJsonOrXml()
    {
        Config::setAPIkey(GENGO_PUBKEY);
        Config::setPrivateKey(GENGO_PRIVKEY);

        $jobAPI = new Job();

        Config::setResponseFormat('json');
        $response = json_decode($jobAPI->getJob(1), true);
        $this->assertTrue(is_array($response));

        Config::setResponseFormat('xml');
        libxml_use_internal_errors(true);
        $this->assertTrue(simplexml_load_string($jobAPI->getJob(1)) !== false);
        libxml_use_internal_errors(false);

        $this->expectException(Exception::CLASS);
        $this->expectExceptionMessage('Invalid response format wrong_format, accepted formats are: xml or json');
        Config::setResponseFormat('wrong_format');
    } //end testAllowsSelectionOfResponseFormatAsJsonOrXml()

    /**
     * Test exception on attempt to set wrong API key.
     */
    public function testRefusesToAcceptWrongApiKey()
    {
        $this->expectException(Exception::CLASS);
        $this->expectExceptionMessage('Invalid API key');
        Config::setAPIkey(123);
    } //end testRefusesToAcceptWrongApiKey()

    /**
     * Test exception on attempt to set wrong private key.
     */
    public function testRefusesToAcceptWrongPrivateKey()
    {
        $this->expectException(Exception::CLASS);
        $this->expectExceptionMessage('Invalid private key');
        Config::setPrivateKey(123);
    } //end testRefusesToAcceptWrongPrivateKey()

    /**
     * Test preconfiguration of job and revision IDs.
     *
     * Gengo's sandbox is broken for this call. It returns following response:
     *
     *  {"opstat":"error","err":{"msg":"Internal Server Error","code":500}}
     *  {"opstat":"error","err":{"msg":"Internal Server Error","code":500}}
     *  {"opstat":"error","err":{"code":2200,"msg":"unauthorized revision access"}}
     *
     * Clearly it is broken JSON and therefore we will assert against string only.
     *
     *
     * @internalconst GENGO_PUBKEY  "pubkeyfortests"                               Gengo test public key
     * @internalconst GENGO_PRIVKEY "privatekeyfortestuserthatcontainsonlyletters" Gengo test private key
     */
    public function testAllowsToPreconfigureJobAndRevisionIdsForUseWithSubsequentJobApiCalls()
    {
        Config::setAPIkey(GENGO_PUBKEY);
        Config::setPrivateKey(GENGO_PRIVKEY);
        Config::setJobID(1);
        Config::setRevisionID(1);

        $jobAPI = new Job();

        $this->assertContains('unauthorized job access', $jobAPI->getRevision());
    } //end testAllowsToPreconfigureJobAndRevisionIdsForUseWithSubsequentJobApiCalls()

    /**
     * Test that exception is thrown if no job and revision IDs are preconfigured and job API call is made without job/revision ID.
     *
     *
     * @internalconst GENGO_PUBKEY  "pubkeyfortests"                               Gengo test public key
     * @internalconst GENGO_PRIVKEY "privatekeyfortestuserthatcontainsonlyletters" Gengo test private key
     */
    public function testExceptionIsThrownIfJobOrRevisionIdsAreNotPreconfiguredAndJobApiCallIsMadeWithoutThem()
    {
        Config::setAPIkey(GENGO_PUBKEY);
        Config::setPrivateKey(GENGO_PRIVKEY);

        $jobAPI = new Job();

        $this->expectException(Exception::CLASS);
        $this->expectExceptionMessage('ID job_id is not set');
        $jobAPI->getRevision();
    } //end testExceptionIsThrownIfJobOrRevisionIdsAreNotPreconfiguredAndJobApiCallIsMadeWithoutThem()

    /**
     * Test refusal to accept wrong job ID.
     */
    public function testRefusesToAcceptInvalidPreconfiguredJobId()
    {
        $this->expectException(Exception::CLASS);
        $this->expectExceptionMessage('Invalid job ID');
        Config::setJobID('wrong_id');
    } //end testRefusesToAcceptInvalidPreconfiguredJobId()

    /**
     * Test refusal to accept wrong revision ID.
     */
    public function testRefusesToAcceptInvalidPreconfiguredRevisionId()
    {
        $this->expectException(Exception::CLASS);
        $this->expectExceptionMessage('Invalid revision ID');
        Config::setRevisionID('wrong_id');
    } //end testRefusesToAcceptInvalidPreconfiguredRevisionId()
} //end class
