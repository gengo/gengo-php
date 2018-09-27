<?php

/**
 * PHP version 5.6.
 *
 * @package Gengo\Tests
 */

namespace Gengo\Tests;

use Gengo\Config;
use Gengo\Glossary;
use PHPUnit_Framework_TestCase;

/**
 * Glossary class tests.
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
class GlossaryTest extends PHPUnit_Framework_TestCase
{
    /**
     * Set up tests.
     *
     *
     * @internalconst GENGO_PUBKEY  "pubkeyfortests"                               Gengo test public key
     * @internalconst GENGO_PRIVKEY "privatekeyfortestuserthatcontainsonlyletters" Gengo test private key
     */
    public function setUp()
    {
        Config::setAPIkey(GENGO_PUBKEY);
        Config::setPrivateKey(GENGO_PRIVKEY);
    } //end setUp()

    /**
     * Test retrieval of glossaries.
     */
    public function testRetrievesAListOfGlossariesThatBelongsToTheAuthenticatedUser()
    {
        $glossaryAPI = new Glossary();

        $response = json_decode($glossaryAPI->getGlossaries(), true);
        $this->assertEquals('ok', $response['opstat']);
        $this->assertTrue(isset($response['response']));
        $this->assertTrue(empty($response['response']));
    } //end testRetrievesAListOfGlossariesThatBelongsToTheAuthenticatedUser()

    /**
     * Test retrieval of glossary by ID.
     */
    public function testRetrievesAGlossaryById()
    {
        $glossaryAPI = new Glossary();

        $response = json_decode($glossaryAPI->getGlossary(123), true);
        $this->assertEquals('error', $response['opstat']);
        $this->assertEquals('404', $response['err']['code']);
        $this->assertEquals('Requested Resource Not Found', $response['err']['msg']);
    } //end testRetrievesAGlossaryById()
} //end class
