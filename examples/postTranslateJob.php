<?php

/**
 * Submits a single job for translation.
 */

require_once '../init.php';

// TODO: this example assumes you replace the 2 values below.
$api_key = 'your-public-api-key';
$private_key = 'your private-api-key';

$job = array(
        'type' => 'text',
        'slug' => 'API Job test',
        'body_src' => 'plop!',
        'lc_src' => 'en',
        'lc_tgt' => 'ja',
        'tier' => 'standard',
        // 'force' => 1, // optional. Default to 0.
        // 'auto_approve' => 1, // optional. Default to 0.
        'custom_data' => '1234567日本語'
        );

// Get an instance of Job Client
$job_client = Gengo_Api::factory('job', $api_key, $private_key);

// Post a new job.
$job_client->postJob($job);

// Display the server response.
echo $job_client->getResponseBody();

/**
 * Typical response:
  {"opstat":"ok","response":{"job":
    {"job_id":"384985",
    "slug":"API Job test", "body_src":"plop!",
    "lc_src":"en","lc_tgt":"ja","unit_count":"1","tier":"standard",
    "credits":"0.05","status":"available","eta":"","ctime":1313475693,
    "auto_approve":"0","custom_data":"1234567\u65e5\u672c\u8a9e",
    "body_tgt":"Some optional Machine translation for you to wait a little the real translation.","mt":1}}}
 */

?>

