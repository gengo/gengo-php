<?php

/**
 * Submits a new comment to the job's comment thread.
 */

require_once '../init.php';

// TODO: this example assumes you replaced the 3 values below.
$api_key = 'your-public-api-key';
$private_key = 'your private-api-key';
$job_id = 1;

// Get an instance of Job Client
$job_client = Gengo_Api::factory('job', $api_key, $private_key);

// Post a new comment.
$comment = 'By the way, the context of this text is a game for kids!';
$job_client->postComment($job_id, $comment);

// Display the server response.
echo $job_client->getResponseBody();

/**
 * Typical response:
 {"opstat":"ok","response":{}}
 */

?>

