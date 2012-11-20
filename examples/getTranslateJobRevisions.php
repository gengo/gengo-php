<?php

/**
 * Gets list of revision resources for a job.
 */

require_once '../init.php';

// TODO: this example assumes you replaced the 3 values below.
$api_key = 'your-public-api-key';
$private_key = 'your private-api-key';
$job_id = 1;

// Get an instance of Job Client
$job_client = Gengo_Api::factory('job', $api_key, $private_key);

// Get the revisions' list.
$job_client->getRevisions($job_id);

// Display the server response.
echo $job_client->getResponseBody();

/**
 * Typical response: a list of revisions id and their timestamp.
  {"opstat":"ok","response":{"job_id":"384988","revisions":[
    {"ctime":1313495744,"rev_id":"3333756"},
    {"ctime":1313495841,"rev_id":"3333759"}
    ]}}
 */

?>


