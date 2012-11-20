<?php

/**
 * Delete a job already sent into Gengo.
 */

require_once '../init.php';

// TODO: this example assumes you replaced the 3 values below.
$api_key = 'your-public-api-key';
$private_key = 'your private-api-key';
$job_id = 1;

// Get an instance of Job Client
$job_client = Gengo_Api::factory('job', $api_key, $private_key);

// Cancel a job which has not been started by a translator.
$job_client->cancel($job_id);

// Display the server response.
echo $job_client->getResponseBody();

/**
 * Typical response:
 {"opstat":"ok","response":{}}
 */

?>
