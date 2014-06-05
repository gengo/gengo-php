<?php

/**
 * Returns contents of glossary files
 */

require_once '../init.php';

// TODO: this example assumes you set the 3 values below.
$api_key = 'your-public-api-key';
$private_key = 'your-private-api-key';
$glossary_id = 817;

// Get an instance of an Service Client
$service = Gengo_Api::factory('glossary', $api_key, $private_key);
$service->setBaseUrl('https://api.gengo.com/');

$service->getGlossaryDetails($glossary_id);

// Display server response.
echo $service->getResponseBody();

/**
 * Typical response:
 *  {
 *      "entries": [
 *          {
 *              "source": "gengo",
 *              "target": "ゲンゴ"
 *          },
 *          {
 *              "source": "google",
 *              "target": "グーグル"
 *          },
 *          {
 *              "source": "yahoo",
 *              "target": "ヤフー"
 *          }
 *      ],
 *      "id": 817,
 *      "link": "https://s3.amazonaws.com/mygengo-v2.1/817-154422-1396232814_154422_en_to_ja.csv?AWSAccessKeyId=AKIAILLT2N26IBPMPXIQ&Expires=1401954777&Signature=qQls%2B8fujUrQQ2SmbLdxVxf7Ld8%3D",
 *      "source_language_code": "en",
 *      "target_language_code": "ja",
 *      "title": "1396232814_154422_en_to_ja.csv"
 *  }
 */

?>
