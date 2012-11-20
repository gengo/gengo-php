<?php

/**
 * Gets a specific revision for a job.
 * Note: you can only view revisions of a job once it has been approved.
 */

require_once '../init.php';

// TODO: this example assumes you replaced the 4 values below.
$api_key = 'your-public-api-key';
$private_key = 'your private-api-key';
$job_id = 1;
$rev_id = 1;

// Get an instance of Job Client
$job_client = Gengo_Api::factory('job', $api_key, $private_key);

// Get the revision.
$job_client->getRevision($job_id, $rev_id);

// Display the server response.
echo $job_client->getResponseBody();

/**
 * Typical response: a revision timestamp and its value.
 {"opstat":"ok","response":{"revision":{"ctime":1313495841,"body_tgt":"\u30d7\u30ed\u30d7!"}}}
 */

?>


