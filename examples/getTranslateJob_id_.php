<?php

/**
 *	Get the status of a Gengo job.
 */

require_once '../init.php';

// TODO: this example assumes you replace the 3 value below.
$api_key = 'your-public-api-key';
$private_key = 'your private-api-key';
$job_id = 1;

// Get an instance of Job Client
$job_client = Gengo_Api::factory('job', $api_key, $private_key);

// Get the job.
$job_client->getJob($job_id);

// Show the server response in depth if you need it.
echo $job_client->getResponseBody();

/**
 * Typical answer:
 {"opstat":"ok","response":{"job":
    {"job_id":"384985","slug":"API Job test",
    "body_src":"plop!","lc_src":"en","lc_tgt":"ja","unit_count":"1","tier":"standard",
    "credits":"0.05","status":"available","eta":"","ctime":1313475693,"auto_approve":"0",
    "custom_data":"1234567\u65e5\u672c\u8a9e"}}}
 */

?>
