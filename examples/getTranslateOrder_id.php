<?php
/**
 * Submits one or several jobs to translate.
 */

require_once '../init.php';

// TODO: this example assumes you replaced the 2 values below.
$api_key = 'your-public-api-key';
$private_key = 'your private-api-key';

// Get an instance of Jobs Client
$job_client = Gengo_Api::factory('order', $api_key, $private_key);

// Post the jobs. The second parameter is optional and determinates whether or
// not the jobs are submitted as a group (default: false).
$job_client->getOrder(102284);

// Display the server response.
echo $job_client->getResponseBody();

/*
*Typical response
{
    "opstat": "ok",
    "response": {
        "order": {
            "total_credits": "0.20",
            "currency": "USD",
            "total_units": "4",
            "jobs_available": [
                "218344",
                "218345"
            ],
            "total_jobs": "2"
        }
    }
}
*/

?>
