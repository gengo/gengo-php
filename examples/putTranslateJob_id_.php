<?php

/**
 * Updates a job to translate.
 */

// TODO: this example assumes you replaced the 2 values below.
$api_key = 'your-public-api-key';
$private_key = 'your private-api-key';

$usage = "Usage: php -f putTranslateJob.php [action] [job_id] {captcha}
[action] is one of (approve, revise, reject).
If [action] is 'reject', provide a [captcha]. \n";

if ($argc < 3
    || !in_array($argv[1], array('approve', 'revise', 'reject'))
    || ($argv[1] == 'reject' && $argc < 4))
{
    echo $usage;
    exit;
}

$action = $argv[1];
$job_id = $argv[2];
if ($action == 'reject')
{
    $captcha = $argv[3];
}

require_once '../init.php';

// Get an instance of Job Client
$job_client = Gengo_Api::factory('job', $api_key, $private_key);

// The update call has been divided into 3 meaningful methods, one for each action.
switch ($action)
{
    case 'approve':
        $approve = array(
                'rating' => 5,
                'for_translator' => 'Thanks, nice translation.',
                'for_mygengo' =>'Gengo really gives me great satisfaction!',
                'public' => 1 // Can Gengo share your feedback publicly (optional, default 0)?
                );
        $job_client->approve($job_id, $approve);
        break;

    case 'revise':
        $comment = 'Nice but not perfect. Could you check the first word?';
        $job_client->revise($job_id, $comment);
        break;

    case 'reject':
        $reject = array(
            'reason' => 'incomplete',
            'comment' => 'It seems the translator did not finish the job.',
            'captcha' => $captcha,
            // 'follow_up' => 'cancel' // optional. Default: 'requeue'
            );
        $job_client->reject($job_id, $reject);
}

// Display the server response.
echo $job_client->getResponseBody();

/**
 * Typical response of any of these queries when they succeed:
 {"opstat":"ok","response":{}}
 */

?>
