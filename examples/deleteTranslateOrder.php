<?php

/**
 * Delete an order, cancels all available jobs and the order itself
 */

require_once '../init.php';

// TODO: this example assumes you replaced the 3 values below.
$api_key = 'your-public-api-key';
$private_key = 'your private-api-key';
$order_id = 1;

// Get an instance of Job Client
$order_client = Gengo_Api::factory('order', $api_key, $private_key);

// Cancel a job which has not been started by a translator.
$order_client->cancel($order_id);

// Display the server response.
echo $order_client->getResponseBody();

/**
 * Typical response:
 {"opstat":"ok","response":{}}
 */

?>