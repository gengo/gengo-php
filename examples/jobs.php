<?php
/**
 * jobs api example
 * translate/jobs (POST)
 * translate/jobs (GET)
 * translate/jobs/{id} (GET)
 */
require_once '../init.php';

// get configs
$config = Gengo_Config::getInstance();
// retrieve keys from config file
$api_key = $config->get('api_key', null, true);
$private_key = $config->get('private_key', null, true);
// retrieve order_id from config, the group_id is normaly returned
// when groupable jobs are submitted with "as_group = 1"
$group_id = $config->get('group_id', null, true);

// get an instance of Job Client
$jobs = Gengo_Api::factory('jobs');

// --------------------------------------------------------------
// Create groupable jobs for submition
// --------------------------------------------------------------
$job1 = array(
    'type' => 'text',
    'slug' => 'API Job 1 test',
    'body_src' => 'Text to be translated goes here.',
    'lc_src' => 'en',
    'lc_tgt' => 'ja',
    'tier' => 'standard',
    'auto_approve' => 'true',
    'custom_data' => '1234567日本語'
);
$job2 = array(
    'type' => 'text',
    'slug' => 'API Job 1 test',
    'body_src' => 'Text to be translated goes here.',
    'lc_src' => 'en',
    'lc_tgt' => 'ja',
    'tier' => 'standard',
    'auto_approve' => 'true',
    'custom_data' => '1234567日本語'
);
// pack the jobs
$data = array('jobs' => array('job_1' => $job1, 'job_2' => $job2),
              'as_group' => 1,
              'process' => 1);

// create the query
$params = array('api_key' => $api_key, '_method' => 'post',
                'ts' => gmdate('U'),
                'data' => json_encode($data));
// sort and sign
ksort($params);
$enc_params = json_encode($params);
$params['api_sig'] = Gengo_Crypto::sign($enc_params, $private_key);

/**
 * translate/jobs (POST)
 * Submits a job or group of jobs to translate.
 */
$jobs->postJobs('json', $params);
// echo back server response
echo $jobs->getResponseBody();
echo "\n\n";

/**
 * translate/jobs (GET)
 * Retrieves a list of resources for the most recent jobs filtered
 * by the given parameters.
 */
$jobs->getJobs('json');
// echo back server response
echo $jobs->getResponseBody();
echo "\n\n";

$job_client->getJobs(array(1, 500, 1000));
// echo back server response
echo $job_client->getResponseBody();
echo "\n\n";

/**
 * translate/jobs/{id} (GET)
 * Retrieves the group of jobs that were previously submitted
 * together.
 */
$jobs->getGroupedJobs($group_id, 'json');
// echo back server response
echo $jobs->getResponseBody();
echo "\n\n";
