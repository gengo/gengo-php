<?php

/**
 * Updates jobs to translate.
 */

// TODO: this example assumes you replaced the 3 values below.
$api_key = 'your-public-api-key';
$private_key = 'your private-api-key';
$job_ids = Array(234,);

require_once '../init.php';

// Get an instance of Jobs Client
$job_client = Gengo_Api::factory('jobs', $api_key, $private_key);

$job_client->archive($job_ids);

// Display the server response.
echo $job_client->getResponseBody();

/**
 * Typical response of any of these queries when they succeed:
 {"opstat":"ok","response":{***}}
 */

?>
