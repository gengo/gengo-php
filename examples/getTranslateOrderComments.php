<?php

/**
 * Retrieves the comment thread for a order.
 */

require_once '../init.php';

// TODO: this example assumes you replaced the 3 values below.
$api_key = 'your-public-api-key';
$private_key = 'your private-api-key';
$order_id = 1;

// Get an instance of Order Client
$order_client = Gengo_Api::factory('order', $api_key, $private_key);

// Get all the comments for this order.
$order_client->getComments($order_id);

// Display the server response.
echo $order_client->getResponseBody();

/**
 * Typical response:
 {"opstat":"ok","response":{"thread":[
 {"body":"By the way, the context of this text is a game for kids!","author":"customer","ctime":1438669236},
 {"body":"By the way, the context of this text is a game for kids!","author":"customer","ctime":1438669212}
 ]}}
 */

?>

