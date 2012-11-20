<?php

/**
 * Returns credit quote and unit count for text based on content, tier, and
 * language pair for job or jobs submitted.
 */

require_once '../init.php';

// TODO: this example assumes you set the 2 values below.
$api_key = 'your-public-api-key';
$private_key = 'your private-api-key';

// Get an instance of an Service Client
$service = Gengo_Api::factory('service', $api_key, $private_key);

$job1 = array(
        'type' => 'text',
        'body_src' => 'plop!',
        'lc_src' => 'en',
        'lc_tgt' => 'es',
        'tier' => 'standard',
        );

$job2 = array(
        'type' => 'text',
        'body_src' => 'plop plop!',
        'lc_src' => 'en',
        'lc_tgt' => 'es',
        'tier' => 'pro',
        );

// The parameter is an array of jobs. If you set custom keys, they will be
// mirrored in the response. Otherwise, default numerical keying applies. This
// helps to keep track of which job corresponds to which quote.
$jobs = array("key 1" => $job1, "key 2" => $job2);

// Request quotes.
$service->quote($jobs);

// Display server response.
echo $service->getResponseBody();

/**
 * Typical response: a list with unit count and credits the job would cost.
 {"opstat":"ok","response":{"jobs":{
    "key 1":{"unit_count":1,"credits":0.05},
    "key 2":{"unit_count":2,"credits":0.2}}}}j
 */

?>
