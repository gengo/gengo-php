<?php

/**
 * Retrieves the group of jobs that were previously submitted together.
 */

require_once '../init.php';

// TODO: this example assumes you replaced the 3 values below.
$api_key = 'your-public-api-key';
$private_key = 'your private-api-key';
$group_id = 1;

// Get an instance of Job Client
$job_client = Gengo_Api::factory('jobs', $api_key, $private_key);

// Get the jobs.
$job_client->getGroupedJobs($group_id);

// Show the server response in depth if you need it.
echo $job_client->getResponseBody();

/**
 * Typical answer: a list of job ids and the time of the order.
 {"opstat":"ok","response":{"jobs":[{"job_id":"384995"},{"job_id":"384994"}],"ctime":1313504671}}
 */

?>
