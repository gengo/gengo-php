<?php

/**
 * Submits a new comment to the order's comment thread.
 */

require_once '../init.php';

// TODO: this example assumes you replaced the 3 values below.
$api_key = 'your-public-api-key';
$private_key = 'your private-api-key';
$order_id = 1;

// Get an instance of Order Client
$order_client = Gengo_Api::factory('order', $api_key, $private_key);

// Post a new comment.
$comment = 'By the way, the context of this text is a game for kids!';
$order_client->postComment($order_id, $comment);

// Display the server response.
echo $order_client->getResponseBody();

/**
 * Typical response:
 {"opstat":"ok","response":{}}
 */

?>

