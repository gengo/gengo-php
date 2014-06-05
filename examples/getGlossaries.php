<?php

/**
 * Returns details of glossary files
 */

require_once '../init.php';

// TODO: this example assumes you set the 2 values below.
$api_key = 'your-public-api-key';
$private_key = 'your-private-api-key';

// Get an instance of an Service Client
$service = Gengo_Api::factory('glossary', $api_key, $private_key);
$service->setBaseUrl('https://api.gengo.com/');

$service->getGlossaries();

// Display server response.
echo $service->getResponseBody();

/**
 * Typical response: a list of your glossary files
 * {
 *      "opstat": "ok",
 *      "response": [
 *          {
 *              "ctime": "2014-06-03 02:33:20.448192",
 *              "customer_user_id": 154422,
 *              "description": null,
 *              "id": 963,
 *              "is_public": false,
 *              "source_language_code": "ja",
 *              "source_language_id": 14,
 *              "status": 1,
 *              "target_languages": [
 *                  [
 *                      8,
 *                      "en-US"
 *                  ]
 *              ],
 *              "title": "ja_to_en.csv",
 *              "unit_count": 7
 *          },
 *          {
 *              "ctime": "2014-06-02 09:57:39.322906",
 *              "customer_user_id": 154422,
 *              "description": null,
 *              "id": 962,
 *              "is_public": false,
 *              "source_language_code": "ja",
 *              "source_language_id": 14,
 *              "status": 1,
 *              "target_languages": [
 *                  [
 *                      8,
 *                      "en-US"
 *                  ]
 *              ],
 *              "title": "ja_to_en.csv",
 *              "unit_count": 7
 *          }
 *      ]
 *  }
 */
?>
