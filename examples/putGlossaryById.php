<?php

/**
 * Update glossary file
 */

require_once '../init.php';

// TODO: this example assumes you set the 4 values below.
$api_key = 'your-public-api-key';
$private_key = 'your-private-api-key';
$glossary_id = 839;
$file_path = '(file_path)';

// Get an instance of an Service Client
$service = Gengo_Api::factory('glossary', $api_key, $private_key);
$service->setBaseUrl('https://api.gengo.com/');

$service->putGlossary($glossary_id, $file_path);

// Display server response.
echo $service->getResponseBody();

/**
 * Typical response:
 *  {
 *      "id": 839,
 *      "link": "https://s3.amazonaws.com/mygengo-v2.1/839-154422-ja_to_en.csv?AWSAccessKeyId=AKIAILMT2N26IBPMPXIQ&Expires=1401956400&Signature=TWh%2FGyBb8c%2Bl1wfkSF8ecfNfTSc%3D",
 *      "number_of_entries": 7,
 *      "source_language_code": "ja",
 *      "target_language_code": "en-US",
 *      "title": "ja_to_en.csv"
 *  }
 */

?>
