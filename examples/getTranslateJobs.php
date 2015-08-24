<?php
/**
 * Retrieves a list of jobs by their ids.
 */

require_once '../init.php';

// TODO: this example assumes you replaced the 3 values below.
$api_key = 'your-public-api-key';
$private_key = 'your private-api-key';

// Get an instance of Job Client
$job_client = Gengo_Api::factory('jobs', $api_key, $private_key);

// Prepare $params
$ts = gmdate('U');
$params = array('api_key' => $api_key,
                'ts'      => $ts,
                'api_sig' => Gengo_Crypto::sign($ts, $private_key));

// [OPTIONAL] Defaults to 10 (maximum 200)
$params['count'] = 5;
// [OPTIONAL] “available”, “pending”, “reviewable”, “approved”, “rejected”, or “canceled”
$params['status'] = 'available';

// Get the jobs.
$job_client->getJobs(null, null, $params);

// Show the server response in depth if you need it.
echo $job_client->getResponseBody();

/**
 * Typical answer: the list of jobs corresponding to the ids.
 {"opstat":"ok","response":{"jobs":[
    {"job_id":"384994","body_src":"plop plop!","lc_src":"en","lc_tgt":"ja","unit_count":"2","tier":"standard","credits":"0.10","status":"available","eta":"","ctime":1313504670,"auto_approve":"0","custom_data":"1234567\u65e5\u672c\u8a9e"},
    {"job_id":"384995","body_src":"hello!","lc_src":"en","lc_tgt":"ja","unit_count":"1","tier":"standard","credits":"0.05","status":"available","eta":"","ctime":1313504671,"auto_approve":"0","custom_data":"custom data 3"}
 ]}}
 */

?>
