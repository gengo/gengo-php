<?php

/**
 * Returns detail of a glossary file
 */

require_once '../init.php';

// TODO: this example assumes you set the 3 values below.
$api_key = 'your-public-api-key';
$private_key = 'your-private-api-key';
$glossary_id = 817;

// Get an instance of an Service Client
$service = Gengo_Api::factory('glossary', $api_key, $private_key);
$service->setResponseFormat('xml');
$service->setBaseUrl('https://api.staging.gengo.com/');

$service->getGlossary($glossary_id);

// Display server response.
echo $service->getResponseBody();

/**
 * Typical response:
 *  {
 *      "opstat": "ok",
 *      "response": {
 *          "ctime": "2014-03-31 02:26:54.967180",
 *          "customer_user_id": 154422,
 *          "description": null,
 *          "id": 817,
 *          "is_public": false,
 *          "source_language_code": "en-US",
 *          "source_language_id": 8,
 *          "status": 1,
 *          "target_languages": [
 *              [
 *                  14,
 *                  "ja"
 *              ]
 *          ],
 *          "title": "1396232814_154422_en_to_ja.csv",
 *          "unit_count": 6
 *      }
 *  }
 */

?>
