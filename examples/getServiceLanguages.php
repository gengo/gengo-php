<?php

/**
 * Returns a list of supported languages and their language codes.
 */

require_once '../init.php';

// TODO: this example assumes you set the 2 values below.
$api_key = 'your-public-api-key';
$private_key = 'your private-api-key';

// Get an instance of an Service Client
$service = Gengo_Api::factory('service', $api_key, $private_key);

// Request the languages.
$service->getLanguages(); 

// Display server response.
echo $service->getResponseBody();

/**
 * Typical response: a list of language, their name in English and in the locale
 * itself, their language code (RFC-4656) and the unit type (word or character).
 {"opstat":"ok","response":[
    {"language":"English","localized_name":"English","lc":"en","unit_type":"word"},
    {"language":"Japanese","localized_name":"\u65e5\u672c\u8a9e","lc":"ja","unit_type":"character"},
    {"language":"Spanish (Spain)","localized_name":"Espa\u00f1ol","lc":"es","unit_type":"word"},
    {"language":"Chinese (Simplified)","localized_name":"\u4e2d\u6587","lc":"zh","unit_type":"character"},
    {"language":"German","localized_name":"Deutsch","lc":"de","unit_type":"word"},
    {"language":"French","localized_name":"Fran\u00e7ais","lc":"fr","unit_type":"word"},
    {"language":"Italian","localized_name":"Italiano","lc":"it","unit_type":"word"},
    {"language":"Portuguese (Brazil)","localized_name":"Portugu\u00eas Brasileiro","lc":"pt-br","unit_type":"word"},
    {"language":"Spanish (Latin America)","localized_name":"Espa\u00f1ol (Am\u00e9rica Latina)","lc":"es-la","unit_type":"word"},
    {"language":"Portuguese (Europe)","localized_name":"Portugu\u00eas Europeu","lc":"pt","unit_type":"word"}]}
 */

?>
