<?php

/**
 * Retrieves the feedback you have submitted for a particular job.
 */

require_once '../init.php';

// TODO: this example assumes you replaced the 3 values below.
$api_key = 'your-public-api-key';
$private_key = 'your private-api-key';
$job_id = 1;

// Get an instance of Job Client
$job_client = Gengo_Api::factory('job', $api_key, $private_key);

// Get the feedback.
$job_client->getFeedback($job_id);

// Display the server response.
echo $job_client->getResponseBody();

/**
 * Typical response: a list of revisions id and their timestamp.
 {"opstat":"ok","response":{"feedback":{"rating":"3.0","for_translator":"Thanks, nice translation."}}}
 */

?>


