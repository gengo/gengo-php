<?php

/**
 * Retrieves the comment thread for a job.
 */

require_once '../init.php';

// TODO: this example assumes you replaced the 3 values below.
$api_key = 'your-public-api-key';
$private_key = 'your private-api-key';
$job_id = 1;

// Get an instance of Job Client
$job_client = Gengo_Api::factory('job', $api_key, $private_key);

// Get all the comments for this job.
$job_client->getComments($job_id);

// Display the server response.
echo $job_client->getResponseBody();

/**
 * Typical response:
 {"opstat":"ok","response":{"thread":[
 {"body":"By the way, the context of this text is a game for kids!","author":"customer","ctime":1313498703},
 {"body":"I see. So I guess I'll have to take care of vocabulary.","author":"worker","ctime":1313498803}
 ]}}
 */

?>

