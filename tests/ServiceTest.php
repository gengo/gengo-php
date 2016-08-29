<?php

/**
 * PHP version 5.6
 *
 * @package Gengo\Tests
 */

namespace Gengo\Tests;

use \Gengo\Config;
use \Gengo\Service;
use \PHPUnit_Framework_TestCase;

/**
 * Service class tests
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
 * @version   GIT: $Id:$
 * @link      https://github.com/gengo/gengo-php
 *
 * @runTestsInSeparateProcesses
 *
 * @donottranslate
 */

class ServiceTest extends PHPUnit_Framework_TestCase
    {

	/**
	 * Set up tests
	 *
	 * @return void
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
	 * Test retriveal of supported language pairs, tiers and credit prices
	 *
	 * @return void
	 */

	public function testReturnsSupportedTranslationLanguagePairsTiersAndCreditPrices()
	    {
		$serviceAPI = new Service();

		$response = json_decode($serviceAPI->getLanguagePairs(), true);
		$this->assertEquals("ok", $response["opstat"]);
		$this->assertTrue(isset($response["response"]));
	    } //end testReturnsSupportedTranslationLanguagePairsTiersAndCreditPrices()


	/**
	 * Test retrieveal of a list of supported languages and their language codes
	 *
	 * @return void
	 */

	public function testReturnsAListOfSupportedLanguagesAndTheirLanguageCodes()
	    {
		$serviceAPI = new Service();

		$response = json_decode($serviceAPI->getLanguages(), true);
		$this->assertEquals("ok", $response["opstat"]);
		$this->assertTrue(isset($response["response"]));
	    } //end testReturnsAListOfSupportedLanguagesAndTheirLanguageCodes()


	/**
	 * Test quotation and unit cound for text based on content, tier and language pair for job or jobs submitted
	 *
	 * @return void
	 */

	public function testReturnsCreditQuoteAndUnitCountForTextBasedOnContentTierAndLanguagePairForJobOrJobsSubmitted()
	    {
		$job1 = array(
			 "type"     => "file",
			 "file_key" => "file_01",
			 "lc_src"   => "en",
			 "lc_tgt"   => "ja",
			 "tier"     => "standard",
			);

		$job2 = array(
			 "type"     => "file",
			 "file_key" => "file_02",
			 "lc_src"   => "en",
			 "lc_tgt"   => "ja",
			 "tier"     => "standard",
			);

		$jobs = array(
			 "job_01" => $job1,
			 "job_02" => $job2,
			);

		$files = array(
			  "file_01" => __DIR__ . "/testfiles/test_file1.txt",
			  "file_02" => __DIR__ . "/testfiles/test_file2.txt",
			 );

		$serviceAPI = new Service();

		$response = json_decode($serviceAPI->quote($jobs, $files), true);
		$this->assertEquals("ok", $response["opstat"]);
		$this->assertTrue(isset($response["response"]));
		$this->assertTrue(isset($response["response"]["jobs"]["job_01"]["identifier"]));
		$this->assertTrue(isset($response["response"]["jobs"]["job_02"]["identifier"]));

		$job1 = array(
			 "type"     => "text",
			 "body_src" => "plop!",
			 "lc_src"   => "en",
			 "lc_tgt"   => "es",
			 "tier"     => "standard",
			);

		$job2 = array(
			 "type"     => "text",
			 "body_src" => "plop plop!",
			 "lc_src"   => "en",
			 "lc_tgt"   => "es",
			 "tier"     => "pro",
			);

		$jobs = array(
			 "key 1" => $job1,
			 "key 2" => $job2,
			);

		$response = json_decode($serviceAPI->quote($jobs), true);
		$this->assertEquals("ok", $response["opstat"]);
		$this->assertTrue(isset($response["response"]));
		$this->assertEquals(1, $response["response"]["jobs"]["key 1"]["unit_count"]);
		$this->assertEquals(2, $response["response"]["jobs"]["key 2"]["unit_count"]);
	    } //end testReturnsCreditQuoteAndUnitCountForTextBasedOnContentTierAndLanguagePairForJobOrJobsSubmitted()


	/**
	 * Test refuses to provide quotation if file_key parameter is missing
	 *
	 * @return void
	 *
	 * @expectedException        Exception
	 * @expectedExceptionMessage is missing file_key parameter
	 */

	public function testRefusesToProvideQuotationIfFileKeyParameterIsMissing()
	    {
		$jobs = array(
			 "job_01" => array(
				      "type"   => "file",
				      "lc_src" => "en",
				      "lc_tgt" => "ja",
				      "tier"   => "standard",
				     ),
			);

		$files = array("file_01" => __DIR__ . "/testfiles/test_file1.txt");

		$serviceAPI = new Service();

		$serviceAPI->quote($jobs, $files);
	    } //end testRefusesToProvideQuotationIfFileKeyParameterIsMissing()


	/**
	 * Test refuses to provide quotation if file_key parameter is invalid
	 *
	 * @return void
	 *
	 * @expectedException        Exception
	 * @expectedExceptionMessage is not a valid file_key parameter
	 */

	public function testRefusesToProvideQuotationIfFileKeyParameterIsInvalid()
	    {
		$jobs = array(
			 "job_01" => array(
				      "type"     => "file",
				      "file_key" => "invalid_file_key!",
				      "lc_src"   => "en",
				      "lc_tgt"   => "ja",
				      "tier"     => "standard",
				     ),
			);

		$files = array("file_01" => __DIR__ . "/testfiles/test_file1.txt");

		$serviceAPI = new Service();

		$serviceAPI->quote($jobs, $files);
	    } //end testRefusesToProvideQuotationIfFileKeyParameterIsInvalid()


	/**
	 * Test refuses to provide quotation if file_key parameter does not have corresponding record in files array
	 *
	 * @return void
	 *
	 * @expectedException        Exception
	 * @expectedExceptionMessage is missing in filepath array
	 */

	public function testRefusesToProvideQuotationIfFileKeyParameterDoesNotHaveCorrespondingRecordInFilesArray()
	    {
		$jobs = array(
			 "job_01" => array(
				      "type"     => "file",
				      "file_key" => "no_such_file",
				      "lc_src"   => "en",
				      "lc_tgt"   => "ja",
				      "tier"     => "standard",
				     ),
			);

		$files = array("file_01" => __DIR__ . "/testfiles/test_file1.txt");

		$serviceAPI = new Service();

		$serviceAPI->quote($jobs, $files);
	    } //end testRefusesToProvideQuotationIfFileKeyParameterDoesNotHaveCorrespondingRecordInFilesArray()


	/**
	 * Test refuses to provide quotation if file specified in file array does not exist
	 *
	 * @return void
	 *
	 * @expectedException        Exception
	 * @expectedExceptionMessage Could not find file
	 */

	public function testRefusesToProvideQuotationIfFileSpecifiedInFileArrayDoesNotExist()
	    {
		$jobs = array(
			 "job_01" => array(
				      "type"     => "file",
				      "file_key" => "file_01",
				      "lc_src"   => "en",
				      "lc_tgt"   => "ja",
				      "tier"     => "standard",
				     ),
			);

		$files = array("file_01" => __DIR__ . "/testfiles/non_existent_file");

		$serviceAPI = new Service();

		$serviceAPI->quote($jobs, $files);
	    } //end testRefusesToProvideQuotationIfFileSpecifiedInFileArrayDoesNotExist()


    } //end class

?>
