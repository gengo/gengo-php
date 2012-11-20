<?php

/**
 * Renders a JPEG preview image of the translated text.
 */

// TODO: this example assumes you replaced the 2 values below.
$api_key = 'your-public-api-key';
$private_key = 'your private-api-key';

$usage = "Usage: php -f getTranslateJobPreview.php [job_id] [output]
An [output] of '-' will returns the raw JPEG stream to stdout.\n";

if ($argc < 3)
{
    echo $usage;
    exit;
}
$job_id = $argv[1];
$output = $argv[2];

require_once '../init.php';

// Get an instance of Job Client
$job_client = Gengo_Api::factory('job', $api_key, $private_key);

// Get the preview image.
$job_client->previewJob($job_id);

if ($output == '-' || !is_null(json_decode($job_client->getResponseBody())))
{
    // if the output is stdout or if the query had an error (hence json
    // response), I display.
    echo $job_client->getResponseBody();
}
else
{
    // Otherwise, I create the JPEG image from the raw returned data.
    $fp = fopen($output, 'w');
    fwrite($fp, $job_client->getResponseBody());
    fclose($fp);
}

/**
 * Typical response: raw JPEG data.
 */

?>

