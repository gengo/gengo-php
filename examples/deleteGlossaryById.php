<?php

/**
 * Delete a Glossary file
 */

require_once '../init.php';

// TODO: this example assumes you set the 3 values below.
$api_key = 'your-public-api-key';
$private_key = 'your-private-api-key';
$glossary_id = 1;

// Get an instance of a Glossary Client
$service = Gengo_Api::factory('glossary', $api_key, $private_key);
$service->setBaseUrl('https://api.gengo.com/');

$service->deleteGlossary($glossary_id);

// Display server response.
echo $service->getResponseBody();

/**
 * No response
 */

?>
